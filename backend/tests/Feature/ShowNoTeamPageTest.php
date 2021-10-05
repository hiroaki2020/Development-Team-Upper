<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Inertia\Testing\Assert;

class ShowNoTeamPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * NoTeam page can be shown.
     * 
     * @return void
     */
    public function test_noteam_page_can_be_shown()
    {
        $this->actingAs($user = User::factory()->create());
        
        $response = $this->get(route('no-team.index'));

        $response->assertStatus(200)
                ->assertInertia(fn(Assert $page) => $page
                    ->component('NoTeam')
                );
    }
}
