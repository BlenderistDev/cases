<?php

namespace App\Http\Controllers;

use App\Models\PaymentGift;
use App\Services\Loyalty\Discounts\NameLoyalty\Repositories\NameLoyaltyRepository;
use App\Services\Loyalty\Discounts\PaymentBonus\Repositories\PaymentBonusRepository;

class LoyaltyController extends Controller
{
    public function index(
        PaymentBonusRepository $paymentBonusRepository,
        NameLoyaltyRepository $nameLoyaltyRepository
    ): array {
        return [
            'paymentBonus' => $paymentBonusRepository->getPaymentBonusInfo(),
            'nameLoyalty' => $nameLoyaltyRepository->getLoyaltyInfo()
        ];
    }
}
