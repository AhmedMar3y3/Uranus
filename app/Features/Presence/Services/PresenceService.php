<?php

namespace App\Features\Presence\Services;

use App\Features\Presence\Events\PresenceStatusChanged;
use App\Models\User;

class PresenceService
{
    public function online(User $user): User
    {
        $user->forceFill([
            'is_online' => true,
            'last_seen_at' => now(),
        ])->save();

        broadcast(new PresenceStatusChanged($user))->toOthers();

        return $user->refresh();
    }

    public function offline(User $user): User
    {
        $user->forceFill([
            'is_online' => false,
            'last_seen_at' => now(),
        ])->save();

        broadcast(new PresenceStatusChanged($user))->toOthers();

        return $user->refresh();
    }
}
