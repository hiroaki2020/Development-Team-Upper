<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\ConversationUser;
use App\Models\Conversation;

class ConversationUserCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $users;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ConversationUser $conversation_user)
    {
        $this->dontBroadcastToCurrentUser();
        $this->users = Conversation::find($conversation_user->conversation_id)->users;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $channels = collect([]);
        $this->users->each(function($item, $key) use (&$channels) {
            $channels = $channels->concat([new PrivateChannel('user-channel.'.$item->id)]);
        });
        $channels = $channels->toArray();
        return $channels;
    }
}
