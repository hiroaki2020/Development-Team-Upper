<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeleteMessageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A message can be deleted
     * 
     * @return void
     */
    public function test_message_can_be_deleted()
    {
        $this->actingAs($user = User::factory()->create());
        $otherUser = User::factory()->create();
        $conversation = Conversation::create(['team_id' => null]);
        $conversation->users()->attach([
            $user->id,
            $otherUser->id
        ]);
        $message = Message::create([
            'sender_id' => $user->id,
            'message' => 'Hello world!',
            'is_image' => false,
            'image_path' => null,
            'conversation_id' => $conversation->id,
            'conversation_user_id' => $conversation->users->where('id', $user->id)->first()->pivot->id,
        ]);

        $response = $this->post(route('message.delete'), [
            'message_id' => $message->id
        ]);

        $response->assertStatus(200);
        $this->expectException(ModelNotFoundException::class);
        Message::findOrFail($message->id);
    }
}
