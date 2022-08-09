<?php

declare(strict_types=1);

namespace App\Services\PayPalych\Requests;

use App\Models\PaypalychPayment;
use App\Models\User;
use App\Services\PayPalych\Logger\Logger;
use App\Services\PayPalych\Services\PayPalychService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class BillStatusRequest
{
    private const URL = 'https://paypalych.com/api/v1/bill/status';

    public function __construct(private Logger $logger)
    {
    }

    public function execute(string $orderId): Response
    {
        $params = [
            'id' => $orderId,
        ];

        $response = Http::withToken(getenv('PAYPALYCH_API_KEY'))
            ->post(self::URL, $params);

        $this->logger->log($orderId, $params, self::URL, $response);

        if ($response->status() !== 200 || (string) $response->json(['success']) !== 'true') {
            throw new \Exception('Ошибка при получении статуса платежа');
        }

        return $response;
    }
}
