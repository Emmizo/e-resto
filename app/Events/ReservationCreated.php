<?php

namespace App\Events;

use App\Models\Reservation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReservationCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reservation;

    /**
     * Create a new event instance.
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('restaurant.' . $this->reservation->restaurant_id),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'reservation' => [
                'id' => $this->reservation->id,
                'user' => [
                    'name' => $this->reservation->user->first_name . ' ' . $this->reservation->user->last_name,
                    'email' => $this->reservation->user->email,
                ],
                'date' => $this->reservation->reservation_date,
                'time' => $this->reservation->reservation_time,
                'party_size' => $this->reservation->party_size,
                'status' => $this->reservation->status,
                'special_requests' => $this->reservation->special_requests,
                'created_at' => $this->reservation->created_at
            ]
        ];
    }
}
