<?php

namespace App\Features\Friends\Services;

use App\Enums\FriendshipStatus;
use App\Features\Friends\Repositories\FriendshipRepository;
use App\Features\Notifications\Services\FirebaseNotificationService;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class FriendshipService
{
    public function __construct(
        private readonly FriendshipRepository $friendships,
        private readonly FirebaseNotificationService $notifications,
    ) {
    }

    public function sendRequest(User $requester, User $addressee): Friendship
    {
        return DB::transaction(function () use ($requester, $addressee) {
            $this->ensureDifferentUsers($requester, $addressee);

            $existing = $this->friendships->between($requester->id, $addressee->id);
            if ($existing && in_array($existing->status, [FriendshipStatus::Pending, FriendshipStatus::Accepted, FriendshipStatus::Blocked], true)) {
                throw ValidationException::withMessages(['user_id' => __('messages.friendship_already_exists')]);
            }

            if ($existing) {
                $existing->update([
                    'requester_id' => $requester->id,
                    'addressee_id' => $addressee->id,
                    'blocked_by_id' => null,
                    'status' => FriendshipStatus::Pending,
                ]);

                $friendship = $existing->refresh();
                $this->notifications->sendToUser(
                    $addressee,
                    'New friend request',
                    ($requester->full_name ?: $requester->username ?: 'Someone') . ' sent you a friend request',
                    [
                        'type' => 'friend.requested',
                        'friendship_id' => $friendship->id,
                        'user_id' => $requester->id,
                    ]
                );

                return $friendship;
            }

            $friendship = $this->friendships->createRequest($requester, $addressee);
            $this->notifications->sendToUser(
                $addressee,
                'New friend request',
                ($requester->full_name ?: $requester->username ?: 'Someone') . ' sent you a friend request',
                [
                    'type' => 'friend.requested',
                    'friendship_id' => $friendship->id,
                    'user_id' => $requester->id,
                ]
            );

            return $friendship;
        });
    }

    public function accept(User $user, User $requester): Friendship
    {
        return DB::transaction(function () use ($user, $requester) {
            $friendship = $this->requiredBetween($requester, $user);
            $this->ensureStatus($friendship, FriendshipStatus::Pending);

            if ($friendship->addressee_id !== $user->id) {
                throw ValidationException::withMessages(['user_id' => __('messages.friendship_action_not_allowed')]);
            }

            $friendship->update(['status' => FriendshipStatus::Accepted]);
            $this->notifications->sendToUser(
                $requester,
                'Friend request accepted',
                ($user->full_name ?: $user->username ?: 'Someone') . ' accepted your friend request',
                [
                    'type' => 'friend.accepted',
                    'friendship_id' => $friendship->id,
                    'user_id' => $user->id,
                ]
            );

            return $friendship->refresh();
        });
    }

    public function reject(User $user, User $requester): Friendship
    {
        return DB::transaction(function () use ($user, $requester) {
            $friendship = $this->requiredBetween($requester, $user);
            $this->ensureStatus($friendship, FriendshipStatus::Pending);

            if ($friendship->addressee_id !== $user->id) {
                throw ValidationException::withMessages(['user_id' => __('messages.friendship_action_not_allowed')]);
            }

            $friendship->update(['status' => FriendshipStatus::Rejected]);
            $this->notifications->sendToUser(
                $requester,
                'Friend request rejected',
                ($user->full_name ?: $user->username ?: 'Someone') . ' rejected your friend request',
                [
                    'type' => 'friend.rejected',
                    'friendship_id' => $friendship->id,
                    'user_id' => $user->id,
                ]
            );

            return $friendship->refresh();
        });
    }

    public function cancel(User $user, User $addressee): void
    {
        DB::transaction(function () use ($user, $addressee) {
            $friendship = $this->requiredBetween($user, $addressee);
            $this->ensureStatus($friendship, FriendshipStatus::Pending);

            if ($friendship->requester_id !== $user->id) {
                throw ValidationException::withMessages(['user_id' => __('messages.friendship_action_not_allowed')]);
            }

            $friendship->delete();
        });
    }

    public function remove(User $user, User $friend): void
    {
        DB::transaction(function () use ($user, $friend) {
            $friendship = $this->requiredBetween($user, $friend);
            $this->ensureStatus($friendship, FriendshipStatus::Accepted);
            $friendship->delete();
        });
    }

    public function block(User $user, User $blockedUser): Friendship
    {
        return DB::transaction(function () use ($user, $blockedUser) {
            $this->ensureDifferentUsers($user, $blockedUser);
            $friendship = $this->friendships->between($user->id, $blockedUser->id);

            if (! $friendship) {
                $friendship = $this->friendships->createRequest($user, $blockedUser);
            }

            $friendship->update([
                'requester_id' => $user->id,
                'addressee_id' => $blockedUser->id,
                'blocked_by_id' => $user->id,
                'status' => FriendshipStatus::Blocked,
            ]);
            $this->notifications->sendToUser(
                $blockedUser,
                'User blocked',
                ($user->full_name ?: $user->username ?: 'Someone') . ' blocked you',
                [
                    'type' => 'friend.blocked',
                    'friendship_id' => $friendship->id,
                    'user_id' => $user->id,
                ]
            );

            return $friendship->refresh();
        });
    }

    public function unblock(User $user, User $blockedUser): void
    {
        DB::transaction(function () use ($user, $blockedUser) {
            $friendship = $this->requiredBetween($user, $blockedUser);
            $this->ensureStatus($friendship, FriendshipStatus::Blocked);

            if ($friendship->blocked_by_id !== $user->id) {
                throw ValidationException::withMessages(['user_id' => __('messages.friendship_action_not_allowed')]);
            }

            $friendship->delete();
        });
    }

    public function friends(User $user, int $perPage): LengthAwarePaginator
    {
        return $this->friendships->friends($user, $perPage);
    }

    public function requests(User $user, int $perPage): array
    {
        return [
            'received' => $this->friendships->receivedPendingRequests($user, $perPage),
            'sent' => $this->friendships->sentPendingRequests($user, $perPage),
        ];
    }

    public function blockedUsers(User $user, int $perPage): LengthAwarePaginator
    {
        return $this->friendships->blockedBy($user, $perPage);
    }

    public function areFriends(User $firstUser, User $secondUser): bool
    {
        return (bool) $this->friendships->acceptedBetween($firstUser, $secondUser);
    }

    private function requiredBetween(User $firstUser, User $secondUser): Friendship
    {
        $friendship = $this->friendships->between($firstUser->id, $secondUser->id);

        if (! $friendship) {
            throw ValidationException::withMessages(['user_id' => __('messages.friendship_not_found')]);
        }

        return $friendship;
    }

    private function ensureDifferentUsers(User $firstUser, User $secondUser): void
    {
        if ($firstUser->id === $secondUser->id) {
            throw ValidationException::withMessages(['user_id' => __('messages.friendship_self_request')]);
        }
    }

    private function ensureStatus(Friendship $friendship, FriendshipStatus $status): void
    {
        if ($friendship->status !== $status) {
            throw ValidationException::withMessages(['user_id' => __('messages.friendship_invalid_status')]);
        }
    }
}
