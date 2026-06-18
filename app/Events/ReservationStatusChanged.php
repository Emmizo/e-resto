<?php

namespace App\Events;

use App\Models\Reservation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReservationStatusChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation->loadMissing(['restaurant.owner', 'user']);
    }

    public function broadcastOn(): array
    {
        $channels = [new PrivateChannel('user.' . $this->reservation->user_id)];
        $ownerId  = $this->reservation->restaurant->owner_id ?? null;
        if ($ownerId) {
            $channels[] = new PrivateChannel('owner.' . $ownerId);
        }
        return $channels;
    }

    public function broadcastWith(): array
    {
        return [
            'reservation' => [
                'id'              => $this->reservation->id,
                'status'          => $this->reservation->status,
                'restaurant'      => $this->reservation->restaurant ? [
                    'name' => $this->reservation->restaurant->name,
                ] : null,
                'reservation_time' => $this->reservation->reservation_time?->toISOString(),
                'updated_at'      => $this->reservation->updated_at?->toISOString(),
            ],
        ];
    }
}
