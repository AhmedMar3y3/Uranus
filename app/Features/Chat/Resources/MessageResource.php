<?php

namespace App\Features\Chat\Resources;

use App\Features\Users\Resources\UserSummaryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'conversation_id' => $this->conversation_id,
            'sender' => new UserSummaryResource($this->whenLoaded('sender', $this->sender)),
            'type' => $this->type?->value,
            'body' => $this->body,
            'attachment' => $this->attachment_path ? [
                'url' => Storage::disk('public')->url($this->attachment_path),
                'name' => $this->attachment_name,
                'mime' => $this->attachment_mime,
                'size' => $this->attachment_size,
                'duration_seconds' => $this->attachment_duration_seconds,
            ] : null,
            'reply_to' => $this->reply_to_message_id ? new self($this->whenLoaded('replyTo', $this->replyTo)) : null,
            'delivered_at' => $this->delivered_at,
            'seen_at' => $this->seen_at,
            'edited_at' => $this->edited_at,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
        ];
    }
}
