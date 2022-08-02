<?php

declare(strict_types=1);

namespace App\Services\Loyalty;

use App\Services\UserBalance\Entities\PaymentInfoEntity;

class Loyalty
{
    /**
     * @param LoyaltyInterface[] $purchaseBonus
     */
    public function __construct(private array $purchaseBonus)
    {
    }

    public function updatePriceWithLoyalty(PaymentInfoEntity $paymentInfoEntity): int
    {
        $newPrice = $paymentInfoEntity->getAmount();
        foreach ($this->purchaseBonus as $item) {
            $newPrice = $item->updatePrice($paymentInfoEntity);
            $paymentInfoEntity->setAmount($newPrice);
        }

        return $newPrice;
    }
}
