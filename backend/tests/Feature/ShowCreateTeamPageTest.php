<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Inertia\Testing\Assert;

class ShowCreateTeamPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Create team page can be shown.
     * 
     * @return void
     */
    public function test_create_team_page_can_be_shown()
    {
        $this->actingAs($user = User::factory()->create());

        $response = $this->get(route('teams.create'));

        $response->assertStatus(200)
                ->assertInertia(fn(Assert $page) => $page
                    ->component('Teams/Create')
                );
    }
}
