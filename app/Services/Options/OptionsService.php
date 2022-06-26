<?php

declare(strict_types=1);

namespace App\Services\Options;

use App\Models\Options;

class OptionsService
{
    public function get(string $name): ?string
    {
        return Options::byName($name)->get()->value('value');
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
    }
}
