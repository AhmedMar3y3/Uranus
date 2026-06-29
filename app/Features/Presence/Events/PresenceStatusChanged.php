<?php

namespace App\Features\Presence\Events;

use App\Models\User;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PresenceStatusChanged implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public function __construct(public readonly User $user)
    {
    }

    public function broadcastOn(): PresenceChannel
    {
        return new PresenceChannel('online');
    }

    public function broadcastAs(): string
    {
        return 'presence.changed';
    }

    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->user->id,
            'online' => (bool) $this->user->is_online,
            'last_seen' => $this->user->last_seen_at,
        ];
    }
}
