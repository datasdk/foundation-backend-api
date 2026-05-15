<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoAuthenticate
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        
        // Only log in the user if they're not already authenticated
        if ($user = $request->user(...$guards)) {

            if (!Auth::check()) {

                Auth::login($user);

            }

        }

        return $next($request);

    }

    /**
     * Optional: Add custom headers after response is sent
     */
    public function terminate($request, $response)
    {
        // No-op for now
    }

}
