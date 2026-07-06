<x-admin.layouts.app title="Conversation Metadata | Uranus" heading="Conversation metadata">
    <section class="panel">
        <div class="panel-header">
            <div>
                <h2>{{ $conversation->userOne->full_name ?? $conversation->userOne->email }} and {{ $conversation->userTwo->full_name ?? $conversation->userTwo->email }}</h2>
                <p>No message bodies, attachment paths, or attachment names are shown here.</p>
            </div>
            <a class="ghost-button" href="{{ route('admin.conversations.index') }}">Back</a>
        </div>

        <section class="stat-grid">
            <article class="stat-card"><span>Total records</span><strong>{{ number_format($messageMeta['total']) }}</strong></article>
            <article class="stat-card"><span>Delivered</span><strong>{{ number_format($messageMeta['delivered']) }}</strong></article>
            <article class="stat-card"><span>Seen</span><strong>{{ number_format($messageMeta['seen']) }}</strong></article>
            <article class="stat-card"><span>Edited</span><strong>{{ number_format($messageMeta['edited']) }}</strong></article>
        </section>

        <div class="content-grid two-columns">
            <div class="panel inset">
                <h3>Participants</h3>
                <div class="mini-list plain">
                    @foreach ([$conversation->userOne, $conversation->userTwo] as $participant)
                        <div>
                            <strong>{{ $participant->full_name ?? 'Unnamed user' }}</strong>
                            <span>{{ $participant->email }}</span>
                            <small>{{ $participant->is_online ? 'Online' : 'Offline' }}</small>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="panel inset">
                <h3>Message type mix</h3>
                <div class="status-list">
                    @forelse ($messageMeta['by_type'] as $type => $count)
                        <div>
                            <span class="status-pill">{{ $type }}</span>
                            <strong>{{ number_format($count) }}</strong>
                        </div>
                    @empty
                        <p class="empty-state">No message metadata yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        @include('admin.conversations.partials.delete', ['conversation' => $conversation])
        <button class="danger-button" type="button" data-modal-target="delete-conversation-{{ $conversation->id }}">Delete conversation</button>
    </section>
</x-admin.layouts.app>
