<?php

declare(strict_types=1);

namespace App\Services\Loyalty\Discounts\PaymentGift\Repositories;

use App\Services\Loyalty\Discounts\PaymentGift\Entities\PaymentGiftInfoEntity;
use App\Services\Options\OptionsService;

class PaymentGiftRepository
{
    private const REAL_USER_PERENT_OPTION = 'payment_gift_real_user_percent';
    const HOURS_OPTION = 'payment_gift_hours';

    public function __construct(private OptionsService $optionsService)
    {
    }

    public function getPaymentGiftInfo(): PaymentGiftInfoEntity
    {
        $realUserPercent = (int) $this->optionsService->get(self::REAL_USER_PERENT_OPTION);
        $timePattern = (int) $this->optionsService->get(self::HOURS_OPTION);

        return new PaymentGiftInfoEntity($realUserPercent, $timePattern);
    }

    public function setPaymentGiftInfo(PaymentGiftInfoEntity $paymentBonusEntity): void
    {
        $this->optionsService->set(self::REAL_USER_PERENT_OPTION, $paymentBonusEntity->getRealUserPercent());;
        $this->optionsService->set(self::HOURS_OPTION, $paymentBonusEntity->getHours());
    }
}
