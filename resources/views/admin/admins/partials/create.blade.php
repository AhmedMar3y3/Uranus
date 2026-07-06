<div class="modal" id="create-admin-modal" aria-hidden="true">
    <div class="modal-card">
        <header>
            <h2>Create admin</h2>
            <button type="button" data-modal-close aria-label="Close">&times;</button>
        </header>
        <form method="POST" action="{{ route('admin.admins.store') }}" class="form-grid">
            @csrf
            <label>Name <input name="name" required></label>
            <label>Email <input type="email" name="email" required></label>
            <label>Password <input type="password" name="password" required></label>
            <label>Confirm password <input type="password" name="password_confirmation" required></label>
            <label class="check-row"><input type="checkbox" name="is_active" value="1" checked> Active</label>
            <button class="primary-button" type="submit">Create admin</button>
        </form>
    </div>
</div>
