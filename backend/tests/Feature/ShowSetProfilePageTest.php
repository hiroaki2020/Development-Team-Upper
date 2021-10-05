<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Inertia\Testing\Assert;

class ShowSetProfilePageTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * SetProfile page can be shown.
     * 
     * @return void
     */
    public function test_set_profile_page_can_be_shown()
    {
        $this->actingAs($user = User::factory()->create());
        $response = $this->get('/profile');

        $response->assertStatus(200)
                ->assertInertia(fn(Assert $page) => $page
                    ->component('Profile/SetProfile')
                    ->hasAll([
                        'skillOptions',
                        'ownedSkills',
                        'team',
                        'currentRequirements',
                        'currentOptions',
                        'wantedPassed',
                        'permissions.canUpdateTeam'
                    ])
                );
    }
}
