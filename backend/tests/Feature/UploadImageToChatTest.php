<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Conversation;
use Illuminate\Support\Facades\Event;
use App\Events\MessageSent;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadImageToChatTest extends TestCase
{
    use RefreshDatabase;

    /**
     * An image can be uploaded to chat.
     * 
     * @return void
     */
    public function test_image_can_be_uploaded_to_chat()
    {
        Event::fake();
        Storage::fake('s3');

        $this->actingAs($user = User::factory()->create());
        $otherUser = User::factory()->create();
        $conversation = Conversation::create(['team_id' => null]);
        $conversation->users()->attach([
            $user->id,
            $otherUser->id
        ]);

        $response = $this->withHeaders([
            'Content-Type' => 'multipart/form-data'
        ])->post('/upload-image', [
            'sender_id' => $user->id,
            'image' => $image = UploadedFile::fake()->image('image.jpg'),
            'is_image' => "true",
            'currentChatId' => $conversation->id
        ]);
        
        //The uploaded image is modified by Intervention image so hashName() does not work.
        //Storage::disk('s3')->assertExists('uploads-in-chat/'.$image->hashName());

        Event::assertDispatched(MessageSent::class);

        $response->assertStatus(200)
                ->assertJson([
                    'chat' => [
                        0 => [
                            'id' => 1
                        ]
                    ],
                    'message' => [
                        'id' => 1
                    ]
                ]);
    }
}
