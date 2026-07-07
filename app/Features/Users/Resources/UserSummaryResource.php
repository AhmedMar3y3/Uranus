<?php

namespace App\Features\Users\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserSummaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'full_name' => $this->full_name,
            'image' => $this->image_path ? Storage::disk('public')->url($this->image_path) : null,
            'public_key' => $this->public_key,
            'key_id' => 'default',
            'online' => (bool) $this->is_online,
            'last_seen' => $this->last_seen_at,
        ];
    }
}
