<div class="modal" id="delete-user-{{ $user->id }}" aria-hidden="true">
    <div class="modal-card compact">
        <header>
            <h2>Delete user?</h2>
            <button type="button" data-modal-close aria-label="Close">&times;</button>
        </header>
        <p>This removes <strong>{{ $user->email }}</strong> and cascades their friendships, conversations, messages, and devices.</p>
        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="modal-actions">
            @csrf
            @method('DELETE')
            <button class="ghost-button" type="button" data-modal-close>Cancel</button>
            <button class="danger-button" type="submit">Delete</button>
        </form>
    </div>
</div>
