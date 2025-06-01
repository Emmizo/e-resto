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
        $info = $event->user;
        $info['plain_password'] = $event->plain_password;

        $info['manage_user_link'] = route('manage-users');
        $mail = $info['email'];
        $mails = array($mail);

        // password reset mail
        $subject = 'User Created Successfully';
        $token = app(PasswordBroker::class)->createToken(User::where('email', $mail)->first());
        $info['tokenUrl'] = url('/reset-password/' . $token . '?email=' . $mail);
        Mail::to($mails)->send(new UserRegistered($subject, $info));
    }
}
