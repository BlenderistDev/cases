<?php

declare(strict_types=1);

namespace App\Services\Loyalty\Discounts\PaymentBonus\Entities;

class PaymentBonusEntity
{
    public function __construct(private string $promocode, private int $value)
    {
    }

    /**
     * @return string
     */
    public function getPromocode(): string
    {
        return $this->promocode;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }
}
