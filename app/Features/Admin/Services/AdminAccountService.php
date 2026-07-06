<?php

namespace App\Features\Admin\Services;

use App\Models\Admin;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class AdminAccountService
{
    public function paginate(?string $search): LengthAwarePaginator
    {
        return Admin::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12);
    }

    public function create(array $data): Admin
    {
        return Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);
    }

    public function update(Admin $admin, array $data): void
    {
        $admin->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'is_active' => (bool) ($data['is_active'] ?? false),
        ]);

        if (! empty($data['password'])) {
            $admin->password = Hash::make($data['password']);
        }

        $admin->save();
    }

    public function delete(Admin $admin): void
    {
        $admin->delete();
    }
}
