<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\JoinRequest;

class CancelJoinRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A join request can be canceled.
     * 
     * @return void
     */
    public function test_join_request_can_be_canceled()
    {
        $this->actingAs($user = User::factory()->create());
        $otherUser = User::factory()->withTeam()->create();
        $join_request = $user->joinRequests()->create([
            'team_id' => $otherUser->currentTeam->id,
            'role' => 'editor',
            'message' => 'Hello world!',
        ]);

        $response = $this->delete(route('join-requests.destroy', $join_request));

        $response->assertStatus(303);
        $this->assertFalse(JoinRequest::where('team_id', $otherUser->currentTeam->id)->exists());
    }
}
