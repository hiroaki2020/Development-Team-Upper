<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTeamMemberRoleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Team owner can update team member roles.
     * 
     * @return void
     */
    public function test_team_owner_can_update_team_member_roles()
    {
        $this->actingAs($user = User::factory()->withTeam()->create());

        $user->currentTeam->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'administrator']
        );

        $response = $this->put('/teams/'.$user->currentTeam->id.'/members/'.$otherUser->id, [
            'role' => 'editor',
        ]);

        $this->assertTrue($otherUser->fresh()->hasTeamRole(
            $user->currentTeam->fresh(), 'editor'
        ));
    }

    /**
     * Team member in administrator role can update team member roles.
     * 
     * @return void
     */
    public function test_team_members_in_administrator_role_can_update_team_member_roles()
    {
        $user = User::factory()->withTeam()->create();

        $user->currentTeam->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'administrator']
        );

        $this->actingAs($otherUser);

        $response = $this->put('/teams/'.$user->currentTeam->id.'/members/'.$otherUser->id, [
            'role' => 'editor',
        ]);

        $this->assertTrue($otherUser->fresh()->hasTeamRole(
            $user->currentTeam->fresh(), 'editor'
        ));
    }

    /**
     * Team member in editor role can't update team member roles.
     * 
     * @return void
     */
    public function test_team_members_in_editor_role_cant_update_team_member_roles()
    {
        $user = User::factory()->withTeam()->create();

        $user->currentTeam->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'editor']
        );

        $this->actingAs($otherUser);

        $response = $this->put('/teams/'.$user->currentTeam->id.'/members/'.$otherUser->id, [
            'role' => 'viewer',
        ]);

        $this->assertFalse($otherUser->fresh()->hasTeamRole(
            $user->currentTeam->fresh(), 'viewer'
        ));
    }

    /**
     * Team member in viewer role can't update team member roles.
     * 
     * @return void
     */
    public function test_team_members_in_viewer_role_cant_update_team_member_roles()
    {
        $user = User::factory()->withTeam()->create();

        $user->currentTeam->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'viewer']
        );

        $this->actingAs($otherUser);

        $response = $this->put('/teams/'.$user->currentTeam->id.'/members/'.$otherUser->id, [
            'role' => 'editor',
        ]);

        $this->assertFalse($otherUser->fresh()->hasTeamRole(
            $user->currentTeam->fresh(), 'editor'
        ));
    }
}
