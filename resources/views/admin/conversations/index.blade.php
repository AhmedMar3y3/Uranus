<x-admin.layouts.app title="Conversations | Uranus" heading="Conversations">
    <section class="panel">
        <div class="panel-header">
            <div>
                <h2>Conversation records</h2>
                <p>Participants and metadata only. Message content is intentionally hidden.</p>
            </div>
        </div>

        <form class="filter-bar" method="GET" action="{{ route('admin.conversations.index') }}">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search participant">
            <button class="ghost-button" type="submit">Filter</button>
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Participants</th>
                        <th>Records</th>
                        <th>Latest activity</th>
                        <th>Created</th>
                        <th class="actions-cell">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($conversations as $conversation)
                        <tr>
                            <td>
                                <strong>{{ $conversation->userOne->full_name ?? $conversation->userOne->email }}</strong>
                                <small>with {{ $conversation->userTwo->full_name ?? $conversation->userTwo->email }}</small>
                            </td>
                            <td>{{ $conversation->messages_count }}</td>
                            <td>{{ $conversation->latest_message_at?->diffForHumans() ?? 'No messages' }}</td>
                            <td>{{ $conversation->created_at?->format('M d, Y') }}</td>
                            <td class="actions-cell">
                                <a class="ghost-button small" href="{{ route('admin.conversations.show', $conversation) }}">View metadata</a>
                                <button class="danger-button small" type="button" data-modal-target="delete-conversation-{{ $conversation->id }}">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty-state">No conversations found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $conversations->links() }}
    </section>

    @foreach ($conversations as $conversation)
        @include('admin.conversations.partials.delete', ['conversation' => $conversation])
    @endforeach
</x-admin.layouts.app>
