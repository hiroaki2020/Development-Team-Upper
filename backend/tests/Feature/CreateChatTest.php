<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Conversation;
use Inertia\Testing\Assert;

class CreateChatTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Chat can be created and chat page can be shown.
     * 
     * @return void
     */
    public function test_chat_can_be_created_and_chat_page_can_be_shown()
    {
        $this->actingAs($user = User::factory()->create());
        $otherUser = User::factory()->create();

        $response = $this->post('/chat', ['profile_id' => $otherUser->id]);
        $this->assertTrue($user->fresh()->load('conversations')->conversations->first()->users->contains($user) &&
            $user->fresh()->load('conversations')->conversations->first()->users->contains($otherUser));

        $response->assertStatus(200)
                ->assertInertia(fn(Assert $page) => $page
                    ->component('Chat/Chat')
                    ->hasAll([
                        'currentConversation',
                        'chatSource',
                        'fromProfile'
                    ])
                );
    }

    /**
     * Chat is not created when it exists and chat page is shown.
     * 
     * @return void
     */
    public function test_chat_is_not_created_when_it_exists_and_chat_page_is_shown()
    {
        $this->actingAs($user = User::factory()->create());
        $otherUser = User::factory()->create();
        $conversation = Conversation::create(['team_id' => null]);
        $conversation->users()->attach([
            $user->id,
            $otherUser->id
        ]);

        $response = $this->post('/chat', ['profile_id' => $otherUser->id]);
        $this->assertCount(1, $user->fresh()->load('conversations')->conversations);
        $this->assertTrue($user->fresh()->load('conversations')->conversations->first()->users->contains($user) &&
            $user->fresh()->load('conversations')->conversations->first()->users->contains($otherUser));

        $response->assertStatus(200)
                ->assertInertia(fn(Assert $page) => $page
                    ->component('Chat/Chat')
                    ->hasAll([
                        'currentConversation',
                        'chatSource',
                        'fromProfile'
                    ])
                );
    }
}
