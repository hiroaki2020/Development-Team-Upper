<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Inertia\Testing\Assert;

class ShowTeamInvitationListPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Team invitation list page can be shown.
     * 
     * @return void
     */
    public function test_team_invitation_list_page_can_be_shown()
    {
        $this->actingAs($user = User::factory()->create());
        $response = $this->get(route('invitations.index'));

        $response->assertStatus(200)
                ->assertInertia(fn(Assert $page) => $page
                    ->component('TeamUp/Invitations/Index')
                    ->has('invitations')
                );
    }
}
