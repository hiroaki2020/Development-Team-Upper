<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UpdateEmailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Email can be updated.
     * 
     * @return void
     */
    public function test_email_can_be_updated()
    {
        $this->actingAs($user = User::factory()->create());
        $response = $this->post(route('user-email.update'), [
            '_method' => 'PUT',
            'email' => 'test@test.com'
        ]);

        $response->assertStatus(302)
                ->assertSessionHas('status', 'email-updated');
    }
}
