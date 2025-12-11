<?php

namespace App\Services\Implementations\Payments;

use App\Traits\MakesHttpRequests;

class BasePaymentService
{
    use MakesHttpRequests;

    /**
     * Base URL for API requests
     */
    protected string $base_url;

    /**
     * Default headers for API requests
     */
    protected array $header;
}
