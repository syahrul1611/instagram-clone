<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\FilesController;
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

// file route
Route::get('/storage/{foldername}/{filename}', [FilesController::class, 'getFile'])->where('filename', '^(.+)\/([^\/]+)$');

// route untuk halaman welcome
Route::get('/', function ()
{
    return view('welcome');
})->middleware('guest');

// kontroler autentikasi user
Route::controller(AuthController::class)->middleware('guest')->group(function ()
{
    // route untuk fungsi login user
    Route::post('/login', 'login');
    // route untuk fungsi registrasi user baru
    Route::post('/register', 'register');
});

// route untuk fungsi logout user
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// kontroler user
Route::controller(UserController::class)->middleware('auth')->group(function ()
{
    // route untuk mencari user
    Route::get('/search', 'search');
    // route untuk mendapat data user
    Route::get('/profile/{user}', 'get_profile');
    // route untuk mengubah data user
    Route::put('/profile', 'update_profile');
});

// kontroler post
Route::resource('/post', PostController::class)->except(['index'])->middleware('auth');
Route::get('/home', [PostController::class, 'index'])->name('post.index')->middleware('auth');

// kontroler friend
Route::controller(FriendController::class)->middleware('auth')->group(function ()
{
    // route untuk meminta pertemanan
    Route::post('/friend/{user}', 'request');
    // route untuk menerima pertemanan
    Route::get('/friend/accept/{user}', 'accept');
    // route untuk menolak pertemanan
    Route::get('/friend/reject/{user}', 'reject');
    // route untuk menghapus pertemanan
    Route::delete('/friend/{user}', 'delete');
});

// kontroler like
Route::post('/like/{post}', [LikeController::class, 'store'])->middleware('auth');

// kontroler comment
Route::post('/comment/{post}', [CommentController::class, 'store'])->middleware('auth');