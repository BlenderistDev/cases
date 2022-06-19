<?php

declare(strict_types=1);

namespace App\Services\Loyalty\Discounts\PaymentBonus\Repositories;

use App\Services\Loyalty\Discounts\PaymentBonus\Entities\PaymentBonusEntity;
use App\Services\Options\OptionsService;

class PaymentBonusRepository
{
    private const PROMOCODE_VALUE_OPTION_NAME = 'payment_bonus_value';
    private const PROMOCODE_OPTION_NAME = 'payment_bonus_promocode';

    public function __construct(private OptionsService $optionsService)
    {
    }

    public function getPaymentBonusInfo(): PaymentBonusEntity
    {
        $value = (int) $this->optionsService->get(self::PROMOCODE_VALUE_OPTION_NAME);;
        $promocode = $this->optionsService->get(self::PROMOCODE_OPTION_NAME);

        return new PaymentBonusEntity($promocode, $value);
    }
}
