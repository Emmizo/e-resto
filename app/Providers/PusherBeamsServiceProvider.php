<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Pusher\PushNotifications\PushNotifications;

class PusherBeamsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(PushNotifications::class, function ($app) {
            return new PushNotifications([
                'instanceId' => config('pusher-beams.instance_id'),
                'secretKey' => config('pusher-beams.primary_key'),
            ]);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
