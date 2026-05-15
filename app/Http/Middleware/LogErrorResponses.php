<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogErrorResponses
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Log kun hvis status er error (400+)
        if ($response->getStatusCode() >= 400) {
            $message = sprintf(
                'HTTP %d returned for %s %s: %s',
                $response->getStatusCode(),
                $request->method(),
                $request->fullUrl(),
                $response->getContent()
            );

            Log::warning($message);
        }

        return $response;
    }
}
