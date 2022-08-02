<?php

declare(strict_types=1);

namespace App\Services\Loyalty\Discounts\PaymentBonus;

use App\Services\Loyalty\Discounts\PaymentBonus\Repositories\PaymentBonusRepository;
use App\Services\Loyalty\LoyaltyInterface;
use App\Services\UserBalance\Entities\PaymentInfoEntity;

class PaymentBonus implements LoyaltyInterface
{
    public function __construct(private PaymentBonusRepository $paymentBonusRepository)
    {
    }

    public function updatePrice(PaymentInfoEntity $paymentInfoEntity): int
    {
        $amount = $paymentInfoEntity->getAmount();

        $paymentBonusInfo = $this->paymentBonusRepository->getPaymentBonusInfo();

        if ($paymentInfoEntity->getPromocode() !== $paymentBonusInfo->getPromocode()) {
            return $amount;
        }

        if ($paymentBonusInfo->getCurrentCount() >= $paymentBonusInfo->getMaxCount()) {
            return $amount;
        }

        $this->paymentBonusRepository->increaseCurrentCount();

        $bonus = $amount / 100 * $paymentBonusInfo->getValue();

        return (int) ($amount + $bonus);
    }
}
