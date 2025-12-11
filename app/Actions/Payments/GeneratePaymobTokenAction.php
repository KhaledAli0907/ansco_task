<?php

namespace App\Actions\Payments;

use App\Services\Implementations\Payments\BasePaymentService;
class GeneratePaymobTokenAction extends BasePaymentService
{
    public function __construct()
    {
        $this->base_url = config('services.paymob.base_url');
        $this->header = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }
    public function handle(): string
    {
        try {
            $apiKey = config('services.paymob.api_key');
            if (!$apiKey) {
                throw new \Exception('PAYMOB_API_KEY is not set');
            }
            $response = $this->buildRequest(
                method: 'POST',
                url: '/api/auth/tokens',
                data: ['api_key' => $apiKey]
            );
            if (!$response['success']) {
                throw new \Exception($response['message']);
            }
            return $response['data']['token'];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
