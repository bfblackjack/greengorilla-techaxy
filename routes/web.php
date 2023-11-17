<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Public, Signed Routes
Route::get('account-setup/{token:token}', [\App\Http\Controllers\UserController::class, 'userSetup'])->name('account-setup');
Route::get('test', [\App\Http\Controllers\UserController::class, 'userSetup'])->name('test');


// Authenticated Routes
Route::group(['prefix' => 'app', 'middleware' => [
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
]], function () {
    Route::get('dashboard', function() {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::group(['prefix' => 'users'], function() {
        Route::post('create', [\App\Http\Controllers\UserController::class, 'createUser'])->name('user.create-user');
    });
});
