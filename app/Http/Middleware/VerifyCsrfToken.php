<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'api/login',
        'api/get_user_details',
        'api/get_user',
        'api/twitter/login',
        'api/facebook/login',
        'facebook/login',
        'api/users',
    ];
}
