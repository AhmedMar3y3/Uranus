<?php

use Illuminate\Support\Facades\Route;
use App\Features\Auth\Controllers\AuthController;
use App\Features\Chat\Controllers\ConversationController;
use App\Features\Chat\Controllers\MessageController;
use App\Features\Friends\Controllers\FriendController;
use App\Features\Presence\Controllers\PresenceController;
use App\Features\Profile\Controllers\ProfileController;
use App\Features\Users\Controllers\UserController;


Route::prefix('auth')->group(function () {
    Route::post('otp', [AuthController::class, 'sendOtp'])->name('auth.otp.send');
    Route::post('otp/verify', [AuthController::class, 'verifyOtp'])->name('auth.otp.verify');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile/me', [ProfileController::class, 'me'])->name('profile.me');
    Route::post('profile/complete', [ProfileController::class, 'complete'])->name('profile.complete');
    Route::post('profile/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('home', [ConversationController::class, 'index'])->name('home');

    Route::prefix('users')->group(function () {
        Route::get('/'      , [UserController::class, 'index'])->name('users.index');
        Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
    });

    Route::prefix('friends')->group(function () {
        Route::get('/', [FriendController::class, 'index'])->name('friends.index');
        Route::get('/requests', [FriendController::class, 'requests'])->name('friends.requests');
        Route::get('/blocked', [FriendController::class, 'blocked'])->name('friends.blocked');
        Route::post('/request' , [FriendController::class, 'send'])->name('friends.request');
        Route::post('/accept'  , [FriendController::class, 'accept'])->name('friends.accept');
        Route::post('/reject'  , [FriendController::class, 'reject'])->name('friends.reject');
        Route::post('/cancel'  , [FriendController::class, 'cancel'])->name('friends.cancel');
        Route::delete('/remove', [FriendController::class, 'remove'])->name('friends.remove');
        Route::post('/block'   , [FriendController::class, 'block'])->name('friends.block');
        Route::post('/unblock' , [FriendController::class, 'unblock'])->name('friends.unblock');
    });

    Route::prefix('conversations')->group(function () {
        Route::get('/'                      ,  [ConversationController::class, 'index'])->name('conversations.index');
        Route::post('/'                     , [ConversationController::class, 'store'])->name('conversations.store');
        Route::post('/{conversation}/typing', [MessageController::class, 'typing'])->name('conversations.typing');

        Route::prefix('{conversation}/messages')->group(function () {
            Route::get('/'                   , [MessageController::class, 'index'])->name('messages.index');
            Route::post('/'                  , [MessageController::class, 'store'])->name('messages.store');
            Route::put('{message}'           , [MessageController::class, 'update'])->name('messages.update');
            Route::delete('{message}'        , [MessageController::class, 'destroy'])->name('messages.destroy');
            Route::post('{message}/delivered', [MessageController::class, 'delivered'])->name('messages.delivered');
            Route::post('{message}/seen'     , [MessageController::class, 'seen'])->name('messages.seen');
        });
    });

    Route::prefix('presence')->group(function () {
        Route::post('/online' , [PresenceController::class, 'online'])->name('presence.online');
        Route::post('/offline', [PresenceController::class, 'offline'])->name('presence.offline');
    });
});
