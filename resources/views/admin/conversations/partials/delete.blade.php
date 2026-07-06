<div class="modal" id="delete-conversation-{{ $conversation->id }}" aria-hidden="true">
    <div class="modal-card compact">
        <header>
            <h2>Delete conversation?</h2>
            <button type="button" data-modal-close aria-label="Close">&times;</button>
        </header>
        <p>This removes the conversation and its message records. Message content is not shown in this dashboard.</p>
        <form method="POST" action="{{ route('admin.conversations.destroy', $conversation) }}" class="modal-actions">
            @csrf
            @method('DELETE')
            <button class="ghost-button" type="button" data-modal-close>Cancel</button>
            <button class="danger-button" type="submit">Delete</button>
        </form>
    </div>
</div>
