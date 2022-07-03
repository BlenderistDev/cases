<?php

declare(strict_types=1);

namespace App\Services\Market\Request;

use Illuminate\Support\Facades\Http;

class RubItemsRequest
{

    private const API_V_2_PRICES_CLASS_INSTANCE_RUB_JSON = '/api/v2/prices/class_instance/RUB.json';

    public function makeRequest(): array
    {
        $host = getenv('MARKET_URL');
        $response = Http::timeout(60)->get($host . self::API_V_2_PRICES_CLASS_INSTANCE_RUB_JSON);
        return $response->json();
    }
}
