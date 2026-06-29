<?php

namespace App\Features\Friends\Repositories;

use App\Enums\FriendshipStatus;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class FriendshipRepository
{
    public function between(int $firstUserId, int $secondUserId): ?Friendship
    {
        return Friendship::query()
            ->where(function ($query) use ($firstUserId, $secondUserId) {
                $query->where('requester_id', $firstUserId)->where('addressee_id', $secondUserId);
            })
            ->orWhere(function ($query) use ($firstUserId, $secondUserId) {
                $query->where('requester_id', $secondUserId)->where('addressee_id', $firstUserId);
            })
            ->first();
    }

    public function createRequest(User $requester, User $addressee): Friendship
    {
        return Friendship::create([
            'requester_id' => $requester->id,
            'addressee_id' => $addressee->id,
            'status' => FriendshipStatus::Pending,
        ]);
    }

    public function acceptedBetween(User $firstUser, User $secondUser): ?Friendship
    {
        $friendship = $this->between($firstUser->id, $secondUser->id);

        return $friendship?->status === FriendshipStatus::Accepted ? $friendship : null;
    }

    public function acceptedFriendIds(User $user): Collection
    {
        return Friendship::query()
            ->where('status', FriendshipStatus::Accepted)
            ->where(function ($query) use ($user) {
                $query->where('requester_id', $user->id)->orWhere('addressee_id', $user->id);
            })
            ->get()
            ->map(fn (Friendship $friendship) => $friendship->requester_id === $user->id ? $friendship->addressee_id : $friendship->requester_id)
            ->values();
    }

    public function countAcceptedFriends(User $user): int
    {
        return $this->acceptedFriendIds($user)->count();
    }

    public function countMutualFriends(User $firstUser, User $secondUser): int
    {
        return $this->acceptedFriendIds($firstUser)
            ->intersect($this->acceptedFriendIds($secondUser))
            ->count();
    }

    public function friends(User $user, int $perPage): LengthAwarePaginator
    {
        return User::query()
            ->whereIn('id', $this->acceptedFriendIds($user))
            ->orderBy('username')
            ->paginate($perPage);
    }

    public function receivedPendingRequests(User $user, int $perPage): LengthAwarePaginator
    {
        return Friendship::query()
            ->with(['requester', 'addressee'])
            ->where('addressee_id', $user->id)
            ->where('status', FriendshipStatus::Pending)
            ->latest()
            ->paginate($perPage);
    }

    public function sentPendingRequests(User $user, int $perPage): LengthAwarePaginator
    {
        return Friendship::query()
            ->with(['requester', 'addressee'])
            ->where('requester_id', $user->id)
            ->where('status', FriendshipStatus::Pending)
            ->latest()
            ->paginate($perPage);
    }

    public function blockedBy(User $user, int $perPage): LengthAwarePaginator
    {
        return Friendship::query()
            ->with(['requester', 'addressee'])
            ->where('blocked_by_id', $user->id)
            ->where('status', FriendshipStatus::Blocked)
            ->latest('updated_at')
            ->paginate($perPage);
    }
}
