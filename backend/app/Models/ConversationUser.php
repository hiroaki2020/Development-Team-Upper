<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Events\ConversationUserCreated;
use App\Events\ConversationUserDeleted;

class ConversationUser extends Pivot
{
    use HasFactory;

    public $incrementing = true;

    protected $dispatchesEvents = [
        'created' => ConversationUserCreated::class,
        'deleted' => ConversationUserDeleted::class
    ];
}
