<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Message;
use App\Models\User;
use App\Models\Conversation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Event;

class ShowImageInChatTest extends TestCase
{
    use RefreshDatabase;

    /**
     * An image can be shown in chat.
     * 
     * @return void
     */
    public function test_image_can_be_shown_in_chat()
    {
        Event::fake();

        $this->actingAs($user = User::factory()->create());
        $otherUser = User::factory()->create();
        $conversation = Conversation::create(['team_id' => null]);
        $conversation->users()->attach([
            $user->id,
            $otherUser->id
        ]);
        Storage::fake('private')->put(
            'uploads-in-chat',
            $image = UploadedFile::fake()->image('image.jpg')
        );
        $message = Message::create([
            'sender_id' => $user->id,
            'message' => '[image]',
            'is_image' => true,
            'image_path' => 'uploads-in-chat/'.$image->hashName(),
            'conversation_id' => $conversation->id,
            'conversation_user_id' => $conversation->users->where('id', $user->id)->first()->pivot->id,
        ]);

        $response = $this->post('show-image', [
            'image_path' => $message->image_path,
            'conversation_id' => $message->conversation_id
        ]);

        $response->assertStatus(200);
    }
}
