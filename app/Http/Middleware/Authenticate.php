<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Illuminate\Support\Facades\Event;

class Authenticate extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        // --- Før authentication ---
        if (class_exists(\Modules\Crm\Events\Auth\UserBeforeAuthenticated::class)) {
            Event::dispatch(new \Modules\Crm\Events\Auth\UserBeforeAuthenticated($request, $guards));
        }

        // Sørg for authentication
        $this->authenticate($request, $guards);

        // --- Kør request videre og hent response ---
        $response = $next($request);

        // --- Efter call ---
        if (class_exists(\Modules\Crm\Events\Auth\UserAfterAuthenticated::class)) {
            Event::dispatch(new \Modules\Crm\Events\Auth\UserAfterAuthenticated($request, $response));
        }

        return $response;
    }

    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('admin.login');
        }
    }
}
