<?php

namespace App\Http\Controllers;

use App\Models\PaymentGift;
use App\Services\Loyalty\Discounts\PaymentBonus\Repositories\PaymentBonusRepository;

class LoyaltyController extends Controller
{
    public function index(
        PaymentBonusRepository $paymentBonusRepository,
    ): array {
        return [
              'paymentBonus' => $paymentBonusRepository->getPaymentBonusInfo(),
        ];
    }
}
