<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Http;

trait MakesHttpRequests
{
    /**
     * Make an HTTP request with standardized response format
     *
     * @param string $method HTTP method (GET, POST, etc.)
     * @param string $url Endpoint URL (relative to base_url)
     * @param array|null $data Request payload
     * @param string $type Request body type: 'json' or 'form_params'
     * @return array Standardized response format
     */
    public function buildRequest(string $method, string $url, ?array $data = null, string $type = 'json'): array
    {
        try {
            $response = Http::withHeaders($this->header ?? [])
                ->send($method, ($this->base_url ?? '') . $url, [
                    $type => $data
                ]);

            return [
                'success' => $response->successful(),
                'status' => $response->status(),
                'data' => $response->json(),
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'status' => 500,
                'message' => $e->getMessage(),
            ];
        }
    }
}

