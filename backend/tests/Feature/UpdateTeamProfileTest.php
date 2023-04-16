<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\SkillOption;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UpdateTeamProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Team owner can update team profile information.
     * 
     * @return void
     */
    public function test_team_owner_can_update_team_profile_information()
    {
        Storage::fake('s3-public');
        SkillOption::create(['skill' => 'php']);
        SkillOption::create(['skill' => 'laravel']);
        $this->actingAs($user = User::factory()->withTeam()->create());

        $team = $user->currentTeam;

        $response = $this->put('/teams', [
            '_method' => 'PUT',
            'name' => 'Test Name',
            'photo' => $image = UploadedFile::fake()->image('image.jpg'),
            'description' => 'Hello world!',
            'wanted' => true,
            'requirements' => ['php'],
            'options' => ['laravel']
        ]);

        $this->assertCount(1, $user->fresh()->ownedTeams);
        Storage::disk('s3-public')->assertExists('team-profile-photos/'.$image->hashName());
        $this->assertEquals('Test Name', $team->fresh()->name);
        $this->assertEquals('Hello world!', $team->fresh()->description);
        $this->assertEquals(true, $team->fresh()->wanted);
        $this->assertEquals(['php'], $team->fresh()->load('requirements')->requirements()->where('required', 1)->pluck('requirement')->all());
        $this->assertEquals(['laravel'], $team->fresh()->load('requirements')->requirements()->where('required', 0)->pluck('requirement')->all());
    }

    /**
     * Team owner can delete team profile photo.
     * 
     * @return void
     */
    public function test_team_owner_can_delete_team_profile_photo()
    {
        $this->actingAs($user = User::factory()->withTeam()->create());

        Storage::fake('s3-public')->put(
            'team-profile-photos',
            $image = UploadedFile::fake()->image('image.jpg')
        );
        $user->currentTeam->fill([
            'team_profile_photo_path' => 'team-profile-photos/'.$image->hashName()
        ])->save();
        Storage::disk('s3-public')->assertExists('team-profile-photos/'.$image->hashName());
        
        $response = $this->delete('/team/profile-photo/'.$user->currentTeam->id);
        
        Storage::disk('s3-public')->assertMissing('team-profile-photos/'.$image->hashName());
        $response->assertStatus(303)
                ->assertSessionHas('status', 'team-profile-photo-deleted');
    }

    /**
     * Team members in administrator role can update team profile information.
     * 
     * @return void
     */
    public function test_team_members_in_administrator_role_can_update_team_profile_information()
    {
        Storage::fake('s3-public');
        SkillOption::create(['skill' => 'php']);
        SkillOption::create(['skill' => 'laravel']);
        $user = User::factory()->withTeam()->create();
        $otherUser = User::factory()->create();
        $team = $user->currentTeam;
        $team->users()->attach($otherUser, ['role' => 'administrator']);
        $this->actingAs($otherUser = $otherUser->fresh());
        $otherUser->currentTeam;

        $response = $this->put('/teams', [
            '_method' => 'PUT',
            'name' => 'Test Name',
            'photo' => $image = UploadedFile::fake()->image('image.jpg'),
            'description' => 'Hello world!',
            'wanted' => true,
            'requirements' => ['php'],
            'options' => ['laravel']
        ]);

        $this->assertCount(1, $user->fresh()->ownedTeams);
        Storage::disk('s3-public')->assertExists('team-profile-photos/'.$image->hashName());
        $this->assertEquals('Test Name', $team->fresh()->name);
        $this->assertEquals('Hello world!', $team->fresh()->description);
        $this->assertEquals(true, $team->fresh()->wanted);
        $this->assertEquals(['php'], $team->fresh()->load('requirements')->requirements()->where('required', 1)->pluck('requirement')->all());
        $this->assertEquals(['laravel'], $team->fresh()->load('requirements')->requirements()->where('required', 0)->pluck('requirement')->all());
    }

    /**
     * Team members in administrator role can delete team profile photo.
     * 
     * @return void
     */
    public function test_team_members_in_administrator_role_can_delete_team_profile_photo()
    {
        $user = User::factory()->withTeam()->create();
        $otherUser = User::factory()->create();
        $team = $user->currentTeam;
        $team->users()->attach($otherUser, ['role' => 'administrator']);
        $this->actingAs($otherUser = $otherUser->fresh());
        $otherUser->currentTeam;

        Storage::fake('s3-public')->put(
            'team-profile-photos',
            $image = UploadedFile::fake()->image('image.jpg')
        );
        $user->currentTeam->fill([
            'team_profile_photo_path' => 'team-profile-photos/'.$image->hashName()
        ])->save();
        Storage::disk('s3-public')->assertExists('team-profile-photos/'.$image->hashName());
        
        $response = $this->delete('/team/profile-photo/'.$user->currentTeam->id);
        
        Storage::disk('s3-public')->assertMissing('team-profile-photos/'.$image->hashName());
        $response->assertStatus(303)
                ->assertSessionHas('status', 'team-profile-photo-deleted');
    }

    /**
     * Team members in editor role can update team profile information.
     * 
     * @return void
     */
    public function test_team_members_in_editor_role_can_update_team_profile_information()
    {
        Storage::fake('s3-public');
        SkillOption::create(['skill' => 'php']);
        SkillOption::create(['skill' => 'laravel']);
        $user = User::factory()->withTeam()->create();
        $otherUser = User::factory()->create();
        $team = $user->currentTeam;
        $team->users()->attach($otherUser, ['role' => 'editor']);
        $this->actingAs($otherUser = $otherUser->fresh());
        $otherUser->currentTeam;

        $response = $this->put('/teams', [
            '_method' => 'PUT',
            'name' => 'Test Name',
            'photo' => $image = UploadedFile::fake()->image('image.jpg'),
            'description' => 'Hello world!',
            'wanted' => true,
            'requirements' => ['php'],
            'options' => ['laravel']
        ]);

        $this->assertCount(1, $user->fresh()->ownedTeams);
        Storage::disk('s3-public')->assertExists('team-profile-photos/'.$image->hashName());
        $this->assertEquals('Test Name', $team->fresh()->name);
        $this->assertEquals('Hello world!', $team->fresh()->description);
        $this->assertEquals(true, $team->fresh()->wanted);
        $this->assertEquals(['php'], $team->fresh()->load('requirements')->requirements()->where('required', 1)->pluck('requirement')->all());
        $this->assertEquals(['laravel'], $team->fresh()->load('requirements')->requirements()->where('required', 0)->pluck('requirement')->all());
    }

    /**
     * Team members in editor role can delete team profile photo.
     * 
     * @return void
     */
    public function test_team_members_in_editor_role_can_delete_team_profile_photo()
    {
        $user = User::factory()->withTeam()->create();
        $otherUser = User::factory()->create();
        $team = $user->currentTeam;
        $team->users()->attach($otherUser, ['role' => 'editor']);
        $this->actingAs($otherUser = $otherUser->fresh());
        $otherUser->currentTeam;

        Storage::fake('s3-public')->put(
            'team-profile-photos',
            $image = UploadedFile::fake()->image('image.jpg')
        );
        $user->currentTeam->fill([
            'team_profile_photo_path' => 'team-profile-photos/'.$image->hashName()
        ])->save();
        Storage::disk('s3-public')->assertExists('team-profile-photos/'.$image->hashName());
        
        $response = $this->delete('/team/profile-photo/'.$user->currentTeam->id);
        
        Storage::disk('s3-public')->assertMissing('team-profile-photos/'.$image->hashName());
        $response->assertStatus(303)
                ->assertSessionHas('status', 'team-profile-photo-deleted');
    }

    /**
     * Team members in viewer role can't update team profile information.
     * 
     * @return void
     */
    public function test_team_members_in_viewer_role_cant_update_team_profile_information()
    {
        Storage::fake('s3-public');
        SkillOption::create(['skill' => 'php']);
        SkillOption::create(['skill' => 'laravel']);
        $user = User::factory()->withTeam()->create();
        $otherUser = User::factory()->create();
        $team = $user->currentTeam;
        $team->users()->attach($otherUser, ['role' => 'viewer']);
        $this->actingAs($otherUser = $otherUser->fresh());
        $otherUser->currentTeam;

        $response = $this->put('/teams', [
            '_method' => 'PUT',
            'name' => 'Test Name',
            'photo' => $image = UploadedFile::fake()->image('image.jpg'),
            'description' => 'Hello world!',
            'wanted' => true,
            'requirements' => ['php'],
            'options' => ['laravel']
        ]);

        $this->assertCount(1, $user->fresh()->ownedTeams);
        Storage::disk('s3-public')->assertMissing('team-profile-photos/'.$image->hashName());
        $this->assertNotEquals('Test Name', $team->fresh()->name);
        $this->assertNotEquals('Hello world!', $team->fresh()->description);
        $this->assertNotEquals(true, $team->fresh()->wanted);
        $this->assertNotEquals(['php'], $team->fresh()->load('requirements')->requirements()->where('required', 1)->pluck('requirement')->all());
        $this->assertNotEquals(['laravel'], $team->fresh()->load('requirements')->requirements()->where('required', 0)->pluck('requirement')->all());
    }

    /**
     * Team members in viewer role can't delete team profile photo.
     * 
     * @return void
     */
    public function test_team_members_in_viewer_role_cant_delete_team_profile_photo()
    {
        $user = User::factory()->withTeam()->create();
        $otherUser = User::factory()->create();
        $team = $user->currentTeam;
        $team->users()->attach($otherUser, ['role' => 'viewer']);
        $this->actingAs($otherUser = $otherUser->fresh());
        $otherUser->currentTeam;

        Storage::fake('s3-public')->put(
            'team-profile-photos',
            $image = UploadedFile::fake()->image('image.jpg')
        );
        $user->currentTeam->fill([
            'team_profile_photo_path' => 'team-profile-photos/'.$image->hashName()
        ])->save();
        Storage::disk('s3-public')->assertExists('team-profile-photos/'.$image->hashName());
        
        $response = $this->delete('/team/profile-photo/'.$user->currentTeam->id);
        
        Storage::disk('s3-public')->assertExists('team-profile-photos/'.$image->hashName());
        $response->assertStatus(403)
                ->assertSessionMissing('status');
    }
}
