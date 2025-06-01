<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([

            'admin' => \App\Http\Middleware\Admin::class,
            'nocache' => \App\Http\Middleware\NoCacheHeaders::class,
            'auth' => \App\Http\Middleware\Authenticate::class,
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'auth:api' => \App\Http\Middleware\EnsureTokenIsValid::class,
            'restaurant.permission' => \App\Http\Middleware\CheckRestaurantPermission::class,
            'notFound' => \App\Http\Middleware\NotFoundMiddleware::class,
            'ServerError' => \App\Http\Middleware\HandleServerError::class,
             ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], 401);
            }
            });
    })->create();
