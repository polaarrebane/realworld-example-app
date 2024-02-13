<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;

trait RenderAuthenticationExceptionAsJson
{
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json('', 401);
    }
}
