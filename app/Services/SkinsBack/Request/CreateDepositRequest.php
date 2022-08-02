<?php

declare(strict_types=1);

namespace App\Services\SkinsBack\Request;

use App\Models\SkinsbackPayment;
use App\Models\User;
use App\Services\SkinsBack\Logger\Logger;
use App\Services\SkinsBack\Services\SignatureService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Http;

class CreateDepositRequest
{
    private const METHOD = 'create';
    private const CURRENCY = 'rub';
    private const URL = 'https://skinsback.com/api.php';

    public function __construct(private SignatureService $signatureService, private Logger $logger)
    {
    }

    public function execute()
    {
        $user = $this->getUser();
        $token = $this->getTradeToken($user);
        $payment = $this->createPayment($user->id);
        $orderId = $this->buildOrderId($payment->id, $user->id);

        $params = [
            'shopid' => getenv('SKINSBACK_CLIENT_ID'),
            'order_id' => $orderId,
            'steam_id' => $user->steamid,
            'method' => self::METHOD,
            'trade_token' => $token,
            'currency' => self::CURRENCY
        ];

        $params['sign'] = $this->signatureService->buildSignature($params);

        $response = Http::post(self::URL, $params);

        $this->logger->log($orderId, $params, self::METHOD, $response);

        if ($response->status() !== 200 || empty($response->json()['status']) || $response->json()['status'] !== 'success') {
            throw new \Exception('Ошибка при генерации ссылки на оплату');
        }

        $json = $response->json();

        $payment->setAttribute('transaction_id', $json['transaction_id']);
        if (!$payment->save()) {
            throw new \Exception('Ошибка при создании транзакции');
        }

        redirect($json['url'])->send();
    }

    /**
     * @param int $userId
     * @return SkinsbackPayment
     * @throws \Exception
     */
    private function createPayment(int $userId): SkinsbackPayment
    {
        $payment = new SkinsbackPayment();
        $payment->setAttribute('user_id', $userId);

        if (!$payment->save()) {
            throw new \Exception('Ошибка при создании платежа');
        }
        return $payment;
    }

    private function buildOrderId(int $id, int $userId): string
    {
        return sprintf("%d_%d_%d", $id, $userId, time());
    }

    /**
     * @param User $user
     * @return array
     * @throws \Exception
     */
    private function getTradeToken(User $user): string
    {
        parse_str(parse_url($user->steam_trade_link, PHP_URL_QUERY), $parsedQuery);

        if (empty($parsedQuery['token'])) {
            throw new \Exception('Некорректная ссылка для обмена');
        }

        return $parsedQuery['token'];
    }

    /**
     * @return mixed
     * @throws AuthorizationException
     */
    private function getUser(): User
    {
        $userId = auth()->id();
        if (!$userId) {
            throw new AuthorizationException();
        }

        $user = User::find($userId);
        if (!$user) {
            throw new \Exception('no user');
        }
        return $user;
    }
}
