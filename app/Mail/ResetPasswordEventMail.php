<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordEventMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $info;

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
        $this->markdown('mails.reset_password_mail', $this->info)
        ->subject($this->subject);
      return $this;
    }
}
