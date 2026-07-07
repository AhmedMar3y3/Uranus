<?php

namespace App\Features\Profile\Repositories;

use App\Models\User;

class ProfileRepository
{
    public function update(User $user, array $data): User
    {
        $user->fill($data);
        $user->completed_profile = true;
        $user->save();

        return $user->refresh();
    }

    public function updatePublicKey(User $user, string $publicKey): User
    {
        $user->public_key = $publicKey;
        $user->save();

        return $user->refresh();
    }
}
