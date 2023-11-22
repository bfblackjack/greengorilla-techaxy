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
    return redirect('login');
});

// Public, Signed Routes
Route::get('account-setup/{invite}', [\App\Http\Controllers\UserController::class, 'userSetup'])->name('account-setup');
Route::get('test', [\App\Http\Controllers\UserController::class, 'userSetup'])->name('test');
Route::post('account-setup/{invite}', [\App\Http\Controllers\UserController::class, 'completeSetup'])->name('account-setup-post');


// Authenticated Routes
Route::group(['prefix' => 'app', 'middleware' => [
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
]], function () {
    Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('createWebhook', [\App\Http\Controllers\DashboardController::class, 'createWebhook'])->name('dashboard.create-webhook');
    Route::post('updateBuyerEndpoint', [\App\Http\Controllers\DashboardController::class, 'updateBuyerEndpoint'])->name('dashboard.update-buyer-endpoint');

    Route::group(['prefix' => 'users'], function() {
        Route::get('/', [\App\Http\Controllers\UserController::class, 'index'])->name('users');
        Route::post('create', [\App\Http\Controllers\UserController::class, 'createUser'])->name('users.create-user');
        Route::post('resendInvite/{user}', [\App\Http\Controllers\UserController::class, 'resendInvite'])->name('user.resend-invite');
    });
});
