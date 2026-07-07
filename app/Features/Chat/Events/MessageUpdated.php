<?php

namespace App\Features\Chat\Events;

use App\Features\Chat\Resources\MessageResource;
use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageUpdated implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public function __construct(public readonly Message $message, private readonly string $eventName)
    {
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('conversations.' . $this->message->conversation_id);
    }

    public function broadcastAs(): string
    {
        return $this->eventName;
    }

    public function broadcastWith(): array
    {
        return [
            'message' => (new MessageResource($this->message->loadMissing(['conversation', 'sender', 'replyTo.sender', 'replyTo.conversation'])))->toArray(request()),
        ];
    }
}
