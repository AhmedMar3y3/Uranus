<?php

namespace App\Features\Admin\Services;

use App\Models\Conversation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ConversationManagementService
{
    public function paginate(?string $search): LengthAwarePaginator
    {
        return Conversation::query()
            ->with(['userOne:id,email,username,full_name,is_online', 'userTwo:id,email,username,full_name,is_online'])
            ->withCount('messages')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->whereHas('userOne', function ($query) use ($search) {
                        $query->where('email', 'like', "%{$search}%")
                            ->orWhere('username', 'like', "%{$search}%")
                            ->orWhere('full_name', 'like', "%{$search}%");
                    })->orWhereHas('userTwo', function ($query) use ($search) {
                        $query->where('email', 'like', "%{$search}%")
                            ->orWhere('username', 'like', "%{$search}%")
                            ->orWhere('full_name', 'like', "%{$search}%");
                    });
                });
            })
            ->latest('latest_message_at')
            ->paginate(12);
    }

    public function messageMeta(Conversation $conversation): array
    {
        $query = $conversation->messages();

        return [
            'total' => (clone $query)->count(),
            'delivered' => (clone $query)->whereNotNull('delivered_at')->count(),
            'seen' => (clone $query)->whereNotNull('seen_at')->count(),
            'edited' => (clone $query)->whereNotNull('edited_at')->count(),
            'by_type' => (clone $query)->selectRaw('type, count(*) as total')->groupBy('type')->pluck('total', 'type'),
        ];
    }

    public function delete(Conversation $conversation): void
    {
        $conversation->delete();
    }
}
