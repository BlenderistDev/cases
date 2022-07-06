<?php

declare(strict_types=1);

namespace App\Services\Market\Request;

use App\Models\SkinOutLog;
use App\Models\User;
use App\Models\UserSkin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class BuyForRequest
{
    const API_V_2_BUY_FOR = '/api/v2/buy-for';

    public function makeRequest(int $skinId, $userId)
    {
        $host = getenv('MARKET_URL');
        $marketKey = getenv('MARKET_KEY');

        /** @var UserSkin $userSkin */
        $userSkin = UserSkin::query()
            ->where('user_id', '=', $userId)
            ->where('id', '=', $skinId)
            ->first();

        if (!$userSkin) {
            throw new \Exception("Скин не найден у пользователя");
        }

        $skin = $userSkin->skin()->first();
        if (!$skin) {
            throw new \Exception("Скин не найден");
        }

        list($partner, $token, $tradeLink) = $this->getReceiverData($userId);

        $customId = Str::random(40);

        $requestData = [
            'key' => $marketKey,
            'hash_name' => $skin->market_hash_name,
            'price' => (int) ($skin->price * 100),
            'partner' => $partner,
            'token' => $token,
            'custom_id' => $customId
        ];

        try {
            $response = Http::get($host . self::API_V_2_BUY_FOR, $requestData);


            if ($response->status() !== 200 || empty($response->json()['success']) || $response->json()['success'] !== true) {
                throw new \Exception('Ошибка при покупке скина');
            }

            $userSkin->delete();
        } catch (\Exception $e) {
            throw $e;
        } finally {
            $log = new SkinOutLog();
            $log->setAttribute('user_id', $userId);
            $log->setAttribute('skin_id', $skinId);
            $log->setAttribute('price', $skin->price);
            $log->setAttribute('custom_id', $customId);
            $log->setAttribute('trade_link', $tradeLink);
            $log->setAttribute('request', json_encode($requestData));
            $log->setAttribute('response', $response->body());
            $log->save();
        }

        return $response->json();
    }

    private function getReceiverData($userId): array
    {
        $user = User::find($userId);

        if (!$user) {
            throw new \Exception('Пользователь не найден');
        }

        $tradeLink = $user->steam_trade_link;

        parse_str(parse_url($tradeLink, PHP_URL_QUERY), $parsedQuery);

        if (empty($parsedQuery['partner'])) {
            throw new \Exception('Некорректная ссылка для обмена');
        }

        if (empty($parsedQuery['token'])) {
            throw new \Exception('Некорректная ссылка для обмена');
        }

        return [$parsedQuery['partner'], $parsedQuery['token'], $user->steam_trade_link];

    }
}
