<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use App\Traits\HasProfilePhoto;
use App\Traits\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use App\Models\ConversationUser;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'profile_photo_path'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'email',
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the team invitations that the user has.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teamInvitations()
    {
        return $this->hasMany(TeamInvitation::class);
    }

    /**
     * Get the join requests that the user has.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function joinRequests()
    {
        return $this->hasMany(JoinRequest::class);
    }

    /**
     * Get the join requests that the user has.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function skills()
    {
        return $this->hasMany(Skill::class)->orderBy('skill', 'asc');
    }

    /**
     * Get conversations that the user has.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function conversations()
    {
        return $this->belongsToMany(Conversation::class)->withPivot('id')->using(ConversationUser::class);
    }

    /**
     * Get conversations that the user has.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ownedConversations()
    {
        return $this->hasMany(Conversation::class, 'owner_id');
    }

    /**
     * Get messages that the user has.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get all of the users that belong to the team.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function readMessages()
    {
        return $this->belongsToMany(Message::class, 'read_ids', 'read_id', 'message_id');
    }

    /**
     * Get all of the users that belong to the team.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function deliveredMessages()
    {
        return $this->belongsToMany(Message::class, 'delivered_ids', 'delivered_id', 'message_id');
    }

    /**
     * Get all of the users that belong to the team.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function receivedMessages()
    {
        return $this->belongsToMany(User::class, 'message_recipient', 'recipient_id', 'message_id');
    }
}
