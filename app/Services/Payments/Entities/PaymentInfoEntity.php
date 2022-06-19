<?php

declare(strict_types=1);

namespace App\Services\Payments\Entities;

class PaymentInfoEntity
{
    public function __construct(private string $promocode, private int $price)
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
    public function getPrice(): int
    {
        return $this->price;
    }
}
