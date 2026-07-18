<?php

namespace App\Features\Admin\Services;

use App\Models\User;
use App\Models\Admin;
use App\Models\Message;
use App\Models\WebEvent;
use App\Models\Friendship;
use App\Models\ApkRelease;
use App\Models\Conversation;
use App\Enums\FriendshipStatus;
use App\Features\Settings\Services\AppVersionService;

class DashboardService
{
    public function __construct(private readonly AppVersionService $appVersions)
    {
    }

    public function overview(): array
    {
        $analytics = $this->analyticsOverview();

        return [
            'stats'                  => [
                'admins'              => Admin::count(),
                'users'               => User::count(),
                'online_users'        => User::where('is_online', true)->count(),
                'completed_profiles'  => User::where('completed_profile', true)->count(),
                'conversations'       => Conversation::count(),
                'messages'            => Message::count(),
                'friendships'         => Friendship::count(),
                'pending_friendships' => Friendship::where('status', FriendshipStatus::Pending)->count(),
                'web_visits'          => $analytics['web_visits'],
                'unique_visitors'     => $analytics['unique_visitors'],
                'downloads'           => $analytics['downloads'],
                'downloads_today'     => $analytics['downloads_today'],
            ],
            'analyticsTrend'         => $analytics['trend'],
            'activeRelease'          => ApkRelease::query()->where('is_active', true)->orderByDesc('id')->first(),
            'appSettings'            => $this->appVersions->settings(),
            'friendshipStatusCounts' => Friendship::query()
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status'),
            'recentUsers'            => User::latest()->limit(6)->get(),
            'recentConversations'    => Conversation::query()
                ->with(['userOne:id,full_name,username,email', 'userTwo:id,full_name,username,email'])
                ->withCount('messages')
                ->latest('latest_message_at')
                ->limit(6)
                ->get(),
        ];
    }

    private function analyticsOverview(): array
    {
        $start  = today()->subDays(6);
        $events = WebEvent::query()
            ->where('occurred_at', '>=', $start)
            ->get(['type', 'occurred_at']);

        $trend = collect(range(0, 6))->map(function (int $offset) use ($start, $events): array {
            $date        = $start->copy()->addDays($offset);
            $dailyEvents = $events->filter(
                fn(WebEvent $event): bool => $event->occurred_at->isSameDay($date)
            );

            return [
                'label'     => $date->format('D'),
                'date'      => $date->format('M j'),
                'visits'    => $dailyEvents->where('type', WebEvent::TYPE_VISIT)->count(),
                'downloads' => $dailyEvents->where('type', WebEvent::TYPE_DOWNLOAD)->count(),
            ];
        });

        return [
            'web_visits'      => WebEvent::query()->where('type', WebEvent::TYPE_VISIT)->count(),
            'unique_visitors' => WebEvent::query()
                ->where('type', WebEvent::TYPE_VISIT)
                ->distinct()
                ->count('visitor_hash'),
            'downloads'       => WebEvent::query()->where('type', WebEvent::TYPE_DOWNLOAD)->count(),
            'downloads_today' => WebEvent::query()
                ->where('type', WebEvent::TYPE_DOWNLOAD)
                ->where('occurred_at', '>=', today())
                ->count(),
            'trend'           => $trend,
        ];
    }
}
