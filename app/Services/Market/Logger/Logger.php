<?php

declare(strict_types=1);

namespace App\Services\Market\Logger;

use App\Models\MarketLog;
use Illuminate\Http\Client\Response;

class Logger
{
    public function log(int $userId, string $url, array $request, ?Response $response = null)
    {
        $log = new MarketLog();
        $log->setAttribute('user_id', $userId);
        $log->setAttribute('user_id', json_encode($request));
        $log->setAttribute('url', $url);
        $log->setAttribute('response', $response->body());
        $log->setAttribute('status', $response->status());

        if (!$log->save()) {
            throw new \Exception("Не удалось сохранить информацию о выводе скина");
        }
    }
}
