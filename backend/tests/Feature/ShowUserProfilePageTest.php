<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Inertia\Testing\Assert;

class ShowUserProfilePageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * User profile page can be shown.
     * 
     * @return void
     */
    public function test_user_profile_page_can_be_shown()
    {
        $user = User::factory()->create();
        
        $response = $this->get(route('see-profile.show', $user->id));

        $response->assertStatus(200)
                ->assertInertia(fn(Assert $page) => $page
                    ->component('Profile/Profile')
                    ->hasAll([
                        'profile',
                        'team',
                        'requested',
                        'inviting',
                        'member_or_not',
                        'availableRoles',
                        'permissions.canAddTeamMembers',
                        'permissions.canRemoveTeamMembers'
                    ])
                );
    }
}
