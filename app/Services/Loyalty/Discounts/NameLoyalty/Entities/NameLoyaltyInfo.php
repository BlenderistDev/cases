<?php

declare(strict_types=1);

namespace App\Services\Loyalty\Discounts\NameLoyalty\Entities;


class NameLoyaltyInfo implements \JsonSerializable
{
    public function __construct(private int $value, private string $pattern)
    {
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getPattern(): string
    {
        return $this->pattern;
    }

    public function jsonSerialize(): array
    {
        return [
            'pattern' => $this->getPattern(),
            'value' => $this->getValue()
        ];
    }
}
