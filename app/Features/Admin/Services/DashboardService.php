<?php

namespace App\Features\Admin\Services;

use App\Enums\FriendshipStatus;
use App\Models\Admin;
use App\Models\Conversation;
use App\Models\Friendship;
use App\Models\Message;
use App\Models\User;

class DashboardService
{
    public function overview(): array
    {
        return [
            'stats' => [
                'admins' => Admin::count(),
                'users' => User::count(),
                'online_users' => User::where('is_online', true)->count(),
                'completed_profiles' => User::where('completed_profile', true)->count(),
                'conversations' => Conversation::count(),
                'messages' => Message::count(),
                'friendships' => Friendship::count(),
                'pending_friendships' => Friendship::where('status', FriendshipStatus::Pending)->count(),
            ],
            'friendshipStatusCounts' => Friendship::query()
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status'),
            'recentUsers' => User::latest()->limit(6)->get(),
            'recentConversations' => Conversation::query()
                ->with(['userOne:id,full_name,username,email', 'userTwo:id,full_name,username,email'])
                ->withCount('messages')
                ->latest('latest_message_at')
                ->limit(6)
                ->get(),
        ];
    }
}
