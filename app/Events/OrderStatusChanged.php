<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order->loadMissing(['restaurant.owner', 'user']);
    }

    public function broadcastOn(): array
    {
        $channels = [new PrivateChannel('user.' . $this->order->user_id)];
        $ownerId  = $this->order->restaurant->owner_id ?? null;
        if ($ownerId) {
            $channels[] = new PrivateChannel('owner.' . $ownerId);
        }
        return $channels;
    }

    public function broadcastWith(): array
    {
        return [
            'order' => [
                'id'           => $this->order->id,
                'status'       => $this->order->status,
                'total_amount' => $this->order->total_amount,
                'restaurant'   => $this->order->restaurant ? [
                    'name' => $this->order->restaurant->name,
                ] : null,
                'updated_at'   => $this->order->updated_at?->toISOString(),
            ],
        ];
    }
}
