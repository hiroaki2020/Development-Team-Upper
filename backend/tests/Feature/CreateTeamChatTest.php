<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Conversation;

class CreateTeamChatTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Team chat can be created when user accepts team invitation.
     * 
     * @return void
     */
    public function test_team_chat_can_be_created_when_user_accepts_team_invitation()
    {
        $this->actingAs($user = User::factory()->create());
        $otherUser = User::factory()->withTeam()->create();
        $invitation = $otherUser->currentTeam->teamInvitations()->create([
            'user_id' => $user->id,
            'role' => 'administrator',
            'message' => 'Hello world!'
        ]);

        $response = $this->post(route('team-invitations.accept'), [
            'invitation' => $invitation
        ]);

        $this->assertCount(2, $otherUser->fresh()->currentTeam->load('conversation.users')->conversation->users);
    }

    /**
     * Team chat can be created when team owner accepts join request.
     * 
     * @return void
     */
    public function test_team_chat_can_be_created_when_team_owner_accepts_join_request()
    {
        $this->actingAs($user = User::factory()->withTeam()->create());
        $otherUser = User::factory()->create();
        $join_request = $otherUser->joinRequests()->create([
            'team_id' => $user->currentTeam->id,
            'role' => 'editor',
            'message' => 'Hello world!',
        ]);

        $response = $this->post(route('handle-join-requests.accept'), [
            'join_request' => $join_request
        ]);

        $this->assertCount(2, $user->fresh()->currentTeam->load('conversation.users')->conversation->users);
    }

    /**
     * User is added to existing team chat when team owner accepts join request.
     * 
     * @return void
     */
    public function test_user_is_added_to_existing_team_chat_when_team_owner_accepts_join_request()
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
        $this->actingAs($otherUser = $otherUser->fresh());

        $join_request = $otherUser2->joinRequests()->create([
            'team_id' => $otherUser->currentTeam->id,
            'role' => 'editor',
            'message' => 'Hello world!',
        ]);

        $response = $this->post(route('handle-join-requests.accept'), [
            'join_request' => $join_request
        ]);

        $this->assertCount(3, $otherUser->fresh()->currentTeam->load('conversation.users')->conversation->users);
    }

    /**
     * User is added to existing team chat when team member in administrator role accepts join request.
     * 
     * @return void
     */
    public function test_user_is_added_to_existing_team_chat_when_team_member_in_administrator_role_accepts_join_request()
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

        $response = $this->post(route('handle-join-requests.accept'), [
            'join_request' => $join_request
        ]);

        $this->assertCount(3, $otherUser->fresh()->currentTeam->load('conversation.users')->conversation->users);
    }

    /**
     * User is not added to existing team chat when team member in editor role tries to accept join request.
     * 
     * @return void
     */
    public function test_user_is_not_added_to_existing_team_chat_when_team_member_in_editor_role_tries_to_accept_join_request()
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

        $response = $this->post(route('handle-join-requests.accept'), [
            'join_request' => $join_request
        ]);

        $this->assertCount(2, $otherUser->fresh()->currentTeam->load('conversation.users')->conversation->users);
        $this->assertCount(0, $otherUser2->fresh()->load('conversations')->conversations);
    }

    /**
     * User is not added to existing team chat when team member in viewer role tries to accept join request.
     * 
     * @return void
     */
    public function test_user_is_not_added_to_existing_team_chat_when_team_member_in_viewer_role_tries_to_accept_join_request()
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

        $response = $this->post(route('handle-join-requests.accept'), [
            'join_request' => $join_request
        ]);

        $this->assertCount(2, $otherUser->fresh()->currentTeam->load('conversation.users')->conversation->users);
        $this->assertCount(0, $otherUser2->fresh()->load('conversations')->conversations);
    }
}
