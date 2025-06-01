<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Closure;

class SwaggerAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('api/documentation*')) {
            // Check if we have a token in the request
            if ($request->has('token')) {
                // Store the token in the session
                session(['swagger_token' => $request->token]);
            }

            // If we have a token in the session, add it to the request
            if ($token = session('swagger_token')) {
                $request->headers->set('Authorization', 'Bearer ' . $token);
            }
        }

        return $next($request);
    }
}
