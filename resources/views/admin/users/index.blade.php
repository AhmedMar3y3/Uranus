<x-admin.layouts.app title="Users | Uranus" heading="Users">
    <section class="panel">
        <div class="panel-header">
            <div>
                <h2>Mobile users</h2>
                <p>Profile, presence, friendship, message, and device counts.</p>
            </div>
        </div>

        <form class="filter-bar" method="GET" action="{{ route('admin.users.index') }}">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search users">
            <select name="profile">
                <option value="">All profiles</option>
                <option value="complete" @selected(request('profile') === 'complete')>Complete</option>
                <option value="incomplete" @selected(request('profile') === 'incomplete')>Incomplete</option>
            </select>
            <button class="ghost-button" type="submit">Filter</button>
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Profile</th>
                        <th>Presence</th>
                        <th>Graph</th>
                        <th class="actions-cell">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>
                                <strong>{{ $user->full_name ?? 'Unnamed user' }}</strong>
                                <small>{{ $user->username ? '@'.$user->username : 'No username' }}</small>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td><span class="status-pill {{ $user->completed_profile ? 'accepted' : 'pending' }}">{{ $user->completed_profile ? 'complete' : 'incomplete' }}</span></td>
                            <td>{{ $user->is_online ? 'Online' : 'Offline' }}<small>{{ $user->last_seen_at?->diffForHumans() }}</small></td>
                            <td>
                                <small>{{ $user->sent_friendships_count + $user->received_friendships_count }} friendships</small>
                                <small>{{ $user->sent_messages_count }} sent records</small>
                                <small>{{ $user->devices_count }} devices</small>
                            </td>
                            <td class="actions-cell">
                                <button class="ghost-button small" type="button" data-modal-target="edit-user-{{ $user->id }}">Edit</button>
                                <button class="danger-button small" type="button" data-modal-target="delete-user-{{ $user->id }}">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty-state">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $users->links() }}
    </section>

    @foreach ($users as $user)
        @include('admin.users.partials.edit', ['user' => $user, 'genders' => $genders])
        @include('admin.users.partials.delete', ['user' => $user])
    @endforeach
</x-admin.layouts.app>
