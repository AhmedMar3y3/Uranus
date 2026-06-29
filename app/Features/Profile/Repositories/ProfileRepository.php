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
}
