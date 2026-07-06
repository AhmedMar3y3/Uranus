<div class="modal" id="delete-admin-{{ $admin->id }}" aria-hidden="true">
    <div class="modal-card compact">
        <header>
            <h2>Delete admin?</h2>
            <button type="button" data-modal-close aria-label="Close">&times;</button>
        </header>
        <p>This removes dashboard access for <strong>{{ $admin->email }}</strong>.</p>
        <form method="POST" action="{{ route('admin.admins.destroy', $admin) }}" class="modal-actions">
            @csrf
            @method('DELETE')
            <button class="ghost-button" type="button" data-modal-close>Cancel</button>
            <button class="danger-button" type="submit">Delete</button>
        </form>
    </div>
</div>
