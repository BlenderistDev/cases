<?php

declare(strict_types=1);

namespace App\Services\Loyalty\Discounts\PaymentBonus\Repositories;

use App\Services\Loyalty\Discounts\PaymentBonus\Entities\PaymentBonusEntity;
use App\Services\Options\OptionsService;

class PaymentBonusRepository
{
    private const PROMOCODE_VALUE_OPTION_NAME = 'payment_bonus_value';
    private const PROMOCODE_OPTION_NAME = 'payment_bonus_promocode';
    private const CURRENT_COUNT_OPTION_NAME = 'payment_bonus_current_count';
    private const MAX_COUNT_OPTION_NAME = 'payment_bonus_max_count';

    public function __construct(private OptionsService $optionsService)
    {
    }

    public function getPaymentBonusInfo(): PaymentBonusEntity
    {
        $value = (int) $this->optionsService->get(self::PROMOCODE_VALUE_OPTION_NAME);;
        $promocode = $this->optionsService->get(self::PROMOCODE_OPTION_NAME);
        $currentCount = (int) $this->optionsService->get(self::CURRENT_COUNT_OPTION_NAME);
        $maxCount = (int) $this->optionsService->get(self::MAX_COUNT_OPTION_NAME);

        return new PaymentBonusEntity($promocode, $value, $currentCount, $maxCount);
    }

    public function setPaymentBonusInfo(PaymentBonusEntity $paymentBonusEntity): void
    {
        $this->optionsService->set(self::PROMOCODE_VALUE_OPTION_NAME, $paymentBonusEntity->getValue());;
        $this->optionsService->set(self::PROMOCODE_OPTION_NAME, $paymentBonusEntity->getPromocode());
        $this->optionsService->set(self::CURRENT_COUNT_OPTION_NAME, $paymentBonusEntity->getCurrentCount());
        $this->optionsService->set(self::MAX_COUNT_OPTION_NAME, $paymentBonusEntity->getMaxCount());
    }

    public function increaseCurrentCount(): void
    {
        $currentCount = (int) $this->optionsService->get(self::CURRENT_COUNT_OPTION_NAME);
        $this->optionsService->get(self::CURRENT_COUNT_OPTION_NAME, $currentCount++);
    }
}
