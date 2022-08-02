<?php
declare(strict_types=1);

namespace App\Services\Loyalty;

use App\Services\UserBalance\Entities\PaymentInfoEntity;

interface LoyaltyInterface
{
    public function updatePrice(PaymentInfoEntity $paymentInfoEntity): int;
}
