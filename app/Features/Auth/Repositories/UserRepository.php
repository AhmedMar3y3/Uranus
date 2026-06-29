<?php

namespace App\Features\Auth\Repositories;

use App\Models\User;

class UserRepository
{
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function findOrCreateByEmail(string $email): User
    {
        return User::firstOrCreate(['email' => $email]);
    }

    public function updateOtp(User $user, string $otpHash, \DateTimeInterface $expiresAt): User
    {
        $user->forceFill([
            'email_login_otp_hash' => $otpHash,
            'email_login_otp_expires_at' => $expiresAt,
        ])->save();

        return $user->refresh();
    }

    public function clearOtp(User $user): User
    {
        $user->forceFill([
            'email_login_otp_hash' => null,
            'email_login_otp_expires_at' => null,
            'email_verified_at' => $user->email_verified_at ?: now(),
        ])->save();

        return $user->refresh();
    }
}
