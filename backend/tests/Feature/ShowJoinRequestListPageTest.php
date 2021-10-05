<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Inertia\Testing\Assert;
use App\Models\User;

class ShowJoinRequestListPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Team owner can access join request list page.
     * 
     * @return void
     */
    public function test_team_owner_can_access_join_request_list_page()
    {
        $this->actingAs($user = User::factory()->withTeam()->create());
        $user->currentTeam;

        $response = $this->get(route('handle-join-requests.index'));

        $response->assertStatus(200)
                ->assertInertia(fn(Assert $page) => $page
                    ->component('TeamUp/Requests/Index')
                    ->hasAll([
                        'joinRequests',
                        'canAddTeamMembers'
                    ])
                );
    }

    /**
     * Team member in administrator role can access join request list page.
     * 
     * @return void
     */
    public function test_team_member_in_administrator_role_can_access_join_request_list_page()
    {
        $user = User::factory()->withTeam()->create();
        $otherUser = User::factory()->create();
        $user->currentTeam->users()->attach($otherUser->id, ['role' => 'administrator']);
        $this->actingAs($otherUser = $otherUser->fresh());

        $response = $this->get(route('handle-join-requests.index'));

        $response->assertStatus(200)
                ->assertInertia(fn(Assert $page) => $page
                    ->component('TeamUp/Requests/Index')
                    ->hasAll([
                        'joinRequests',
                        'canAddTeamMembers'
                    ])
                );
    }

    /**
     * Team member in editor role can't access join request list page.
     * 
     * @return void
     */
    public function test_team_member_in_editor_role_cant_access_join_request_list_page()
    {
        $user = User::factory()->withTeam()->create();
        $otherUser = User::factory()->create();
        $user->currentTeam->users()->attach($otherUser->id, ['role' => 'editor']);
        $this->actingAs($otherUser = $otherUser->fresh());

        $response = $this->get(route('handle-join-requests.index'));
        
        $response->assertStatus(403);
    }

    /**
     * Team member in viewer role can't access join request list page.
     * 
     * @return void
     */
    public function test_team_member_in_viewer_role_cant_access_join_request_list_page()
    {
        $user = User::factory()->withTeam()->create();
        $otherUser = User::factory()->create();
        $user->currentTeam->users()->attach($otherUser->id, ['role' => 'viewer']);
        $this->actingAs($otherUser = $otherUser->fresh());

        $response = $this->get(route('handle-join-requests.index'));

        $response->assertStatus(403);
    }
}
