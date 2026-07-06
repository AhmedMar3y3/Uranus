<?php

namespace App\Features\Admin\Services;

use App\Enums\FriendshipStatus;
use App\Models\Friendship;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FriendshipManagementService
{
    public function paginate(?string $search, ?string $status): LengthAwarePaginator
    {
        return Friendship::query()
            ->with([
                'requester:id,email,username,full_name',
                'addressee:id,email,username,full_name',
                'blockedBy:id,email,username,full_name',
            ])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->whereHas('requester', function ($query) use ($search) {
                        $query->where('email', 'like', "%{$search}%")
                            ->orWhere('username', 'like', "%{$search}%")
                            ->orWhere('full_name', 'like', "%{$search}%");
                    })->orWhereHas('addressee', function ($query) use ($search) {
                        $query->where('email', 'like', "%{$search}%")
                            ->orWhere('username', 'like', "%{$search}%")
                            ->orWhere('full_name', 'like', "%{$search}%");
                    });
                });
            })
            ->latest()
            ->paginate(12);
    }

    public function update(Friendship $friendship, array $data): void
    {
        $status = FriendshipStatus::from($data['status']);

        $friendship->update([
            'status' => $status,
            'blocked_by_id' => $status === FriendshipStatus::Blocked ? ($data['blocked_by_id'] ?? null) : null,
        ]);
    }

    public function delete(Friendship $friendship): void
    {
        $friendship->delete();
    }
}
