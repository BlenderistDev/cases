<?php

declare(strict_types=1);

namespace App\Services\Market\Request;

use Illuminate\Support\Facades\Http;

class RubPriceRequest
{

    private const API_V_2_PRICES_RUB_JSON = '/api/v2/prices/RUB.json';

    public function makeRequest(): array
    {
        $host = getenv('MARKET_URL');
        $response = Http::get($host . self::API_V_2_PRICES_RUB_JSON);
        return $response->json();
    }
}
