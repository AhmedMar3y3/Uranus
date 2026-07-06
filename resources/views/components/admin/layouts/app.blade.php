<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Uranus Admin' }}</title>
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
</head>
<body>
    <div class="admin-shell">
        <aside class="sidebar">
            <a class="brand" href="{{ route('admin.dashboard') }}">
                <span class="brand-mark">U</span>
                <span>
                    <strong>Uranus</strong>
                    <small>Control Deck</small>
                </span>
            </a>

            <nav class="nav-stack" aria-label="Dashboard navigation">
                <a href="{{ route('admin.dashboard') }}" @class(['active' => request()->routeIs('admin.dashboard')])>Home</a>
                <a href="{{ route('admin.admins.index') }}" @class(['active' => request()->routeIs('admin.admins.*')])>Admins</a>
                <a href="{{ route('admin.users.index') }}" @class(['active' => request()->routeIs('admin.users.*')])>Users</a>
                <a href="{{ route('admin.conversations.index') }}" @class(['active' => request()->routeIs('admin.conversations.*')])>Conversations</a>
                <a href="{{ route('admin.friendships.index') }}" @class(['active' => request()->routeIs('admin.friendships.*')])>Friendships</a>
            </nav>
        </aside>

        <main class="main-panel">
            <header class="topbar">
                <div>
                    <p class="eyebrow">Admin dashboard</p>
                    <h1>{{ $heading ?? 'Uranus' }}</h1>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button class="ghost-button" type="submit">Sign out</button>
                </form>
            </header>

            @include('admin.partials.feedback')

            {{ $slot }}
        </main>
    </div>
</body>
</html>
