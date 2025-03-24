<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use App\Events\ResetCreateEvent;

class ResetListener
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
    public function handle(ResetCreateEvent $event): void
    {
       //
       $email=$event->email;
       $user=User::where('email',$email)->first();

       $mails = array($email);
       //password reset mail
       $subject = "Reset password";

       $info['first_name']=$user->first_name;
       $info['email']=$user->email;
       $info['password']=$user->password;
       \Mail::to($mails)->send(new ResetMail($subject, $info));
    }
}
