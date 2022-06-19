<?php
declare(strict_types=1);

namespace App\Services\Loyalty\Interfaces;

use App\Services\Payments\Entities\PaymentInfoEntity;

interface AfterPaymentBonusInterface
{
    public function execute(PaymentInfoEntity $paymentInfoEntity): void;
}
