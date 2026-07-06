<x-admin.layouts.app title="Uranus Admin" heading="Home">
    <section class="hero-panel">
        <div>
            <p class="eyebrow">System overview</p>
            <h2>Uranus is online.</h2>
            <p>Operational controls for people, social graph state, and encrypted-ready conversations.</p>
        </div>
        <div class="orbit-badge">
            <span>{{ number_format($stats['online_users']) }}</span>
            <small>online users</small>
        </div>
    </section>

    <section class="stat-grid">
        @foreach ([
            'Admins' => $stats['admins'],
            'Users' => $stats['users'],
            'Completed profiles' => $stats['completed_profiles'],
            'Conversations' => $stats['conversations'],
            'Message records' => $stats['messages'],
            'Friendships' => $stats['friendships'],
            'Pending requests' => $stats['pending_friendships'],
        ] as $label => $value)
            <article class="stat-card">
                <span>{{ $label }}</span>
                <strong>{{ number_format($value) }}</strong>
            </article>
        @endforeach
    </section>

    <section class="content-grid two-columns">
        <div class="panel">
            <div class="panel-header">
                <h2>Friendship statuses</h2>
                <a href="{{ route('admin.friendships.index') }}">View all</a>
            </div>
            <div class="status-list">
                @foreach (App\Enums\FriendshipStatus::cases() as $status)
                    <div>
                        <span class="status-pill {{ $status->value }}">{{ $status->value }}</span>
                        <strong>{{ number_format($friendshipStatusCounts[$status->value] ?? 0) }}</strong>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="panel">
            <div class="panel-header">
                <h2>Recent conversations</h2>
                <a href="{{ route('admin.conversations.index') }}">View all</a>
            </div>
            <div class="mini-list">
                @forelse ($recentConversations as $conversation)
                    <a href="{{ route('admin.conversations.show', $conversation) }}">
                        <span>{{ $conversation->userOne->full_name ?? $conversation->userOne->email }}</span>
                        <span>{{ $conversation->userTwo->full_name ?? $conversation->userTwo->email }}</span>
                        <strong>{{ $conversation->messages_count }} records</strong>
                    </a>
                @empty
                    <p class="empty-state">No conversations yet.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="panel">
        <div class="panel-header">
            <h2>Newest users</h2>
            <a href="{{ route('admin.users.index') }}">View all</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Profile</th>
                        <th>Online</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentUsers as $user)
                        <tr>
                            <td>{{ $user->full_name ?? $user->username ?? 'Unnamed user' }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="status-pill {{ $user->completed_profile ? 'accepted' : 'pending' }}">{{ $user->completed_profile ? 'complete' : 'incomplete' }}</span></td>
                            <td>{{ $user->is_online ? 'Online' : 'Offline' }}</td>
                            <td>{{ $user->created_at?->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty-state">No users yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-admin.layouts.app>
