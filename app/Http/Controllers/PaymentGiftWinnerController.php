<?php

namespace App\Http\Controllers;

use App\Models\PaymentGiftWinner;
use App\Services\Loyalty\Discounts\PaymentGift\Repositories\PaymentGiftRepository;
use Carbon\Carbon;

class PaymentGiftWinnerController extends Controller
{
    public function index(PaymentGiftRepository $paymentGiftRepository)
    {
        $paymentGiftInfo = $paymentGiftRepository->getPaymentGiftInfo();
        $timeFrom = Carbon::now()->subHours($paymentGiftInfo->getHours());

        return PaymentGiftWinner::query()
            ->whereDate('created_at', '>=', $timeFrom)
            ->with(['user', 'dummy', 'paymentGift'])
            ->orderByDesc('created_at')
            ->get();
    }
}
