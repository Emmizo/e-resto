<?php

namespace App\Events;

use App\Models\PromoBanner;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PromoBannerUpdated implements ShouldBroadcastNow
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
            // Public channel — mobile clients subscribe without authentication
            new Channel('promo-banners'),
            // Also fire on the restaurant channel for web dashboard live updates
            new Channel('restaurant.' . $this->promoBanner->restaurant_id),
        ];
    }

    public function broadcastWith(): array
    {
        $path = $this->promoBanner->image_path;
        if ($path) {
            if (str_starts_with($path, 'http')) {
                $imageUrl = $path;
            } elseif (str_starts_with($path, 'promo_banners/') && !str_starts_with($path, 'promo_banners/banner_')) {
                $imageUrl = asset('storage/' . $path);
            } else {
                $imageUrl = asset($path);
            }
        } else {
            $imageUrl = null;
        }

        return [
            'promo_banner' => [
                'id'            => $this->promoBanner->id,
                'title'         => $this->promoBanner->title,
                'description'   => $this->promoBanner->description,
                'image_url'     => $imageUrl,
                'image_path'    => $path,
                'start_date'    => $this->promoBanner->start_date,
                'end_date'      => $this->promoBanner->end_date,
                'is_active'     => $this->promoBanner->is_active,
                'restaurant_id' => $this->promoBanner->restaurant_id,
            ],
            'action' => $this->action,
        ];
    }
}
