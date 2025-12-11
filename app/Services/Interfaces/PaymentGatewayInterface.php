<?php

namespace App\Services\Interfaces;

interface PaymentGatewayInterface
{

    public function send_payment(array $data): array;
    public function callBack(array $data): bool;
}
