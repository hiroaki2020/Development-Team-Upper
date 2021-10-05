<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class ReceiveMessageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Receive new messages.
     * 
     * @return void
     */
    public function test_receive_new_messages()
    {
        $this->actingAs($user = User::factory()->create());
        $response = $this->get('/send-message');

        $response->assertStatus(200);
    }

    /**
     * Get new chat data.
     * 
     * @return void
     */
    public function test_get_new_chat_data()
    {
        $this->actingAs($user = User::factory()->create());
        $response = $this->get(route('new-chat.index'));

        $response->assertStatus(200);
    }
}
