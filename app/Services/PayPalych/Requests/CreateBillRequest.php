<?php

declare(strict_types=1);

namespace App\Services\PayPalych\Requests;

use App\Models\PaypalychPayment;
use App\Models\SkinsbackPayment;
use App\Models\User;
use App\Services\PayPalych\Logger\Logger;
use App\Services\UserBalance\Entities\PaymentInfoEntity;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Http;

class CreateBillRequest
{
    private const URL = 'https://paypalych.com/api/v1/bill/create';
    private const MIN_AMOUNT = 15;

    public function __construct(private Logger $logger)
    {
    }

    public function execute(float $amount, string $promocode)
    {
        if ($amount < self::MIN_AMOUNT) {
            throw new \Exception(sprintf('Минимальная сумма для пополнения - %s', self::MIN_AMOUNT));
        }

        $user = $this->getUser();
        $payment = $this->createPayment($user->id, $amount, $promocode);
        $orderId = $this->buildOrderId($payment->id, $user->id);
        $this->setPaymentOrderId($orderId, $payment);

        $params = [
            'amount' => $amount,
            'order_id' => $orderId,
            'type' => 'normal',
            'shop_id' => getenv('PAYPALYCH_SHOP_ID'),
            'payer_pays_commission' => 0
        ];

        $response = Http::withToken(getenv('PAYPALYCH_API_KEY'))
            ->post(self::URL, $params);

        $this->logger->log($orderId, $params, self::URL, $response);

        if ($response->status() !== 200 || (string) $response->json(['success']) !== 'true') {
            throw new \Exception('Ошибка при генерации ссылки на оплату');
        }

        $json = $response->json();

        $payment->setAttribute('bill_id', $json['bill_id']);
        if (!$payment->save()) {
            throw new \Exception('Ошибка при создании транзакции');
        }

        return $json['link_page_url'];
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

    private function createPayment(int $userId, float $amount, string $promocode): PaypalychPayment
    {
        $payment = new PaypalychPayment();
        $payment->setAttribute('user_id', $userId);
        $payment->setAttribute('amount', $amount);
        $payment->setAttribute('promocode', $promocode);

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
     * @param string $orderId
     * @param PaypalychPayment $payment
     * @return void
     * @throws \Exception
     */
    private function setPaymentOrderId(string $orderId, PaypalychPayment $payment): void
    {
        $payment->order_id = $orderId;
        if (!$payment->save()) {
            throw new \Exception('Ошибка при создании платежа');
        }
    }
}
