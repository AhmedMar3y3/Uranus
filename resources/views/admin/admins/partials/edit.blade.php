<div class="modal" id="edit-admin-{{ $admin->id }}" aria-hidden="true">
    <div class="modal-card">
        <header>
            <h2>Edit admin</h2>
            <button type="button" data-modal-close aria-label="Close">&times;</button>
        </header>
        <form method="POST" action="{{ route('admin.admins.update', $admin) }}" class="form-grid">
            @csrf
            @method('PUT')
            <label>Name <input name="name" value="{{ $admin->name }}" required></label>
            <label>Email <input type="email" name="email" value="{{ $admin->email }}" required></label>
            <label>New password <input type="password" name="password" placeholder="Leave blank to keep current"></label>
            <label>Confirm password <input type="password" name="password_confirmation"></label>
            <label class="check-row"><input type="checkbox" name="is_active" value="1" @checked($admin->is_active)> Active</label>
            <button class="primary-button" type="submit">Save changes</button>
        </form>
    </div>
</div>
