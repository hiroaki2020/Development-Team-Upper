<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SwitchLanguageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Switch language.
     * 
     * @return void
     */
    public function test_language_can_be_switched()
    {
        $response = $this->get('/language/ja');

        $response->assertStatus(302)
            ->assertSessionHas('locale', 'ja');
    }
}
