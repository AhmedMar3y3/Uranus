<?php

namespace App\Features\Auth\Services;

use App\Features\Auth\Mail\LoginOtpMail;
use App\Features\Auth\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(private readonly UserRepository $users)
    {
    }

    public function sendOtp(string $email): void
    {
        $plainOtp = (string) random_int(100000, 999999);

        DB::transaction(function () use ($email, $plainOtp) {
            $user = $this->users->findOrCreateByEmail($email);

            $this->users->updateOtp(
                $user,
                Hash::make($plainOtp),
                now()->addMinutes(5)
            );

            Mail::to($user->email)->send(new LoginOtpMail($plainOtp));
        });
    }

    public function verifyOtp(string $email, string $otp): array
    {
        return DB::transaction(function () use ($email, $otp) {
            $user = $this->users->findByEmail($email);

            if (! $this->isValidOtp($user, $otp)) {
                throw ValidationException::withMessages([
                    'otp' => __('messages.invalid_or_expired_otp'),
                ]);
            }

            $user = $this->users->clearOtp($user);
            $token = $user->createToken('uranus-api')->plainTextToken;

            return [
                'token' => $token,
                'completed_profile' => (bool) $user->completed_profile,
            ];
        });
    }

    private function isValidOtp(?User $user, string $otp): bool
    {
        if (! $user || ! $user->email_login_otp_hash || ! $user->email_login_otp_expires_at) {
            return false;
        }

        if ($user->email_login_otp_expires_at->isPast()) {
            return false;
        }

        return Hash::check($otp, $user->email_login_otp_hash);
    }
}
