<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $info;
    public $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject,$info)
    {
        //
        $this->info=$info;
        $this->subject=$subject;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $this->markdown('mails.welcome', ['user'=>$this->info])
        ->subject($this->subject);
      return $this;
    }
}
