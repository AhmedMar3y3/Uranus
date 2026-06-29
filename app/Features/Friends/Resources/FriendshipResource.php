<?php

namespace App\Features\Friends\Resources;

use App\Features\Users\Resources\UserSummaryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FriendshipResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status?->value,
            'requester' => new UserSummaryResource($this->whenLoaded('requester', $this->requester)),
            'addressee' => new UserSummaryResource($this->whenLoaded('addressee', $this->addressee)),
            'blocked_by_id' => $this->blocked_by_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
