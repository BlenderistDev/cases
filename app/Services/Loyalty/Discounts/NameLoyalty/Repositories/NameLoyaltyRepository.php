<?php

declare(strict_types=1);

namespace App\Services\Loyalty\Discounts\NameLoyalty\Repositories;

use App\Services\Loyalty\Discounts\NameLoyalty\Entities\NameLoyaltyInfo;
use App\Services\Options\OptionsService;

class NameLoyaltyRepository
{
    private const PROMOCODE_VALUE_OPTION_NAME = 'loyalty_name_value';
    private const PROMOCODE_PATTERN_OPTION_NAME = 'loyalty_name_pattern';

    public function __construct(private OptionsService $optionsService)
    {

    }

    public function getLoyaltyInfo(): NameLoyaltyInfo
    {
        $value = $this->optionsService->get(self::PROMOCODE_VALUE_OPTION_NAME);
        $pattern = $this->optionsService->get(self::PROMOCODE_PATTERN_OPTION_NAME);

        return new NameLoyaltyInfo((int) $value, $pattern);
    }

    public function setLoyaltyInfo(NameLoyaltyInfo $nameLoyaltyInfo): void
    {
        $this->optionsService->set(self::PROMOCODE_VALUE_OPTION_NAME, $nameLoyaltyInfo->getValue());
        $this->optionsService->set(self::PROMOCODE_PATTERN_OPTION_NAME, $nameLoyaltyInfo->getPattern());
    }
}
