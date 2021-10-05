<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Inertia\Testing\Assert;

class ShowManageTeamPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Manage team page can be shown.
     * 
     * @return void
     */
    public function test_manage_team_page_can_be_shown()
    {
        $user = User::factory()->withTeam()->create();
        $user->currentTeam;
        $this->actingAs($user = $user->fresh());

        $response = $this->get(route('teams.show', $user->currentTeam));

        $response->assertStatus(200)
                ->assertInertia(fn(Assert $page) => $page
                    ->component('Teams/Show')
                    ->hasAll([
                        'team',
                        'availableRoles',
                        'availablePermissions',
                        'defaultPermissions',
                        'permissions.canAddTeamMembers',
                        'permissions.canDeleteTeam',
                        'permissions.canRemoveTeamMembers',
                        'permissions.canUpdateTeam',
                        'permissions.canUpdateTeamMemberRole'
                    ])
                );
    }
}
