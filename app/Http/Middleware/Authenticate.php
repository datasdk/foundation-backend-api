<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Illuminate\Support\Facades\Event;
use Modules\Crm\Events\Auth\UserBeforeAuthenticated;
use Modules\Crm\Events\Auth\UserAfterAuthenticated;

class Authenticate extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        // --- Før authentication ---
        Event::dispatch(new UserBeforeAuthenticated($request, $guards));

        // Sørg for authentication
        $this->authenticate($request, $guards);

        // --- Kør request videre og hent response ---
        $response = $next($request);

        // --- Efter call ---
        Event::dispatch(new UserAfterAuthenticated($request, $response));

        return $response;
    }

    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('admin.login');
        }
    }
}
