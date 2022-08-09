<?php

declare(strict_types=1);

namespace App\Services\PayPalych\Logger;

use App\Models\PaypalychPaymentLog;
use Illuminate\Http\Client\Response;

class Logger
{
    public function log(?string $orderId, array $request, string $url, ?Response $response = null)
    {
        $log = new PaypalychPaymentLog();
        $log->setAttribute('order_id', $orderId);
        $log->setAttribute('request', json_encode($request));
        $log->setAttribute('url', $url);

        if (!is_null($response)) {
            $log->setAttribute('response', $response->body());
            $log->setAttribute('status', $response->status());
        }

        if (!$log->save()) {
            throw new \Exception("Не удалось сохранить информацию о платеже");
        }
    }
}
