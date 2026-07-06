<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Uranus Admin Login</title>
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
</head>
<body class="auth-body">
    <main class="auth-card">
        <div class="brand auth-brand">
            <span class="brand-mark">U</span>
            <span>
                <strong>Uranus</strong>
                <small>Control Deck</small>
            </span>
        </div>

        <h1>Admin sign in</h1>
        <p>Manage users, friendships, conversations, and operational health.</p>

        @include('admin.partials.feedback')

        <form method="POST" action="{{ route('admin.login.store') }}" class="form-grid">
            @csrf
            <label>
                Email
                <input type="email" name="email" value="{{ old('email') }}" required autofocus>
            </label>
            <label>
                Password
                <input type="password" name="password" required>
            </label>
            <label class="check-row">
                <input type="checkbox" name="remember" value="1">
                Remember this device
            </label>
            <button class="primary-button" type="submit">Enter dashboard</button>
        </form>
    </main>
</body>
</html>
