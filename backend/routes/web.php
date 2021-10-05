<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\TeamUpController;
use App\Http\Controllers\JoinRequestController;
use App\Http\Controllers\HandleJoinRequestController;
use App\Http\Controllers\SeeProfileController;
use App\Http\Controllers\SetProfileController;
use App\Http\Controllers\SetTeamProfileController;
use App\Http\Controllers\SeeTeamProfileController;
use App\Http\Controllers\TeamProfilePhotoController;
use App\Http\Controllers\SearchUserController;
use App\Http\Controllers\SearchTeamController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ImageController;
use App\Http\Middleware\NoStoreResponseHeader;
use App\Http\Controllers\NewChatController;
use App\Http\Controllers\UserEmailController;
use App\Http\Controllers\NoTeamController;
use App\Http\Controllers\SwitchLanguageController;
use App\Http\Controllers\CurrentSlideController;
use App\Http\Controllers\ShowGetStartedModalOpenerController;
use App\Http\Controllers\GetStartedModalSessionDataController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::put('/get-started-modal-opener-visibility', [ShowGetStartedModalOpenerController::class, 'update'])
->name('show-opener');

Route::put('/current-slide', [CurrentSlideController::class, 'update'])
->name('current-slide');

Route::get('/get-started-modal-session-data', [GetStartedModalSessionDataController::class, 'index'])
->name('get-started-modal-session-data');

Route::get('/language/{lang}', [SwitchLanguageController::class, 'index'])
->where('lang', '^[a-z]{2}$')
->name('language');

Route::get('/teamup', [TeamUpController::class, 'index'])->name('teamup');

Route::inertia('/documentation', 'Documentation')->name('documentation');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [SetProfileController::class, 'index'])->name('yourprofile');
    Route::delete('/team/profile-photo/{team}', [TeamProfilePhotoController::class, 'destroy'])
    ->name('current-team-photo.destroy');
    Route::post('/chat', [ChatController::class, 'store'])
    ->name('chat.store');
    Route::get('/chat', [ChatController::class, 'index'])
    ->name('chat.index');
    Route::post('/send-message', [MessageController::class, 'store'])
    ->name('message.store');
    Route::post('upload-image', [ImageController::class, 'store'])
    ->name('image.store');
    Route::post('show-image', [ImageController::class, 'show'])
    ->name('image.show');
    Route::get('/send-message', [MessageController::class, 'index'])
    ->name('message.index');
    Route::get('/new-chat', [NewChatController::class, 'index'])
    ->name('new-chat.index');
    Route::post('/delete-chat', [ChatController::class, 'delete'])
    ->name('chat.delete');
    Route::post('/delete-message', [MessageController::class, 'delete'])
    ->name('message.delete');
    Route::resource('invitations', InvitationController::class)->only(['index', 'destroy']);
    Route::resource('join-requests', JoinRequestController::class)->only(['store', 'destroy']);
    Route::get('/handle-join-requests', [HandleJoinRequestController::class, 'index'])
    ->name('handle-join-requests.index')->middleware('has-team');
    Route::post('/handle-join-requests', [HandleJoinRequestController::class, 'accept'])
    ->name('handle-join-requests.accept');
    Route::delete('/handle-join-requests/{join_request}', [HandleJoinRequestController::class, 'decline'])
    ->name('handle-join-requests.decline');
    Route::put('/user/email', [UserEmailController::class, 'update'])
    ->name('user-email.update');
    Route::get('/no-team', [NoTeamController::class, 'index'])
    ->name('no-team.index');
});

Route::get('/see-profile/{id}', [SeeProfileController::class, 'show'])->name('see-profile.show');

Route::get('/see-team-profile/{id}', [SeeTeamProfileController::class, 'show'])->name('see-team-profile.show');

Route::get('/searchuser', [SearchUserController::class, 'show'])
->name('search-user.show');

Route::get('/searchteam', [SearchTeamController::class, 'show'])
->name('search-team.show');
