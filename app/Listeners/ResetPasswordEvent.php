<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ResetPasswordEvent;
use Illuminate\Auth\Passwords\PasswordBroker;
use App\Mail\ResetPasswordEventMail;
use App\Models\User;

class ResetPasswordListener
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
    public function handle(ResetPasswordEvent $event): void
    {
        $email=$event->email;
        $user=User::where('email',$email)->first();

        $mails = array($email);
        //password reset mail
        $subject = "Reset password";
        $token = app(PasswordBroker::class)->createToken(User::where('email', $email)->first());
        $info['name']=$user->name;
        $info['tokenUrl'] = url('/reset-password/'.$token.'?email='.$email);
        \Mail::to($mails)->send(new ResetPasswordEventMail($subject, $info));

        //
    }
}
