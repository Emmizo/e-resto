<?php

namespace App\Events;

use App\Models\PromoBanner;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PromoBannerUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $promoBanner;
    public $action;  // 'created', 'updated', or 'deleted'

    /**
     * Create a new event instance.
     */
    public function __construct(PromoBanner $promoBanner, string $action)
    {
        $this->promoBanner = $promoBanner;
        $this->action = $action;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('restaurant.' . $this->promoBanner->restaurant_id),
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
            'promo_banner' => [
                'id' => $this->promoBanner->id,
                'title' => $this->promoBanner->title,
                'description' => $this->promoBanner->description,
                'image_url' => $this->promoBanner->image_url,
                'start_date' => $this->promoBanner->start_date,
                'end_date' => $this->promoBanner->end_date,
                'is_active' => $this->promoBanner->is_active,
                'restaurant_id' => $this->promoBanner->restaurant_id
            ],
            'action' => $this->action
        ];
    }
}
