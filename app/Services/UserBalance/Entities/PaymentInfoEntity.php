<?php

declare(strict_types=1);

namespace App\Services\UserBalance\Entities;

class PaymentInfoEntity
{
    public function __construct(
        private string $promocode,
        private int $amount,
        private string $source,
        private int $userId
    )
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
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }
}
