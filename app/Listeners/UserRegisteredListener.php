<?php

namespace App\Listeners;

use App\Events\NewUserCreatedEvent;
use App\Mail\UserRegistered;
use App\Models\User;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Hash;
use Mail;
use Str;

class UserRegisteredListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewUserCreatedEvent $event): void
    {
        $user = User::where('email', $event->user->email)->first();
        if (!$user) return;

        $user->plain_password    = $event->plain_password;
        $user->manage_user_link  = route('manage-users');

        $token = app(PasswordBroker::class)->createToken($user);
        $user->tokenUrl = url('/reset-password/' . $token . '?email=' . urlencode($user->email));

        $subject = 'Verify your RestoFinder account';
        Mail::to($user->email)->send(new UserRegistered($subject, $user));
    }
}
