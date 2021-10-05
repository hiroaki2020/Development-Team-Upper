<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Inertia\Testing\Assert;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Event;
use App\Events\MessageSent;

class SendMessageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A message can be sent.
     * 
     * @return void
     */
    public function test_message_can_be_sent()
    {
        Event::fake();

        $this->actingAs($user = User::factory()->create());
        $otherUser = User::factory()->create();
        $conversation = Conversation::create(['team_id' => null]);
        $conversation->users()->attach([
            $user->id,
            $otherUser->id
        ]);

        $response = $this->post('/send-message', [
            'messageForm' => [
                'sender_id' => $user->id,
                'message' => 'Hello world!',
                'is_image' => false,
                'currentChatIdToSubmit' => $conversation->id
            ]
        ]);
        
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
