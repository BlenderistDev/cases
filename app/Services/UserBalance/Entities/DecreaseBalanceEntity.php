<?php

declare(strict_types=1);

namespace App\Services\UserBalance\Entities;

class DecreaseBalanceEntity
{
    public function __construct(
        private int $amount,
        private int $userId,
        private string $source
    )
    {}

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }
}
