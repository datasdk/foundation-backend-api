<?php

namespace App\Http\Middleware;



class Sanctum extends \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful
{
    public function terminate($request, $response)
    {
        if ($user = $request->user()) {
        
        }
    }
}
