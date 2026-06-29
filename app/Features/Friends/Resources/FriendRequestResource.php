<?php

namespace App\Features\Friends\Resources;

use App\Features\Users\Resources\UserSummaryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FriendRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'requester' => new UserSummaryResource($this->whenLoaded('requester', $this->requester)),
            'addressee' => new UserSummaryResource($this->whenLoaded('addressee', $this->addressee)),
            'status' => $this->status?->value,
            'created_at' => $this->created_at,
        ];
    }
}
