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

class ConversationUserDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $users;
    private $deleted_user_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ConversationUser $conversation_user)
    {
        $this->dontBroadcastToCurrentUser();
        $this->users = Conversation::find($conversation_user->conversation_id)->users;
        $this->deleted_user_id = $conversation_user->user_id;
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
        $channels = $channels->concat([new PrivateChannel('user-channel.'.$this->deleted_user_id)]);
        $channels = $channels->toArray();
        return $channels;
    }
}
