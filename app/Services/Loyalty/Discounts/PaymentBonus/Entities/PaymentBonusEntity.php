<?php

declare(strict_types=1);

namespace App\Services\Loyalty\Discounts\PaymentBonus\Entities;

use JetBrains\PhpStorm\Internal\TentativeType;

class PaymentBonusEntity implements \JsonSerializable
{
    public function __construct(private string $promocode, private int $value, private int $currentCount, private int $maxCount)
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

    /**
     * @return int
     */
    public function getCurrentCount(): int
    {
        return $this->currentCount;
    }

    /**
     * @return int
     */
    public function getMaxCount(): int
    {
        return $this->maxCount;
    }

    public function jsonSerialize(): array
    {
        return [
            'promocode' => $this->getPromocode(),
            'value' => $this->getValue(),
            'currentCount' => $this->getCurrentCount(),
            'maxCount' => $this->getMaxCount(),
        ];

    }
}
