<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SwaggerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        if (!defined('L5_SWAGGER_CONST_HOST')) {
            define('L5_SWAGGER_CONST_HOST', env('APP_URL', 'http://localhost:8000'));
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
