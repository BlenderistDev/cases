<?php

declare(strict_types=1);

namespace App\Services\PayPalych\Services;

use App\Models\PaypalychPayment;
use App\Services\PayPalych\Requests\BillStatusRequest;
use Carbon\Carbon;

class MissingPaymentsService
{
    public function __construct(private BillStatusRequest $billStatusRequest, private PayPalychService $payPalychService)
    {
    }

    public function checkMissingPayments(): void
    {
        $payments = PaypalychPayment::query()
            ->select(['order_id'])
            ->whereNotIn('status', [PayPalychService::SUCCESS_STATUS, PayPalychService::FAIL_STATUS])
            ->whereDate('created_at', '<', Carbon::now()->subHour())
            ->get();

        foreach ($payments as $payment) {
            $response = $this->billStatusRequest->execute($payment->order_id);
            $this->payPalychService->processFromResponse($response);
        }
    }
}
