<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Conversation;

class DeleteTeamChatTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Team chat is deleted when team owner deletes team.
     * 
     * @return void
     */
    public function test_team_chat_is_deleted_when_team_owner_deletes_team()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->withTeam()->create();
        $otherUser->currentTeam->users()->attach($user->id, ['role' => 'administrator']);
        $conversation = Conversation::create([
            'team_id' => $otherUser->currentTeam->id
        ]);
        $conversation->users()->attach([
            $user->id,
            $otherUser->id
        ]);
        $this->actingAs($otherUser = $otherUser->fresh());
        $team = $otherUser->currentTeam;

        $response = $this->delete('/teams/'.$team->id);

        $this->assertNull($conversation->fresh());
    }

    /**
     * Team chat is deleted when team owner removes team member and becomes only one in team.
     * 
     * @return void
     */
    public function test_team_chat_is_deleted_when_team_owner_removes_team_member_and_becomes_only_one_in_team()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->withTeam()->create();
        $otherUser->currentTeam->users()->attach($user->id, ['role' => 'administrator']);
        $conversation = Conversation::create([
            'team_id' => $otherUser->currentTeam->id
        ]);
        $conversation->users()->attach([
            $user->id,
            $otherUser->id
        ]);
        $this->actingAs($otherUser = $otherUser->fresh());
        $team = $otherUser->currentTeam;

        $response = $this->delete('/teams/'.$team->id.'/members/'.$user->id);

        $this->assertNull($conversation->fresh());
    }

    /**
     * Team chat is deleted when team member leaves team and team owner becomes only one in team.
     * 
     * @return void
     */
    public function test_team_chat_is_deleted_when_team_member_leaves_team_and_team_owner_becomes_only_one_in_team()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->withTeam()->create();
        $otherUser->currentTeam->users()->attach($user->id, ['role' => 'administrator']);
        $conversation = Conversation::create([
            'team_id' => $otherUser->currentTeam->id
        ]);
        $conversation->users()->attach([
            $user->id,
            $otherUser->id
        ]);
        $this->actingAs($user = $user->fresh());
        $team = $otherUser->currentTeam;

        $response = $this->delete('/teams/'.$team->id.'/members/'.$user->id);

        $this->assertNull($conversation->fresh());
    }
}
