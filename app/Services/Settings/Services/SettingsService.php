<?php

declare(strict_types=1);

namespace App\Services\Settings\Services;

use App\Services\Options\OptionsService;

class SettingsService
{
    public const SITE_EMAIL_KEY = 'site_email';

    public function __construct(private OptionsService $optionsService)
    {
    }

    public function getEmail(): ?string
    {
        return $this->optionsService->get(self::SITE_EMAIL_KEY);
    }
}
