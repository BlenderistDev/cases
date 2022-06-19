<?php

declare(strict_types=1);

namespace App\Services\Loyalty\Exceptions;

class NoSteamInfo extends \Exception
{
    public function __construct()
    {
        parent::__construct('Не удалось получить информацию о пользователе');
    }
}
