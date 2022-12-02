<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\likeController;
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

Route::get('/', function () {
    return view('welcome');
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


Route::resource('videos', videoController::class);

Route::get('/video/search', [videoController::class, 'search'])->name('video.search');
