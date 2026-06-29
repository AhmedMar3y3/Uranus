<?php

namespace App\Features\Users\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository
{
    public function search(?string $query, int $perPage): LengthAwarePaginator
    {
        return User::query()
            ->where('completed_profile', true)
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
