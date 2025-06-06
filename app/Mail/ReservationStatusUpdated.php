<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;

    /**
     * Create a new message instance.
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function build()
    {
        $restaurant = $this->reservation->restaurant;
        return $this
            ->subject('Your Reservation Status Has Been Updated')
            ->view('mails.reservation-status-updated')
            ->with([
                'reservation' => $this->reservation,
                'restaurant_image' => $restaurant && $restaurant->image ? url($restaurant->image) : null,
                'restaurant_name' => $restaurant ? $restaurant->name : null,
                'restaurant_address' => $restaurant ? $restaurant->address : null,
            ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reservation Status Updated',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mails.reservation-status-updated',
            with: [
                'reservationId' => $this->reservation->id,
                'status' => $this->reservation->status,
                'customerName' => $this->reservation->user->first_name,  // Assuming User model has first_name
                'reservationTime' => $this->reservation->reservation_time,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
