<?php

declare(strict_types=1);

namespace App\Services\Loyalty;

use App\Services\Loyalty\Interfaces\AfterPaymentBonusInterface;
use App\Services\Payments\Entities\PaymentInfoEntity;

class Loyalty
{
    /**
     * @param LoyaltyInterface[] $purchaseBonus
     * @param AfterPaymentBonusInterface[] $afterPurchaseBonus
     */
    public function __construct(private array $purchaseBonus, private array $afterPurchaseBonus)
    {
    }

    public function updatePriceWithLoyalty(int $price): int
    {
        $newPrice = $price;
        foreach ($this->purchaseBonus as $item) {
            $newPrice = $item->updatePrice($newPrice);
        }

        return $newPrice;
    }

    public function afterPurchaseBonus(PaymentInfoEntity $paymentInfoEntity): void
    {
        foreach ($this->afterPurchaseBonus as $bonus) {
            $bonus->execute($paymentInfoEntity);
        }
    }
}
