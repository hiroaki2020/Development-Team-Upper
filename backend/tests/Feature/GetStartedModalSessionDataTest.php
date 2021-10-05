<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class GetStartedModalSessionDataTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if get started modal opener's visibility is saved to session.
     * 
     * @return void
     */
    public function test_save_get_started_modal_opener_visibility_to_session()
    {
        $response = $this->put('/get-started-modal-opener-visibility', [
            '_method' => 'PUT',
            'show_opener' => true
        ]);

        $response->assertSessionHas('show_get_started_modal_opener', true);
    }

    /**
     * Test if get started modal opener's visibility in session data is deleted when user log out.
     * 
     * @return void
     */
    public function test_delete_get_started_modal_opener_visibility_in_session_when_user_log_out()
    {
        $this->actingAs(User::factory()->create());

        session()->put('show_get_started_modal_opener', true);

        $response = $this->post(route('logout'));

        $response->assertSessionMissing('show_get_started_modal_opener');
    }

    /**
     * Test if get started modal page index is saved to session.
     * 
     * @return void
     */
    public function test_save_get_started_modal_page_index_to_session()
    {
        $response = $this->put('/current-slide', [
            '_method' => 'PUT',
            'current_slide_index' => 3
        ]);

        $response->assertSessionHas('current_slide_index', 3);
    }

    /**
     * Test if get started modal page index in session data is deleted when user log out.
     * 
     * @return void
     */
    public function test_delete_get_started_modal_page_index_in_session_when_user_log_out()
    {
        $this->actingAs(User::factory()->create());

        session()->put('current_slide_index', 3);

        $response = $this->post(route('logout'));

        $response->assertSessionMissing('current_slide_index');
    }

    /**
     * Test if user can get get started modal related session data.
     * 
     * @return void
     */
    public function test_get_get_started_modal_related_session_data()
    {
        session()->put('show_get_started_modal_opener', true);
        session()->put('current_slide_index', 3);

        $response = $this->getJson(route('get-started-modal-session-data'));

        $response->assertJson(['showOpener' => true, 'currentSlideIndex' => 3]);
    }
}
