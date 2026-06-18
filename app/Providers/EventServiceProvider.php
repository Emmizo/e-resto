<?php

namespace App\Providers;

use App\Events\NewUserCreatedEvent;
use App\Events\ResetCreateEvent;
use App\Events\ResetPasswordEvent;
use App\Events\WelcomeEmailEvent;
use App\Listeners\ResetListener;
use App\Listeners\ResetPasswordListener;
use App\Listeners\UserRegisteredListener;
use App\Listeners\WelcomeEmailListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Disable auto-discovery to prevent the base EventServiceProvider
        // from scanning app/Listeners and double-registering all listeners.
        BaseEventServiceProvider::disableEventDiscovery();
    }

    public function boot(): void
    {
        Event::listen(Registered::class, SendEmailVerificationNotification::class);
        Event::listen(ResetPasswordEvent::class, [ResetPasswordListener::class, 'handle']);
        Event::listen(NewUserCreatedEvent::class, [UserRegisteredListener::class, 'handle']);
        Event::listen(ResetCreateEvent::class, [ResetListener::class, 'handle']);
        Event::listen(WelcomeEmailEvent::class, [WelcomeEmailListener::class, 'handle']);
    }
}
