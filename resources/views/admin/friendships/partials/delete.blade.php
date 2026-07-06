<div class="modal" id="delete-friendship-{{ $friendship->id }}" aria-hidden="true">
    <div class="modal-card compact">
        <header>
            <h2>Delete friendship?</h2>
            <button type="button" data-modal-close aria-label="Close">&times;</button>
        </header>
        <p>This removes the relationship record between these users.</p>
        <form method="POST" action="{{ route('admin.friendships.destroy', $friendship) }}" class="modal-actions">
            @csrf
            @method('DELETE')
            <button class="ghost-button" type="button" data-modal-close>Cancel</button>
            <button class="danger-button" type="submit">Delete</button>
        </form>
    </div>
</div>
