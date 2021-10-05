<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class SwitchTeamTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Current team can be switched.
     * 
     * @return void
     */
    public function test_current_team_can_be_switched()
    {
        $this->actingAs($user = User::factory()->withTeam()->create());
        $team = $user->ownedTeams()->create([
            'name' => 'test team'
        ]);

        $response = $this->put(route('current-team.update'), [
            'team_id' => $team->id
        ]);

        $response->assertStatus(303);
        $this->assertTrue($team->id === $user->fresh()->currentTeam->id);
    }
}
