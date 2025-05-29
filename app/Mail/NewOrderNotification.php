<?php
namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        $restaurant = $this->order->restaurant;
        return $this
            ->subject('New Order Received')
            ->view('mails.new-order-notification')
            ->with([
                'order' => $this->order,
                'restaurant_image' => $restaurant && $restaurant->image ? url($restaurant->image) : null,
                'restaurant_name' => $restaurant ? $restaurant->name : null,
                'restaurant_address' => $restaurant ? $restaurant->address : null,
            ]);
    }
}
