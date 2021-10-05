<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Inertia\Testing\Assert;

class ShowHomePageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * App home page can be shown.
     * 
     * @return void
     */
    public function test_home_page_can_be_shown()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
                ->assertInertia(fn(Assert $page) => $page
                    ->component('Home')
                    ->where('isJustLoggedOut', false)
                );
    }
}
