<?php
declare(strict_types=1);

namespace App\Services\Loyalty;

interface LoyaltyInterface
{
    public function updatePrice(int $price): int;
}
