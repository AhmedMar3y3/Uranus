<?php

namespace App\Features\Profile\Services;

use App\Features\Profile\Repositories\ProfileRepository;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    public function __construct(private readonly ProfileRepository $profiles)
    {
    }

    public function complete(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            if (($data['image'] ?? null) instanceof UploadedFile) {
                if ($user->image_path) {
                    Storage::disk('public')->delete($user->image_path);
                }

                $data['image_path'] = $data['image']->store('users/images', 'public');
            }

            unset($data['image']);

            return $this->profiles->update($user, $data);
        });
    }
}
