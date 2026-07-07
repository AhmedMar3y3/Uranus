<?php

namespace App\Features\Profile\Services;

use App\Features\Friends\Repositories\FriendshipRepository;
use App\Features\Profile\Repositories\ProfileRepository;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    public function __construct(
        private readonly ProfileRepository $profiles,
        private readonly FriendshipRepository $friendships,
    ) {
    }

    public function complete(User $user, array $data): User
    {
        return $this->saveProfile($user, $data);
    }

    public function update(User $user, array $data): User
    {
        return $this->saveProfile($user, $data);
    }

    public function currentUserProfile(User $user): User
    {
        $user->friends_count = $this->friendships->countAcceptedFriends($user);
        $user->mutual_friends_count = 0;
        $user->friendship_status = null;

        return $user;
    }

    public function updatePublicKey(User $user, string $publicKey): User
    {
        // Security boundary: the backend stores only the user's public key.
        // Private keys must stay on the Flutter device keystore/keychain.
        return $this->currentUserProfile($this->profiles->updatePublicKey($user, $publicKey));
    }

    private function saveProfile(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            if (($data['image'] ?? null) instanceof UploadedFile) {
                if ($user->image_path) {
                    Storage::disk('public')->delete($user->image_path);
                }

                $data['image_path'] = $data['image']->store('users/images', 'public');
            }

            unset($data['image']);

            return $this->currentUserProfile($this->profiles->update($user, $data));
        });
    }
}
