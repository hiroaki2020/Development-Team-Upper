<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\SkillOption;

class SearchUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Users can be searched.
     * 
     * @return void
     */
    public function test_users_can_be_searched()
    {
        SkillOption::create(['skill' => 'php']);
        $user = User::factory()->create([
            'name' => 'steve'
        ]);
        $user->skills()->create(['skill' => 'php']);
        
        $response = $this->get(route('search-user.show', [
            'searchUserInput' => 'steve',
            'skills' => ['php'],
            'page' => 1
        ]));

        $response->assertStatus(200);
    }
}
