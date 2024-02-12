<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Tymon\JWTAuth\Http\Parser\AuthHeaders;

class JwtAuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $authHeaders = new AuthHeaders();
        $authHeaders->setHeaderPrefix('token');
        app('tymon.jwt.parser')->addParser($authHeaders);
    }
}
