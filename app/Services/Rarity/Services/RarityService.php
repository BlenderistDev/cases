<?php

declare(strict_types=1);

namespace App\Services\Rarity\Services;

use App\Models\Rarity;

class RarityService
{
    private static ?array $mapping = null;

    const DEFAULT_RARITY = '';

    public const OPTIONS = [
        'silver' => 'silver',
        'purple' => 'purple',
        'maline' => 'maline',
        'blue' => 'blue',
        'red' => 'red',
        'orange' => 'orange',
        'bluelight' => 'bluelight',
    ];

    public function getColor(string $rarity): string
    {
        $mapping = $this->getMapping();
        return $mapping[$rarity] ?? self::DEFAULT_RARITY;
    }

    public function getMapping(): array
    {
        if (is_null(self::$mapping)) {
            self::$mapping = Rarity::all()->pluck('color', 'name')->toArray();
        }
        return self::$mapping;
    }
}
