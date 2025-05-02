<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewUserCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $plain_password;

    /**
     * Create a new event instance.
     */
    public function __construct($user, $plain_password)
    {
        $this->user = $user;
        $this->plain_password = $plain_password;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
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
