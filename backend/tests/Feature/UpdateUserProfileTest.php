<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\SkillOption;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UpdateUserProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * User profile information can be updated.
     * 
     * @return void
     */
    public function test_user_profile_information_can_be_updated()
    {
        Storage::fake('s3-public');
        SkillOption::create(['skill' => 'php']);
        $this->actingAs($user = User::factory()->create());

        $response = $this->put('/user/profile-information', [
            '_method' => 'PUT',
            'name' => 'Test Name',
            'photo' => $image = UploadedFile::fake()->image('image.jpg'),
            'description' => 'Hello world!',
            'url' => 'https://laravel-test.com',
            'skills' => ['php']
        ]);

        Storage::disk('s3-public')->assertExists('profile-photos/'.$image->hashName());
        $this->assertEquals('Test Name', $user->fresh()->name);
        $this->assertEquals('Hello world!', $user->fresh()->description);
        $this->assertEquals('https://laravel-test.com', $user->fresh()->url);
        $this->assertEquals(['php'], $user->fresh()->load('skills')->skills->pluck('skill')->all());
    }

    /**
     * User profile photo can be deleted.
     * 
     * @return void
     */
    public function test_user_profile_photo_can_be_deleted()
    {
        $this->actingAs($user = User::factory()->create());

        Storage::fake('s3-public')->put(
            'profile-photos',
            $image = UploadedFile::fake()->image('image.jpg')
        );
        $user->forceFill([
            'profile_photo_path' => 'profile-photos/'.$image->hashName()
        ])->save();
        Storage::disk('s3-public')->assertExists('profile-photos/'.$image->hashName());

        $response = $this->delete('/user/profile-photo');
        
        Storage::disk('s3-public')->assertMissing('profile-photos/'.$image->hashName());
        $response->assertStatus(303)
                ->assertSessionHas('status', 'profile-photo-deleted');
    }
}
