<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Services\Interfaces\PaymentGatewayInterface;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use ResponseTrait;
    public function __construct(protected PaymentGatewayInterface $paymentService)
    {
    }

    public function send_payment(PaymentRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $response = $this->paymentService->send_payment($data);
        return $this->success200(['order_id' => $response['id'], 'payment_url' => $response['url']]);
    }

    public function callBack(Request $request): JsonResponse
    {
        $response = $this->paymentService->callBack($request->all());
        if (!$response) {
            return $this->error400('Payment failed');
        }
        return $this->success200('Payment successful');
    }
}
