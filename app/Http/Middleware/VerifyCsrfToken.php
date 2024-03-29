<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/api/case/open',
        '/api/user/skin/sell',
        '/api/user/tradelink',
        '/api/user/skin/out',
        '/api/freecase/open',
        '/payment/skin/callback',
        '/api/payment/paypalych',
        '/payment/paypalych/callback',
    ];
}
