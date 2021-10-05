<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\JoinRequest;

class MakeJoinRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Join requests can be made.
     * 
     * @return void
     */
    public function test_join_requests_can_be_made()
    {
        $this->actingAs($user = User::factory()->create());
        $otherUser = User::factory()->withTeam()->create();

        $response = $this->post(route('join-requests.store'), [
            'team_id' => $otherUser->currentTeam->id,
            'role' => 'editor',
            'message' => 'Hello world!'
        ]);

        $response->assertStatus(303);
        $this->assertTrue(JoinRequest::where('team_id', $otherUser->currentTeam->id)->exists());
    }
}
