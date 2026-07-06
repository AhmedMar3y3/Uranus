<x-admin.layouts.app title="Friendships | Uranus" heading="Friendships">
    <section class="panel">
        <div class="panel-header">
            <div>
                <h2>Friendship graph</h2>
                <p>Review request state, approvals, rejections, and blocks.</p>
            </div>
        </div>

        <form class="filter-bar" method="GET" action="{{ route('admin.friendships.index') }}">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search users">
            <select name="status">
                <option value="">All statuses</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->value }}</option>
                @endforeach
            </select>
            <button class="ghost-button" type="submit">Filter</button>
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Requester</th>
                        <th>Addressee</th>
                        <th>Status</th>
                        <th>Blocked by</th>
                        <th>Updated</th>
                        <th class="actions-cell">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($friendships as $friendship)
                        <tr>
                            <td>{{ $friendship->requester->full_name ?? $friendship->requester->email }}</td>
                            <td>{{ $friendship->addressee->full_name ?? $friendship->addressee->email }}</td>
                            <td><span class="status-pill {{ $friendship->status->value }}">{{ $friendship->status->value }}</span></td>
                            <td>{{ $friendship->blockedBy?->full_name ?? $friendship->blockedBy?->email ?? 'None' }}</td>
                            <td>{{ $friendship->updated_at?->diffForHumans() }}</td>
                            <td class="actions-cell">
                                <button class="ghost-button small" type="button" data-modal-target="edit-friendship-{{ $friendship->id }}">Edit</button>
                                <button class="danger-button small" type="button" data-modal-target="delete-friendship-{{ $friendship->id }}">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty-state">No friendships found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $friendships->links() }}
    </section>

    @foreach ($friendships as $friendship)
        @include('admin.friendships.partials.edit', ['friendship' => $friendship, 'statuses' => $statuses, 'users' => $users])
        @include('admin.friendships.partials.delete', ['friendship' => $friendship])
    @endforeach
</x-admin.layouts.app>
