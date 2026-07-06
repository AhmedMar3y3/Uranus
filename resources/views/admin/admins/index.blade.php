<x-admin.layouts.app title="Admins | Uranus" heading="Admins">
    <section class="panel">
        <div class="panel-header">
            <div>
                <h2>Admin accounts</h2>
                <p>Separate dashboard operators for Uranus.</p>
            </div>
            <button class="primary-button" type="button" data-modal-target="create-admin-modal">New admin</button>
        </div>

        <form class="filter-bar" method="GET" action="{{ route('admin.admins.index') }}">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search name or email">
            <button class="ghost-button" type="submit">Filter</button>
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Last login</th>
                        <th class="actions-cell">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($admins as $admin)
                        <tr>
                            <td>{{ $admin->name }}</td>
                            <td>{{ $admin->email }}</td>
                            <td><span class="status-pill {{ $admin->is_active ? 'accepted' : 'rejected' }}">{{ $admin->is_active ? 'active' : 'disabled' }}</span></td>
                            <td>{{ $admin->last_login_at?->diffForHumans() ?? 'Never' }}</td>
                            <td class="actions-cell">
                                <button class="ghost-button small" type="button" data-modal-target="edit-admin-{{ $admin->id }}">Edit</button>
                                <button class="danger-button small" type="button" data-modal-target="delete-admin-{{ $admin->id }}">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty-state">No admins found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $admins->links() }}
    </section>

    @include('admin.admins.partials.create')
    @foreach ($admins as $admin)
        @include('admin.admins.partials.edit', ['admin' => $admin])
        @include('admin.admins.partials.delete', ['admin' => $admin])
    @endforeach
</x-admin.layouts.app>
