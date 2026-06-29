<?php

namespace App\Features\Friends\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BlockedUserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $blockedUser = $this->requester_id === $request->user()->id ? $this->addressee : $this->requester;

        return [
            'id' => $blockedUser->id,
            'username' => $blockedUser->username,
            'full_name' => $blockedUser->full_name,
            'image' => $blockedUser->image_path ? Storage::disk('public')->url($blockedUser->image_path) : null,
            'online' => (bool) $blockedUser->is_online,
            'last_seen' => $blockedUser->last_seen_at,
            'blocked_at' => $this->updated_at,
        ];
    }
}
