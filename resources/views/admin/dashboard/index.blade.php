<x-admin.layouts.app title="Uranus Dashboard" heading="Overview">
    <section class="hero-panel dashboard-hero">
        <div>
            <p class="eyebrow">Live control center</p>
            <h2>Everything in orbit.</h2>
            <p>Monitor your audience, publish Android releases, and keep an eye on the Uranus community.</p>
            <div class="hero-links">
                <a class="primary-button" href="#apk-manager">Publish new APK</a>
                <a class="ghost-button" href="{{ route('download') }}" target="_blank" rel="noreferrer">Open download
                    page</a>
            </div>
        </div>
        <div class="orbit-badge">
            <span>{{ number_format($stats['online_users']) }}</span>
            <small>online now</small>
        </div>
    </section>

    <section class="section-heading">
        <div>
            <p class="eyebrow">Audience</p>
            <h2>Website performance</h2>
        </div>
        <span class="live-indicator"><i></i> Tracking live</span>
    </section>

    <section class="stat-grid analytics-stat-grid">
        @foreach ([['label' => 'Website visits', 'value' => $stats['web_visits'], 'note' => 'All page views', 'tone' => 'cyan'], ['label' => 'Unique visitors', 'value' => $stats['unique_visitors'], 'note' => 'Privacy-safe count', 'tone' => 'violet'], ['label' => 'APK downloads', 'value' => $stats['downloads'], 'note' => $stats['downloads_today'] . ' today', 'tone' => 'green']] as $item)
            <article class="stat-card metric-card {{ $item['tone'] }}">
                <span>{{ $item['label'] }}</span>
                <strong>{{ number_format($item['value']) }}</strong>
                <small>{{ $item['note'] }}</small>
            </article>
        @endforeach
    </section>

    @php
        $chartMax = max(1, $analyticsTrend->max(fn($day) => max($day['visits'], $day['downloads'])));
    @endphp
    <section class="panel analytics-panel">
        <div class="panel-header">
            <div>
                <p class="eyebrow">Last 7 days</p>
                <h2>Traffic pulse</h2>
            </div>
            <div class="chart-legend"><span class="visits">Visits</span><span class="downloads">Downloads</span></div>
        </div>
        <div class="bar-chart" aria-label="Website visits and APK downloads over the last seven days">
            @foreach ($analyticsTrend as $day)
                <div class="chart-day"
                    title="{{ $day['date'] }}: {{ $day['visits'] }} visits, {{ $day['downloads'] }} downloads">
                    <div class="chart-bars">
                        <i class="visit-bar"
                            style="--bar-height: {{ max(4, round(($day['visits'] / $chartMax) * 100)) }}%"></i>
                        <i class="download-bar"
                            style="--bar-height: {{ max(4, round(($day['downloads'] / $chartMax) * 100)) }}%"></i>
                    </div>
                    <span>{{ $day['label'] }}</span>
                </div>
            @endforeach
        </div>
    </section>

    <section id="apk-manager" class="content-grid apk-grid">
        <div class="panel release-panel">
            <div class="panel-header">
                <div>
                    <p class="eyebrow">Android release</p>
                    <h2>Current APK</h2>
                </div>
                <span class="status-pill accepted">Live</span>
            </div>

            @if ($activeRelease)
                <div class="release-summary">
                    <div class="file-icon">APK</div>
                    <div>
                        <strong>{{ $activeRelease->original_name }}</strong>
                        <p>
                            {{ number_format($activeRelease->file_size / 1024 / 1024, 1) }} MB
                            @if ($activeRelease->version)
                                &middot; Version {{ $activeRelease->version }}
                            @endif
                        </p>
                    </div>
                </div>
                <div class="release-facts">
                    <div><span>Downloads</span><strong>{{ number_format($activeRelease->downloads_count) }}</strong>
                    </div>
                    <div><span>Published</span><strong>{{ $activeRelease->created_at->format('M j, Y') }}</strong>
                    </div>
                </div>
                @if ($activeRelease->release_notes)
                    <p class="release-notes">{{ $activeRelease->release_notes }}</p>
                @endif
            @else
                <div class="legacy-release">
                    <div class="file-icon">APK</div>
                    <div>
                        <strong>Legacy static APK</strong>
                        <p>Upload a release to start versioned APK management.</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="panel upload-panel">
            <div class="panel-header">
                <div>
                    <p class="eyebrow">Replace application</p>
                    <h2>Publish a new APK</h2>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.apk-releases.store') }}" enctype="multipart/form-data"
                class="form-grid">
                @csrf
                <label class="file-drop">
                    <input type="file" name="apk" accept=".apk,application/vnd.android.package-archive" required
                        data-file-input>
                    <span class="file-drop-icon">↑</span>
                    <strong data-file-label>Choose an APK file</strong>
                    <small>Maximum file size: 500 MB</small>
                </label>
                <label>
                    Version <span class="optional">Optional</span>
                    <input type="text" name="version" value="{{ old('version') }}" placeholder="e.g. 1.4.0"
                        maxlength="100">
                </label>
                <label>
                    Release notes <span class="optional">Optional</span>
                    <textarea name="release_notes" rows="3" placeholder="What changed in this release?" maxlength="2000">{{ old('release_notes') }}</textarea>
                </label>
                <button class="primary-button" type="submit">Publish and replace current APK</button>
            </form>
        </div>
    </section>

    <section id="app-settings" class="panel app-settings-panel">
        <div class="panel-header">
            <div>
                <p class="eyebrow">Mobile app control</p>
                <h2>Mandatory update settings</h2>
                <p>Configure the version check used when the Android app starts.</p>
            </div>
            <span class="status-pill {{ $appSettings['android_force_update'] ? 'pending' : 'accepted' }}">
                {{ $appSettings['android_force_update'] ? 'Enforced' : 'Optional' }}
            </span>
        </div>

        <div class="settings-grid">
            <form method="POST" action="{{ route('admin.settings.update') }}" class="form-grid settings-form">
                @csrf
                @method('PUT')
                <div class="form-grid two">
                    <label>
                        Latest version name
                        <input type="text" name="android_latest_version"
                            value="{{ old('android_latest_version', $appSettings['android_latest_version']) }}"
                            placeholder="e.g. 1.4.0" maxlength="50" required>
                    </label>
                    <label>
                        Latest version code
                        <input type="number" name="android_latest_version_code"
                            value="{{ old('android_latest_version_code', $appSettings['android_latest_version_code'] ?: '') }}"
                            placeholder="e.g. 14" min="1" max="2147483647" required>
                    </label>
                </div>
                <label>
                    Update message
                    <textarea name="android_update_message" rows="3" maxlength="500"
                        placeholder="Tell users why they need to update.">{{ old('android_update_message', $appSettings['android_update_message']) }}</textarea>
                </label>
                <label class="setting-switch">
                    <input type="checkbox" name="android_force_update" value="1"
                        @checked(old('android_force_update', $appSettings['android_force_update']))>
                    <span class="switch-track"><i></i></span>
                    <span>
                        <strong>Require the update</strong>
                        <small>Users on a lower version code must update before continuing.</small>
                    </span>
                </label>
                <button class="primary-button" type="submit">Save mobile settings</button>
            </form>

            <aside class="api-contract">
                <span class="file-icon">API</span>
                <div>
                    <p class="eyebrow">App startup endpoint</p>
                    <strong>GET /api/app/version</strong>
                </div>
                <p>Send the installed Android <code>version_code</code>. When <code>must_update</code> is true, show a non-dismissible update popup and open the returned download URL.</p>
                <code class="endpoint-example">?version_code=13&amp;version=1.3.0</code>
            </aside>
        </div>
    </section>

    <section class="section-heading compact-heading">
        <div>
            <p class="eyebrow">Community</p>
            <h2>Platform activity</h2>
        </div>
    </section>
    <section class="stat-grid compact-stats">
        @foreach ([
        'Users' => $stats['users'],
        'Completed profiles' => $stats['completed_profiles'],
        'Conversations' => $stats['conversations'],
        'Messages' => $stats['messages'],
        'Friendships' => $stats['friendships'],
        'Pending requests' => $stats['pending_friendships'],
    ] as $label => $value)
            <article class="stat-card"><span>{{ $label }}</span><strong>{{ number_format($value) }}</strong>
            </article>
        @endforeach
    </section>

    <section class="content-grid two-columns">
        <div class="panel">
            <div class="panel-header">
                <h2>Friendship statuses</h2><a href="{{ route('admin.friendships.index') }}">View all</a>
            </div>
            <div class="status-list">
                @foreach (App\Enums\FriendshipStatus::cases() as $status)
                    <div><span
                            class="status-pill {{ $status->value }}">{{ $status->value }}</span><strong>{{ number_format($friendshipStatusCounts[$status->value] ?? 0) }}</strong>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="panel">
            <div class="panel-header">
                <h2>Recent conversations</h2><a href="{{ route('admin.conversations.index') }}">View all</a>
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
            <h2>Newest users</h2><a href="{{ route('admin.users.index') }}">View all</a>
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
                            <td>{{ $user->full_name ?? ($user->username ?? 'Unnamed user') }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span
                                    class="status-pill {{ $user->completed_profile ? 'accepted' : 'pending' }}">{{ $user->completed_profile ? 'complete' : 'incomplete' }}</span>
                            </td>
                            <td>{{ $user->is_online ? 'Online' : 'Offline' }}</td>
                            <td>{{ $user->created_at?->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-state">No users yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-admin.layouts.app>
