<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Inertia\Testing\Assert;

class ShowDocumentationPageTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Documentation page can be shown.
     * 
     * @return void
     */
    public function test_documenatation_page_can_be_shown()
    {
        $response = $this->get(route('documentation'));

        $response->assertStatus(200)
                ->assertInertia(fn(Assert $page) => $page
                    ->component('Documentation')
                );
    }
}
