<?php

declare(strict_types=1);

namespace App\Services\Loyalty\Discounts\PaymentGift\Entities;

use JsonSerializable;

class WinnerEntity implements JsonSerializable
{
    public function __construct(private string $name, private string $img)
    {

    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getImg(): string
    {
        return $this->img;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->getName(),
            'img' => $this->getImg()
        ];
    }
}
