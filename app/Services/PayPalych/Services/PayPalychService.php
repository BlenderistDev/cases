<?php

declare(strict_types=1);

namespace App\Services\PayPalych\Services;

use App\Models\PaypalychPayment;
use App\Services\PayPalych\Logger\Logger;
use App\Services\UserBalance\Entities\PaymentInfoEntity;
use App\Services\UserBalance\Services\UserBalanceService;
use Illuminate\Http\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\DB;

class PayPalychService
{
    public const SUCCESS_STATUS = 'SUCCESS';
    public const FAIL_STATUS = 'FAIL';
    const SOURCE = 'paypalych';

    public function __construct(private UserBalanceService $userBalanceService, private Logger $logger)
    {
    }

    public function processFromRequest(Request $request): void
    {
        $orderId = $request->get('InvId');

        $this->logger->log($orderId, $request->toArray(), 'callback');

        $amount = $request->get('OutSum');
        $sign = $request->get('SignatureValue');

        $this->checkSignature($sign, $amount, $orderId);

        $billId = $request->get('TrsId');
        $status = $request->get('Status');

        $this->process($orderId, $billId, $status, (float) $amount);
    }

    public function processFromResponse(Response $response): void
    {
        $this->process(
            (string) $response->json('InvId', ''),
            (string) $response->json('TrsId', ''),
            (string) $response->json('Status', ''),
            (float) $response->json('OutSum', 0)
        );
    }

    private function checkSignature(string $sign, string $amount, string $orderId): void
    {
        $apiToken = getenv('PAYPALYCH_API_KEY');
        $requestSign = strtoupper(md5($amount . ":" . $orderId . ":" . $apiToken));
        if ($sign !== $requestSign) {
            throw new \Exception("invalid sign");
        }
    }

    /**
     * @param string $orderId
     * @param string $billId
     * @param string $status
     * @param float $amount
     * @return void
     * @throws \Exception
     */
    private function process(string $orderId, string $billId, string $status, float $amount): void
    {
        DB::beginTransaction();
        try {
            $payment = PaypalychPayment::query()
                ->where('bill_id', '=', $billId)
                ->where('order_id', '=', $orderId)
                ->first();

            if (in_array($payment->status, [self::SUCCESS_STATUS, self::FAIL_STATUS])) {
                DB::commit();
                return;
            }
            switch ($status) {
                case self::SUCCESS_STATUS:
                    $payment->setAttribute('status', self::SUCCESS_STATUS);
                    $payment->setAttribute('amount', $amount);
                    $this->userBalanceService->increaseBalance(new PaymentInfoEntity(
                        $payment->promocode,
                        (int) ($amount * 100),
                        self::SOURCE,
                        $payment->user_id
                    ));
                    break;
                case self::FAIL_STATUS:
                    $payment->setAttribute('status', self::FAIL_STATUS);
                    break;
                default:
                    $payment->setAttribute('status', $status);
                    break;
            }

            $payment->save();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();
    }
}
