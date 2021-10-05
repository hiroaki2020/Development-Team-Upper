<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Events\MessageSent;
use App\Events\MessageDeleted;

class Message extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sender_id', 'conversation_id', 'is_image', 'message', 'image_path', 'conversation_user_id',
    ];

    /**
     * Update 'updated_at' col of the related records every time a message is sent.
     */
    protected $touches = ['conversation'];

    protected $dispatchesEvents = [
        'created' => MessageSent::class,
        'deleted' => MessageDeleted::class,
    ];

    /**
     * Get conversations that the user has.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get conversations that the user has.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Get all of the users that belong to the team.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function userReadMessages()
    {
        return $this->belongsToMany(User::class, 'read_ids', 'message_id', 'read_id');
    }

    /**
     * Get all of the users that belong to the team.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function userDeliveredMessages()
    {
        return $this->belongsToMany(User::class, 'delivered_ids', 'message_id', 'delivered_id');
    }

    /**
     * Get all of the users that belong to the team.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function recipients()
    {
        return $this->belongsToMany(User::class, 'user_recipient', 'message_id', 'recipient_id');
    }
}
