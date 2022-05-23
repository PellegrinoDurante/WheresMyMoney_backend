<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  Request  $request
     * @return string|null
     */
    protected function redirectTo($request): ?string
    {
        if (! $request->expectsJson()) {
            // If any route is not called from XMLHttpRequest (like the Google ones) and no user is logged, then the app
            // redirect to frontend login URL
            return env('FRONTEND_LOGIN_URL');
        }

        return null;
    }
}
