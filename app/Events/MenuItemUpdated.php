<?php

namespace App\Events;

use App\Models\MenuItem;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MenuItemUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $menuItem;
    public $action;  // 'created', 'updated', or 'deleted'

    /**
     * Create a new event instance.
     */
    public function __construct(MenuItem $menuItem, string $action)
    {
        $this->menuItem = $menuItem;
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
            new Channel('restaurant.' . $this->menuItem->restaurant_id),
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
            'menu_item' => [
                'id' => $this->menuItem->id,
                'name' => $this->menuItem->name,
                'price' => $this->menuItem->price,
                'category' => $this->menuItem->category,
                'status' => $this->menuItem->status,
            ],
            'action' => $this->action
        ];
    }
}
