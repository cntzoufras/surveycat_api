<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PasswordResetRequested {

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $token;


    /**
     * Create a new event instance.
     *
     * @param $token
     * @param $user
     */
    public function __construct($user, $token) {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
//    public function broadcastOn(): array {
//        return [
//            new PrivateChannel('channel-name'),
//        ];
//    }
}