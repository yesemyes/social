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
        'api/register',
        'api/get_user',
        'facebook/login',
        'api/facebook/login',
        'api/google/login',
        'google/login',
        'api/users',
        'api/account/delete',
	     'api/twitter',
	     'api/facebook',
	     'api/linkedin',
    ];
}
