<div class="modal" id="edit-friendship-{{ $friendship->id }}" aria-hidden="true">
    <div class="modal-card">
        <header>
            <h2>Edit friendship</h2>
            <button type="button" data-modal-close aria-label="Close">&times;</button>
        </header>
        <form method="POST" action="{{ route('admin.friendships.update', $friendship) }}" class="form-grid">
            @csrf
            @method('PUT')
            <label>Status
                <select name="status" data-friendship-status>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->value }}" @selected($friendship->status === $status)>{{ $status->value }}</option>
                    @endforeach
                </select>
            </label>
            <label>Blocked by
                <select name="blocked_by_id">
                    <option value="">None</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @selected($friendship->blocked_by_id === $user->id)>{{ $user->full_name ?? $user->email }}</option>
                    @endforeach
                </select>
            </label>
            <button class="primary-button" type="submit">Save friendship</button>
        </form>
    </div>
</div>
