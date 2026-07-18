<?php

use Illuminate\Support\Facades\Route;
use App\Features\Admin\Controllers\UserController;
use App\Features\Admin\Controllers\AuthController;
use App\Features\Admin\Controllers\AdminController;
use App\Features\Admin\Controllers\SettingController;
use App\Features\Admin\Controllers\DashboardController;
use App\Features\Admin\Controllers\ApkReleaseController;
use App\Features\Admin\Controllers\FriendshipController;
use App\Features\Downloads\Controllers\DownloadController;
use App\Features\Admin\Controllers\ConversationController;


Route::get('/download', [DownloadController::class, 'show'])->name('download');
Route::get('/download/apk', [DownloadController::class, 'download'])
    ->middleware('throttle:60,1')
    ->name('apk.download');

Route::redirect('/'     , '/admin');
Route::redirect('/home' , '/admin')->name('home');
Route::redirect('/login', '/admin/login')->name('login');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login' , [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::get('/'             , DashboardController::class)->name('dashboard');
        Route::post('/apk-releases', [ApkReleaseController::class, 'store'])->name('apk-releases.store');
        Route::put('/settings'     , [SettingController::class, 'update'])->name('settings.update');
        Route::post('/logout'      , [AuthController::class, 'logout'])->name('logout');

        Route::resource('admins'       , AdminController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('users'        , UserController::class)->only(['index', 'update', 'destroy']);
        Route::resource('conversations', ConversationController::class)->only(['index', 'show', 'destroy']);
        Route::resource('friendships'  , FriendshipController::class)->only(['index', 'update', 'destroy']);
    });
});
