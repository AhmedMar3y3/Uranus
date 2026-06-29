<?php

namespace App\Features\Users\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'profile_image' => $this->image_path ? Storage::disk('public')->url($this->image_path) : null,
            'username' => $this->username,
            'full_name' => $this->full_name,
            'bio' => $this->bio,
            'gender' => $this->gender?->value,
            'friends_count' => $this->friends_count ?? 0,
            'mutual_friends_count' => $this->mutual_friends_count ?? 0,
            'online' => (bool) $this->is_online,
            'last_seen' => $this->last_seen_at,
            'friendship_status' => $this->friendship_status,
            'completed_profile' => (bool) $this->completed_profile,
        ];
    }
}
