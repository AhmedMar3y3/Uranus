<div class="modal" id="edit-user-{{ $user->id }}" aria-hidden="true">
    <div class="modal-card wide">
        <header>
            <h2>Edit user</h2>
            <button type="button" data-modal-close aria-label="Close">&times;</button>
        </header>
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="form-grid two">
            @csrf
            @method('PUT')
            <label>Email <input type="email" name="email" value="{{ $user->email }}" required></label>
            <label>Username <input name="username" value="{{ $user->username }}"></label>
            <label>Full name <input name="full_name" value="{{ $user->full_name }}"></label>
            <label>Gender
                <select name="gender">
                    <option value="">Not set</option>
                    @foreach ($genders as $gender)
                        <option value="{{ $gender->value }}" @selected($user->gender?->value === $gender->value)>{{ $gender->value }}</option>
                    @endforeach
                </select>
            </label>
            <label class="span-two">Bio <textarea name="bio" rows="4">{{ $user->bio }}</textarea></label>
            <label class="check-row"><input type="checkbox" name="completed_profile" value="1" @checked($user->completed_profile)> Completed profile</label>
            <label class="check-row"><input type="checkbox" name="is_online" value="1" @checked($user->is_online)> Online</label>
            <button class="primary-button span-two" type="submit">Save user</button>
        </form>
    </div>
</div>
