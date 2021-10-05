<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Inertia\Testing\Assert;

class ShowTeamUpPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TeamUp page can be shown.
     * 
     * @return void
     */
    public function test_teamup_page_can_be_shown()
    {
        $response = $this->get(route('teamup'));

        $response->assertStatus(200)
                ->assertInertia(fn(Assert $page) => $page
                    ->component('TeamUp/TeamUp')
                    ->hasAll([
                        'joinRequestsCount',
                        'teamInvitationsCount',
                        'skillOptions',
                        'canAddTeamMembers'
                    ])
                );
    }
}
