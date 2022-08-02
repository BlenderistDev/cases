<?php

declare(strict_types=1);

namespace App\Services\SkinsBack\Services;

use App\Models\SkinsbackPayment;
use App\Services\UserBalance\Entities\PaymentInfoEntity;
use App\Services\UserBalance\Services\UserBalanceService;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SkinsBackService
{
    const SOURCE = 'SkinsBack';

    public function __construct(
        private SignatureService $signatureService,
        private UserBalanceService $userBalanceService
    )
    {
    }

    public function processFromRequest(Request $request)
    {
        $this->signatureService->checkSignature($request);

        $this->process(
            $request->get('transaction_id'),
            $request->get('status', ''),
            $request->get('amount', 0)
        );
    }

    public function processFromResponse(Response $response)
    {
        $this->process(
            $response->get('transaction_id'),
            $response->get('status', ''),
            $response->get('amount', 0)
        );
    }

    /**
     * @param string $transactionId
     * @param string $status
     * @param int $amount
     * @return void
     * @throws \Exception
     */
    private function process(string $transactionId, string $status, int $amount): void
    {
        DB::beginTransaction();
        try {
            $payment = SkinsbackPayment::query()->where('transaction_id', '=', $transactionId)->first();

            switch ($status) {
                case "success":
                    $payment->setAttribute('status', 'success');
                    $payment->setAttribute('amount', $amount);
                    $this->userBalanceService->increaseBalance(new PaymentInfoEntity(
                        '',
                        (int) ($amount * 100),
                        self::SOURCE,
                        $payment->user_id
                    ));
                    break;
                case "pending":
                    $payment->setAttribute('status', 'pending');
                    break;
                case "fail":
                    $payment->setAttribute('status', 'fail');
                    break;
                default:
                    throw new \Exception("unknown status");
            }

            $payment->save();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();
    }
}
