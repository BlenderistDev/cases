<?php

declare(strict_types=1);

namespace App\Services\Loyalty\Exceptions;

class NoAuthException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Необходима авторизация');
    }
}
