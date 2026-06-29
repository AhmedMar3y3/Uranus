<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\FriendshipStatus;
use App\Enums\Gender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'username',
        'full_name',
        'gender',
        'bio',
        'image_path',
        'is_online',
        'last_seen_at',
        'email_login_otp_hash',
        'email_login_otp_expires_at',
        'completed_profile',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_login_otp_hash',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'email_login_otp_expires_at' => 'datetime',
        'completed_profile' => 'boolean',
        'is_online' => 'boolean',
        'last_seen_at' => 'datetime',
        'gender' => Gender::class,
    ];

    public function sentFriendships(): HasMany
    {
        return $this->hasMany(Friendship::class, 'requester_id');
    }

    public function receivedFriendships(): HasMany
    {
        return $this->hasMany(Friendship::class, 'addressee_id');
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function friends()
    {
        $acceptedUserIds = Friendship::query()
            ->where('status', FriendshipStatus::Accepted)
            ->where(function ($query) {
                $query->where('requester_id', $this->id)
                    ->orWhere('addressee_id', $this->id);
            })
            ->get()
            ->map(fn (Friendship $friendship) => $friendship->requester_id === $this->id ? $friendship->addressee_id : $friendship->requester_id);

        return static::query()->whereIn('id', $acceptedUserIds);
    }
}
