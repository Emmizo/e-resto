<?php
namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewReservationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function build()
    {
        $restaurant = $this->reservation->restaurant;
        return $this
            ->subject('New Reservation Received')
            ->view('mails.new-reservation-notification')
            ->with([
                'reservation' => $this->reservation,
                'restaurant_image' => $restaurant && $restaurant->image ? url($restaurant->image) : null,
                'restaurant_name' => $restaurant ? $restaurant->name : null,
                'restaurant_address' => $restaurant ? $restaurant->address : null,
            ]);
    }
}
