<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Closure;
use Throwable;

class HandleServerError
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $response = $next($request);
        } catch (Throwable $e) {
            // Log the error for debugging
            Log::error($e);

            return response()->view('errors.500', [], 500);
        }

        // If the response is 500, show the custom page
        if ($response->status() === 500) {
            return response()->view('errors.500', [], 500);
        }

        return $response;
    }
}
