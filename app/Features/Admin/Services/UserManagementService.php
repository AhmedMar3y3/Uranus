<?php

namespace App\Features\Admin\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserManagementService
{
    public function paginate(?string $search, ?string $profileStatus): LengthAwarePaginator
    {
        return User::query()
            ->withCount(['sentFriendships', 'receivedFriendships', 'sentMessages', 'devices'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('email', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('full_name', 'like', "%{$search}%");
                });
            })
            ->when($profileStatus === 'complete', fn ($query) => $query->where('completed_profile', true))
            ->when($profileStatus === 'incomplete', fn ($query) => $query->where('completed_profile', false))
            ->latest()
            ->paginate(12);
    }

    public function update(User $user, array $data): void
    {
        $user->update([
            'email' => $data['email'],
            'username' => $data['username'] ?? null,
            'full_name' => $data['full_name'] ?? null,
            'gender' => $data['gender'] ?? null,
            'bio' => $data['bio'] ?? null,
            'completed_profile' => (bool) ($data['completed_profile'] ?? false),
            'is_online' => (bool) ($data['is_online'] ?? false),
        ]);
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}
