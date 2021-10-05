<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Inertia\Testing\Assert;

class ShowAccountPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Account page can be shown.
     * 
     * @return void
     */
    public function test_account_page_can_be_shown()
    {
        $this->actingAs($user = User::factory()->create());

        $response = $this->get(route('account.show'));

        $response->assertStatus(200)
                ->assertInertia(fn(Assert $page) => $page
                    ->component('Profile/Show')
                    ->has('sessions')
                );
    }
}
