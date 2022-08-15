<?php

declare(strict_types=1);

namespace App\Services\Options;

use App\Models\Options;

class OptionsService
{
    private static ?array $cache = null;

    public function get(string $name): ?string
    {
        if (is_null(self::$cache)) {
            self::$cache = Options::all()->pluck('value', 'name')->toArray();
        }

        return self::$cache[$name] ?? '';
    }

    public function set(string $name, $value)
    {
        $option = Options::byName($name)->first();

        if (empty($option)) {
            $option = new Options();
            $option->name = $name;
        }

        $option->value = $value;
        $option->save();

        self::$cache = null;
    }
}
