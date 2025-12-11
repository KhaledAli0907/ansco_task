<?php
declare(strict_types=1);
namespace App\Services\Implementations\Payments;

use App\Actions\CalculateSubscriptionEndDateAction;
use App\Actions\Payments\GeneratePaymobTokenAction;
use App\Models\Order;
use App\Models\UserSubscription;
use App\Services\Implementations\Payments\BasePaymentService;
use App\Services\Interfaces\PaymentGatewayInterface;
use Carbon\Carbon;
use DB;
use Log;

class PaymobPaymentService extends BasePaymentService implements PaymentGatewayInterface
{
    protected string $api_key;
    protected array $integrations_id;
    public function __construct()
    {
        $this->api_key = config('services.paymob.api_key');
        $this->base_url = config('services.paymob.base_url');
        $this->header = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $this->integrations_id = [
            5433488, // mobile wallet
            5433304 // cash on delivery
        ];
    }
    public function send_payment(array $data): array
    {
        // We can add validation based on the business logic
        // if (User::find($data['user_id'])->hasActiveSubscription()) {
        //     throw new \Exception('User already has an active subscription');
        // }
        Log::info('Sending payment for user: ' . $data['user_id']);
        $tokenAction = new GeneratePaymobTokenAction();
        $token = $tokenAction->handle();
        $this->header['Authorization'] = 'Bearer ' . $token;

        // Prepare order data
        $orderData = [
            'amount_cents' => $data['amount_cents'],
            'currency' => $data['currency'],
            'shipping_data' => $data['shipping_data'],
            'integrations' => $this->integrations_id,
            'api_source' => "INVOICE",
        ];

        $response = $this->buildRequest('POST', '/api/ecommerce/orders', $orderData);
        if (!$response['success']) {
            throw new \Exception($response['message'] ?? 'Failed to create order');
        }
        // Verify URL exists in response
        if (!isset($response['data']['url'])) {
            throw new \Exception('Payment URL not found in response. Check integration IDs: ' . implode(', ', $this->integrations_id));
        }

        // Map Paymob payment status to our status
        $paymobStatus = strtolower($response['data']['payment_status'] ?? 'unpaid');
        $paymentStatus = match ($paymobStatus) {
            'unpaid' => 'pending',
            'paid' => 'paid',
            'failed' => 'failed',
            'cancelled', 'canceled' => 'cancelled',
            'refunded' => 'refunded',
            default => 'pending',
        };

        DB::beginTransaction();
        try {
            // Save order to database
            $order = Order::create([
                'subscription_id' => $data['subscription_id'],
                'user_id' => $data['user_id'],
                'paymob_order_id' => $response['data']['id'],
                'payment_token' => $response['data']['token'] ?? null,
                'payment_url' => $response['data']['url'],
                'amount_cents' => $data['amount_cents'],
                'paid_amount_cents' => $response['data']['paid_amount_cents'] ?? 0,
                'currency' => $data['currency'],
                'payment_status' => $paymentStatus,
                'payment_method' => $response['data']['payment_method'] ?? null,
                'merchant_order_id' => $response['data']['merchant_order_id'] ?? null,
                'shipping_data' => $data['shipping_data'],
                'paymob_order_response' => $response['data'],
            ]);

            Log::info('Order created successfully', ['order_id' => $order->id]);

            DB::commit();
            return $response['data'];
        } catch (\Exception $e) {
            Log::error('Error creating order', ['error' => $e->getMessage()]);
            DB::rollBack();
            throw $e;
        }
    }

    public function callBack(array $data): bool
    {
        Log::info('Callback received', ['data' => $data]);
        // Check if payment was successful
        $isSuccessful = isset($data['success']) && $data['success'] === 'true';

        // Find order by Paymob order ID
        $paymobOrderId = $data['order'] ?? null;
        if (!$paymobOrderId) {
            Log::error('Paymob order ID not found');
            return false;
        }

        $order = Order::where('paymob_order_id', $paymobOrderId)->first();
        if (!$order) {
            Log::error('Order not found');
            return false;
        }

        Log::info('Order found', ['order_id' => $order->id]);

        DB::beginTransaction();
        try {
            // Update order with callback data
            Log::info('Updating order with callback data', ['data' => $data]);
            $order->update([
                'transaction_id' => $data['id'] ?? null,
                'integration_id' => $data['integration_id'] ?? null,
                'is_successful' => $isSuccessful,
                'transaction_response_code' => $data['txn_response_code'] ?? null,
                'transaction_message' => $data['data_message'] ?? null,
                'paid_amount_cents' => isset($data['amount_cents']) ? (int) $data['amount_cents'] : $order->paid_amount_cents,
                'payment_status' => $isSuccessful ? 'paid' : 'failed',
                'source_data_type' => $data['source_data_type'] ?? null,
                'source_data_pan' => $data['source_data_pan'] ?? null,
                'is_refunded' => isset($data['is_refunded']) ? filter_var($data['is_refunded'], FILTER_VALIDATE_BOOLEAN) : false,
                'is_voided' => isset($data['is_voided']) ? filter_var($data['is_voided'], FILTER_VALIDATE_BOOLEAN) : false,
                'refunded_amount_cents' => isset($data['refunded_amount_cents']) ? (int) $data['refunded_amount_cents'] : 0,
                'hmac' => $data['hmac'] ?? null,
                'paymob_callback_response' => $data,
            ]);

            // Create user subscription if payment is successful
            if ($isSuccessful && !$order->userSubscription) {
                $subscription = $order->subscription;
                if ($subscription) {
                    Log::info('Subscription found', ['subscription_id' => $subscription->id]);
                    $startDate = Carbon::now();
                    $endDate = app(CalculateSubscriptionEndDateAction::class)->handle(
                        startDate: $startDate,
                        duration: $subscription->duration,
                        durationType: $subscription->duration_type
                    );

                    // Expire all active subscriptions for this user
                    UserSubscription::where('user_id', $order->getUserId())
                        ->where('status', 'active')
                        ->where('end_date', '>', now())
                        ->update([
                            'status' => 'expired',
                        ]);

                    // Create new subscription
                    UserSubscription::create([
                        'user_id' => $order->getUserId(),
                        'subscription_id' => $order->getSubscriptionId(),
                        'order_id' => $order->id,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'status' => 'active',
                    ]);
                    Log::info('New subscription created', ['subscription_id' => $subscription->id]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error creating subscription', ['error' => $e->getMessage()]);
            DB::rollBack();
            throw $e;
        }

        DB::commit();
        Log::info('Callback processed successfully');
        return $isSuccessful;
    }
}
