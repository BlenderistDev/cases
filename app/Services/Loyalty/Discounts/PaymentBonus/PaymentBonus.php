<?php

declare(strict_types=1);

namespace App\Services\Loyalty\Discounts\PaymentBonus;

use App\Models\User;
use App\Services\Loyalty\Discounts\PaymentBonus\Repositories\PaymentBonusRepository;
use App\Services\Payments\Entities\PaymentInfoEntity;
use App\Services\Users\Repositories\UserRepository;

class PaymentBonus
{
    public function __construct(private PaymentBonusRepository $paymentBonusRepository, private UserRepository $userRepository)
    {
    }

    public function execute(PaymentInfoEntity $paymentInfoEntity): void
    {
        $paymentBonusInfo = $this->paymentBonusRepository->getPaymentBonusInfo();

        if ($paymentInfoEntity->getPromocode() !== $paymentBonusInfo->getPromocode()) {
            return;
        }

        $bonus = $paymentInfoEntity->getPrice() / 100 * $paymentBonusInfo->getValue();
        $this->userRepository->addToBalance($bonus);
    }
}
