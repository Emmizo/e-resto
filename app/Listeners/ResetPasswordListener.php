<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ResetPasswordEvent;
use Illuminate\Auth\Passwords\PasswordBroker;
use App\Mail\ResetPasswordEventMail;
use App\Models\User;
use Auth;

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
        $data = view()->shared('data');
        $mails = array($email);
        //password reset mail
        $subject = "Reset password";
        $token = app(PasswordBroker::class)->createToken(User::where('email', $email)->first());
        $info['name']=$user->first_name;
       /*  $info['app'] = optional(Auth::user())->role == "admin" || optional(Auth::user())->role == null
    ? config('app.name')
    : $data['users']->restaurant_name ; */
        $info['tokenUrl'] = url('/reset-password/'.$token.'?email='.$email);
        \Mail::to($mails)->send(new ResetPasswordEventMail($subject, $info));

        //
    }
}
