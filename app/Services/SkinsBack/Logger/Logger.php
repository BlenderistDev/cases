<?php

declare(strict_types=1);

namespace App\Services\SkinsBack\Logger;

use App\Models\SkinsbackPaymentLog;
use Illuminate\Http\Client\Response;

class Logger
{
    public function log(?string $orderId, array $request, string $method, ?Response $response = null)
    {
        $log = new SkinsbackPaymentLog();
        $log->setAttribute('order_id', $orderId);
        $log->setAttribute('request', json_encode($request));
        $log->setAttribute('method', $method);

        if (!is_null($response)) {
            $log->setAttribute('response', $response->body());
            $log->setAttribute('status', $response->status());
        }

        if (!$log->save()) {
            throw new \Exception("Не удалось сохранить информацию о платеже");
        }
    }
}
