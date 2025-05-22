<?php
namespace App\Mail;

use App\Models\Restaurant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RestaurantApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $restaurant;
    public $approved;

    public function __construct(Restaurant $restaurant, $approved)
    {
        $this->restaurant = $restaurant;
        $this->approved = $approved;
    }

    public function build()
    {
        $subject = $this->approved ? 'Your Restaurant Has Been Approved!' : 'Your Restaurant Has Been Unapproved';
        return $this
            ->subject($subject)
            ->view('mails.restaurant-approved');
    }
}
