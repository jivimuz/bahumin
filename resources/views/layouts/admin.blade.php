<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex,nofollow">
    <title>@yield('title', 'Admin') · Bahumin</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="turbo-cache-control" content="no-cache">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,700&family=Source+Sans+3:wght@400;600;700&family=Fira+Code:wght@500;600&display=swap" rel="stylesheet">
</head>
<body>
    <a class="skip-link" href="#konten">Lewati ke konten</a>
    <header class="site-header">
        <div class="shell header-inner">
            <a href="{{ route('admin.dashboard') }}" class="brand">
                <span class="brand-name">BAHUMIN</span>
                <span class="brand-note">Konsol admin</span>
            </a>
            <nav class="desktop-nav" aria-label="Navigasi admin">
                <a href="{{ route('admin.dashboard') }}" @class(['active' => request()->routeIs('admin.dashboard')])>Ringkasan</a>
                <a href="{{ route('admin.users.index') }}" @class(['active' => request()->routeIs('admin.users.*')])>Users</a>
                <a href="{{ route('admin.jokes.index') }}" @class(['active' => request()->routeIs('admin.jokes.*')])>Jokes</a>
                <a href="{{ route('admin.categories.index') }}" @class(['active' => request()->routeIs('admin.categories.*')])>Kategori</a>
                <a href="{{ route('home') }}">Lihat situs</a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="nav-logout">Keluar</button>
                </form>
            </nav>
            <details class="mobile-nav">
                <summary>Menu admin</summary>
                <nav aria-label="Navigasi admin seluler">
                    <a href="{{ route('admin.dashboard') }}">Ringkasan</a>
                    <a href="{{ route('admin.users.index') }}">Users</a>
                    <a href="{{ route('admin.jokes.index') }}">Jokes</a>
                    <a href="{{ route('admin.categories.index') }}">Kategori</a>
                    <a href="{{ route('home') }}">Lihat situs</a>
                    <form method="POST" action="{{ route('admin.logout') }}">@csrf<button type="submit">Keluar</button></form>
                </nav>
            </details>
        </div>
    </header>
    <main id="konten" class="shell page-content">
        @if (session('success'))<div class="flash flash-success" role="status">{{ session('success') }}</div>@endif
        @if ($errors->any())
            <div class="flash flash-error" role="alert"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif
        @yield('content')
    </main>
</body>
</html>
