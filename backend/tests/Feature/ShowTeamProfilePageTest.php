<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Inertia\Testing\Assert;

class ShowTeamProfilePageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Team profile page can be shown.
     * 
     * @return void
     */
    public function test_team_profile_page_can_be_shown()
    {
        $user = User::factory()->withTeam()->create();

        $response = $this->get(route('see-team-profile.show', $user->currentTeam->id));

        $response->assertStatus(200)
                ->assertInertia(fn(Assert $page) => $page
                    ->component('TeamProfile/TeamProfile')
                    ->hasAll([
                        'team',
                        'requirements',
                        'options',
                        'hasAnyUser',
                        'isMember',
                        'invited',
                        'availableRoles',
                        'requesting'
                    ])
                );
    }
}
