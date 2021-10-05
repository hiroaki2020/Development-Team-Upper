<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;
use App\Traits\HasTeamProfilePhoto;

class Team extends JetstreamTeam
{
    use HasFactory;
    use HasTeamProfilePhoto;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'team_profile_photo_path', 'description', 'wanted'
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'team_profile_photo_url',
    ];

    /**
     * Get all of the pending join requests for the team.
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
    public function requirements()
    {
        return $this->hasMany(Requirement::class);
    }

    /**
     * Get messages that the conversation has.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function conversation()
    {
        return $this->hasOne(Conversation::class);
    }

    /**
     * Determine if the given user_id belongs to a user on the team.
     *
     * @param  int  $id
     * @return bool
     */
    public function hasUserWithId(int $id)
    {
        return $this->allUsers()->contains(function ($user) use ($id) {
            return $user->id === $id;
        });
    }
}
