<?php

use App\Features\Admin\Controllers\AdminController;
use App\Features\Admin\Controllers\AuthController;
use App\Features\Admin\Controllers\ConversationController;
use App\Features\Admin\Controllers\DashboardController;
use App\Features\Admin\Controllers\FriendshipController;
use App\Features\Admin\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/download', function () {
    $apkPath = public_path('uranus.apk');

    return view('download', [
        'apkSize' => file_exists($apkPath)
            ? number_format(filesize($apkPath) / 1024 / 1024, 1) . ' MB'
            : null,
    ]);
})->name('download');

Route::redirect('/', '/admin');
Route::redirect('/home', '/admin')->name('home');
Route::redirect('/login', '/admin/login')->name('login');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::resource('admins', AdminController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('users', UserController::class)->only(['index', 'update', 'destroy']);
        Route::resource('conversations', ConversationController::class)->only(['index', 'show', 'destroy']);
        Route::resource('friendships', FriendshipController::class)->only(['index', 'update', 'destroy']);
    });
});
