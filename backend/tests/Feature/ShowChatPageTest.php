<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Inertia\Testing\Assert;
use App\Models\User;
use App\Models\Conversation;

class ShowChatPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Chat page can be shown.
     * 
     * @return void
     */
    public function test_chat_page_can_be_shown()
    {
        $this->actingAs($user = User::factory()->create());
        $otherUser = User::factory()->create();
        $conversation = Conversation::create(['team_id' => null]);
        $conversation->users()->attach([
            $user->id,
            $otherUser->id
        ]);
        $chat = User::find($user->id)->conversations->load('lastMessage', 'users', 'team', 'messages');

        $response = $this->get('/chat');

        $response->assertStatus(200)
                ->assertInertia(fn(Assert $page) => $page
                    ->where('chatSource', $chat)
                    ->hasAll([
                        'currentConversation',
                        'fromProfile'
                    ])
                );
    }
}
