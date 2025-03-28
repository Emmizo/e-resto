<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class resetCreateEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
public $info;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($info)
    {
        $this->info=$info;
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [];
    }
    public function fire($event, $payload = [], $halt = false)
    {
        $responses = [];
        foreach ($this->getListeners($event) as $listener) {
            $response = call_user_func_array($listener, $payload);
            $responses[] = $response;
        }
        return $halt ? null : $responses;
    }
}
