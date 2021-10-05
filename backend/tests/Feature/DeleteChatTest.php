<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Conversation;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeleteChatTest extends TestCase
{
    use RefreshDatabase;

    /**
     * An individual chat can be deleted.
     * 
     * @return void
     */
    public function test_individual_chat_can_be_deleted()
    {
        $this->actingAs($user = User::factory()->create());
        $otherUser = User::factory()->create();
        $conversation = Conversation::create(['team_id' => null]);
        $conversation->users()->attach([
            $user->id,
            $otherUser->id
        ]);

        $response = $this->post(route('chat.delete'), [
            'conversation_id' => $conversation->id
        ]);

        $response->assertStatus(200);
        $this->expectException(ModelNotFoundException::class);
        Conversation::findOrFail($conversation->id);
    }
}
