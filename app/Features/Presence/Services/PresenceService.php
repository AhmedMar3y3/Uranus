<?php

namespace App\Features\Presence\Services;

use App\Features\Presence\Events\PresenceStatusChanged;
use App\Models\User;

class PresenceService
{
    public function online(User $user): User
    {
        $wasOnline = (bool) $user->is_online;

        $user->forceFill([
            'is_online' => true,
            'last_seen_at' => now(),
        ])->save();

        if (! $wasOnline) {
            $this->broadcast($user);
        }

        return $user->refresh();
    }

    public function offline(User $user): User
    {
        $user->forceFill([
            'is_online' => false,
            'last_seen_at' => now(),
        ])->save();

        $this->broadcast($user);

        return $user->refresh();
    }

    public function markOfflineDueToTimeout(User $user): User
    {
        if (! $user->is_online) {
            return $user;
        }

        $user->forceFill(['is_online' => false])->save();

        $this->broadcast($user);

        return $user->refresh();
    }

    private function broadcast(User $user): void
    {
        broadcast(new PresenceStatusChanged($user))->toOthers();
    }
}
