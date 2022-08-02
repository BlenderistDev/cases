<?php

declare(strict_types=1);

namespace App\Services\SkinsBack\Request;

use App\Models\SkinsbackPayment;
use App\Services\SkinsBack\Logger\Logger;
use App\Services\SkinsBack\Services\SignatureService;
use App\Services\SkinsBack\Services\SkinsBackService;
use Illuminate\Support\Facades\Http;

class OrderStatusRequest
{
    private const METHOD = 'orderstatus';

    private const URL = 'https://skinsback.com/api.php';

    public function __construct(
        private SignatureService $signatureService,
        private Logger $logger,
        private SkinsBackService $skinsBackService
    )
    {
    }

    public function execute(string $transactionId, string $orderId)
    {
        /** @var SkinsbackPayment $payment */
        $payment = SkinsbackPayment::byTransactionId($transactionId)->first();
        if (empty($payment)) {
            throw new \Exception("no payment");
        }

        $params = [
            'shopid' => getenv('SKINSBACK_CLIENT_ID'),
            'transaction_id' => $transactionId,
            'method' => self::METHOD,
        ];

        $params['sign'] = $this->signatureService->buildSignature($params);

        $response = Http::post(self::URL, $params);

        $this->logger->log($orderId, $params, self::METHOD, $response);

        if ($response->status() !== 200) {
            throw new \Exception('Ошибка при проверки статуса оплаты');
        }

        $this->skinsBackService->processFromResponse($response);
        $json = $response->json();

        $payment->setAttribute('status', $json['status']);
        if (!$payment->save()) {
            throw new \Exception('Ошибка при обновлении транзакции');
        }
    }
}
