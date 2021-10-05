<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\JoinRequest;
use App\Models\Conversation;

class DeclineJoinRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Team owner can decline join requests.
     * 
     * @return void
     */
    public function test_team_owner_can_decline_join_requests()
    {
        $this->actingAs($user = User::factory()->withTeam()->create());
        $otherUser = User::factory()->create();
        $join_request = $otherUser->joinRequests()->create([
            'team_id' => $user->currentTeam->id,
            'role' => 'editor',
            'message' => 'Hello world!',
        ]);

        $response = $this->delete(route('handle-join-requests.decline', [
            'join_request' => $join_request
        ]));

        $response->assertStatus(303);
        $this->assertFalse(JoinRequest::where('team_id', $user->currentTeam->id)->exists());
    }

    /**
     * Team member in administrator role can decline join requests.
     * 
     * @return void
     */
    public function test_team_member_in_administrator_role_can_decline_join_requests()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->withTeam()->create();
        $otherUser2 = User::factory()->create();
        $otherUser->currentTeam->users()->attach($user->id, ['role' => 'administrator']);
        $conversation = Conversation::create([
            'team_id' => $otherUser->currentTeam->id
        ]);
        $conversation->users()->attach([
            $user->id,
            $otherUser->id
        ]);
        $this->actingAs($user = $user->fresh());

        $join_request = $otherUser2->joinRequests()->create([
            'team_id' => $otherUser->currentTeam->id,
            'role' => 'editor',
            'message' => 'Hello world!',
        ]);

        $response = $this->delete(route('handle-join-requests.decline', [
            'join_request' => $join_request
        ]));

        $response->assertStatus(303);
        $this->assertFalse(JoinRequest::where('team_id', $otherUser->currentTeam->id)->exists());
    }

    /**
     * Team member in editor role can't decline join requests.
     * 
     * @return void
     */
    public function test_team_member_in_editor_role_cant_decline_join_requests()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->withTeam()->create();
        $otherUser2 = User::factory()->create();
        $otherUser->currentTeam->users()->attach($user->id, ['role' => 'editor']);
        $conversation = Conversation::create([
            'team_id' => $otherUser->currentTeam->id
        ]);
        $conversation->users()->attach([
            $user->id,
            $otherUser->id
        ]);
        $this->actingAs($user = $user->fresh());

        $join_request = $otherUser2->joinRequests()->create([
            'team_id' => $otherUser->currentTeam->id,
            'role' => 'editor',
            'message' => 'Hello world!',
        ]);

        $response = $this->delete(route('handle-join-requests.decline', [
            'join_request' => $join_request
        ]));

        $response->assertStatus(403);
        $this->assertTrue(JoinRequest::where('team_id', $otherUser->currentTeam->id)->exists());
    }

    /**
     * Team member in viewer role can't decline join requests.
     * 
     * @return void
     */
    public function test_team_member_in_viewer_role_cant_decline_join_requests()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->withTeam()->create();
        $otherUser2 = User::factory()->create();
        $otherUser->currentTeam->users()->attach($user->id, ['role' => 'viewer']);
        $conversation = Conversation::create([
            'team_id' => $otherUser->currentTeam->id
        ]);
        $conversation->users()->attach([
            $user->id,
            $otherUser->id
        ]);
        $this->actingAs($user = $user->fresh());

        $join_request = $otherUser2->joinRequests()->create([
            'team_id' => $otherUser->currentTeam->id,
            'role' => 'editor',
            'message' => 'Hello world!',
        ]);

        $response = $this->delete(route('handle-join-requests.decline', [
            'join_request' => $join_request
        ]));

        $response->assertStatus(403);
        $this->assertTrue(JoinRequest::where('team_id', $otherUser->currentTeam->id)->exists());
    }
}
