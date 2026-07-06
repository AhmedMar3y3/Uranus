<?php

namespace App\Features\Users\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UserRepository
{
    public function search(?string $query, int $perPage, int $authUserId, Collection $excludedUserIds): LengthAwarePaginator
    {
        return User::query()
            ->where('completed_profile', true)
            ->where('id', '!=', $authUserId)
            ->when($excludedUserIds->isNotEmpty(), fn ($builder) => $builder->whereNotIn('id', $excludedUserIds))
            ->when($query, function ($builder) use ($query) {
                $builder->where(function ($inner) use ($query) {
                    $inner->where('username', 'like', "%{$query}%")
                        ->orWhere('full_name', 'like', "%{$query}%");
                });
            })
            ->orderBy('username')
            ->paginate($perPage);
    }

    public function findProfile(int $id): User
    {
        return User::query()->where('completed_profile', true)->findOrFail($id);
    }
}
