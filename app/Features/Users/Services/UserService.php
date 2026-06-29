<?php

namespace App\Features\Users\Services;

use App\Enums\FriendshipStatus;
use App\Features\Friends\Repositories\FriendshipRepository;
use App\Features\Users\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserService
{
    public function __construct(
        private readonly UserRepository $users,
        private readonly FriendshipRepository $friendships,
    ) {
    }

    public function search(?string $query, int $perPage): LengthAwarePaginator
    {
        return $this->users->search($query, $perPage);
    }

    public function profile(User $viewer, int $userId): User
    {
        $profile = $this->users->findProfile($userId);
        $profile->friends_count = $this->friendships->countAcceptedFriends($profile);
        $profile->mutual_friends_count = $viewer->id === $profile->id
            ? 0
            : $this->friendships->countMutualFriends($viewer, $profile);
        $friendship = $viewer->id === $profile->id ? null : $this->friendships->between($viewer->id, $profile->id);
        $profile->friendship_status = $friendship?->status instanceof FriendshipStatus ? $friendship->status->value : null;

        return $profile;
    }
}
