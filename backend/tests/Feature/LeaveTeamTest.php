<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Conversation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeaveTeamTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Users can leave team.
     * 
     * @return void
     */
    public function test_users_can_leave_team()
    {
        $user = User::factory()->withTeam()->create();

        $user->currentTeam->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'administrator']
        );

        $conversation = Conversation::create(['team_id' => $user->currentTeam->id]);
        $conversation->users()->attach([
            $user->id,
            $otherUser->id
        ]);

        $this->actingAs($otherUser);

        $response = $this->delete('/teams/'.$user->currentTeam->id.'/members/'.$otherUser->id);

        $response->assertStatus(302);
        $this->assertCount(0, $user->currentTeam->fresh()->load('users')->users);
    }

    /**
     * Team owner cant leave own team
     * 
     * @return void
     */
    public function test_team_owner_cant_leave_own_team()
    {
        $this->actingAs($user = User::factory()->withTeam()->create());

        $response = $this->delete('/teams/'.$user->currentTeam->id.'/members/'.$user->id);

        $response->assertStatus(302)
                ->assertSessionHasErrorsIn('removeTeamMember', ['team']);
        $this->assertNotNull($user->currentTeam->fresh());
    }
}
