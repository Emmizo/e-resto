<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TwoFASetupMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function build()
    {
        return $this
            ->subject('Your 2FA Setup Verification Code')
            ->view('emails.2fa_setup')
            ->with(['otp' => $this->otp]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Two F A Setup Mail',
        );
    }

    /**
     * Get the message content definition.
     */
   /*  public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    } */

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
   /*  public function attachments(): array
    {
        return [];
    } */
}
