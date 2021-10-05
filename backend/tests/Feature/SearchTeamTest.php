<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\SkillOption;

class SearchTeamTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teams can be searched.
     * 
     * @return void
     */
    public function test_teams_can_be_searched()
    {
        SkillOption::create(['skill' => 'php']);
        $user = User::factory()->withTeam()->create([
            'name' => 'steve'
        ]);
        $team = $user->currentTeam;
        $team->wanted = true;
        $team->save();
        $team->requirements()->create(['requirement' => 'php', 'required' => true]);

        $response = $this->get(route('search-team.show', [
            'searchTeamInput' => 'steve\'s Team',
            'requirements' => ['php'],
            'options' => [],
            'wanted' => true,
            'notWanted' => false,
            'page' => 1
        ]));

        $response->assertStatus(200);
    }
}
