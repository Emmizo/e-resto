<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use App\Events\WelcomeEmailEvent;
use App\Mail\WelcomeEmail;
use Mail;
class WelcomeEmailListener
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
    public function handle(WelcomeEmailEvent $event): void
    {
        $info = $event->users;



        $mail = $info['email'];
        $mails = array($mail);

        //password reset mail
        $subject = "Welcome to the Team ".$info['first_name']. ' ' .$info['last_name'];

        Mail::to($mails)->send(new WelcomeEmail($subject, $info));
    }
}
