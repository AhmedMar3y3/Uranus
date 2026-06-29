<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('online', function ($user) {
    return [
        'id' => $user->id,
        'username' => $user->username,
        'full_name' => $user->full_name,
        'image_path' => $user->image_path,
    ];
});

Broadcast::channel('conversations.{conversation}', function ($user, \App\Models\Conversation $conversation) {
    return (int) $conversation->user_one_id === (int) $user->id
        || (int) $conversation->user_two_id === (int) $user->id;
});
