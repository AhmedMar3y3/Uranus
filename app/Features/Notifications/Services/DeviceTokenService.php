<?php

namespace App\Features\Notifications\Services;

use App\Models\User;
use App\Models\UserDevice;

class DeviceTokenService
{
    public function store(User $user, array $data): UserDevice
    {
        return UserDevice::updateOrCreate(
            ['fcm_token_hash' => hash('sha256', $data['fcm_token'])],
            [
                'user_id' => $user->id,
                'fcm_token' => $data['fcm_token'],
                'platform' => $data['platform'] ?? null,
                'device_name' => $data['device_name'] ?? null,
                'last_used_at' => now(),
            ]
        );
    }

    public function delete(User $user, string $token): void
    {
        UserDevice::query()
            ->where('user_id', $user->id)
            ->where('fcm_token_hash', hash('sha256', $token))
            ->delete();
    }
}
