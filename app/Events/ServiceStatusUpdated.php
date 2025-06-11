<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ServiceStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $serviceType;
    public $status;
    public $restaurantId;

    /**
     * Create a new event instance.
     */
    public function __construct(string $serviceType, bool $status, int $restaurantId)
    {
        $this->serviceType = $serviceType;
        $this->status = $status;
        $this->restaurantId = $restaurantId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('restaurant.' . $this->restaurantId),
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
            'service_type' => $this->serviceType,
            'status' => $this->status,
            'restaurant_id' => $this->restaurantId
        ];
    }
}
