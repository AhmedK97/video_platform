<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\likeController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\videoController;
use Illuminate\Support\Facades\Route;

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

Route::get('/admin', function () {
    return view('theme.default');
});

Route::prefix('/admin')->middleware('can:update-video')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/channels', [ChannelController::class, 'adminIndex'])->name('channels.index');
    Route::patch('/{user}/channels', [ChannelController::class, 'adminUpdate'])->name('channels.update')->middleware('can:update-user');
    Route::delete('/channel/{id}', [ChannelController::class, 'adminDistroy'])->name('channel.delete')->middleware('can:update-user');
    Route::patch('{user}/block', [ChannelController::class, 'adminBlock'])->name('channel.block')->middleware('can:update-user');
    Route::get('/channels/blocked', [ChannelController::class, 'blockedChannels'])->name('channels.block');
    Route::patch('/{user}/unblock', [ChannelController::class, 'userUnblock'])->name('channel.unblock');
    Route::get('/channels/all', [ChannelController::class, 'Channels'])->name('channel.all');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('layouts.main');
    })->name('dashboard');
});

route::get('/', [MainController::class, 'main'])->name('main');

Route::get('/channel', [ChannelController::class, 'index'])->name('channel.index');


Route::post('/like', [likeController::class, 'likeVideo'])->name('like');

Route::post('/view', [videoController::class, 'addView'])->name('view');


Route::post('/comment', [CommentController::class, 'saveComment'])->name('comment');

//comment resource
Route::resource('comments', CommentController::class);

// delete comment
// Route::post('/delete/{id}', [CommentController::class, 'destroy'])->name('comment.destroy');
// edit specific  comment
// Route::get('/comment/{id}/edit', [CommentController::class, 'edit'])->name('comment.edit');
// update specific  comment
// Route::patch('/comment/{id}', [CommentController::class, 'update'])->name('comment.update');


// history resource
Route::resource('/history', HistoryController::class);
Route::post('/history', [HistoryController::class, 'destroyAll'])->name('history.destroyAll');

Route::resource('videos', videoController::class);

Route::get('/video/search', [videoController::class, 'search'])->name('video.search');
Route::get('main/{channel}/videos', [MainController::class, 'ChannelsVideo'])->name('main.channels.video');
Route::get('/channel/search', [ChannelController::class, 'search'])->name('channel.search');
