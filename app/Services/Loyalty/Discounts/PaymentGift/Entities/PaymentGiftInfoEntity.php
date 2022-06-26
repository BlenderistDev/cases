<?php

declare(strict_types = 1);

namespace App\Services\Loyalty\Discounts\PaymentGift\Entities;

class PaymentGiftInfoEntity
{
    public function __construct(
        private int $realUserPercent,
        private int $hours
    )
    {

    }

    /**
     * @return int
     */
    public function getRealUserPercent(): int
    {
        return $this->realUserPercent;
    }

    /**
     * @return int
     */
    public function getHours(): int
    {
        return $this->hours;
    }
}
