<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Bahumin (Bapak Humor Indonesia): ruang nongkrong digital untuk membaca jokes, membuat tebakan, bermain trivia, dan mengumpulkan XP.">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="robots" content="index,follow,max-image-preview:large">
    <meta property="og:locale" content="id_ID">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Bahumin">
    <meta property="og:title" content="@yield('title', 'Warung Humor') · Bahumin">
    <meta property="og:description" content="Baca jokes, tulis tebakan, main trivia, dan kumpulkan XP di Bapak Humor Indonesia.">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary">
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@type": "WebSite",
      "name": "Bahumin",
      "alternateName": "Bapak Humor Indonesia",
      "url": "{{ url('/') }}"
    }
    </script>
    <title>@yield('title', 'Warung Humor') · Bahumin (Bapak Humor Indonesia)</title>
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
            <a href="{{ route('home') }}" class="brand" aria-label="Bahumin, kembali ke beranda">
                <img src="{{ asset('logo_img.png') }}" alt="BAHUMIN - Bapak Humor Indonesia" class="h-16 w-auto">
            </a>

            <nav class="desktop-nav" aria-label="Navigasi utama">
                <a href="{{ route('home') }}" @class(['active' => request()->routeIs('home')])>Beranda</a>
                <a href="{{ route('jokes.index') }}" @class(['active' => request()->routeIs('jokes.*')])>Lihat jokes</a>
                <a href="{{ route('leaderboard.index') }}" @class(['active' => request()->routeIs('leaderboard.*')])>Peringkat</a>
                @auth
                    <a href="{{ route('quiz.index') }}" @class(['active' => request()->routeIs('quiz.*')])>Main trivia</a>
                    <a href="{{ route('profile.show') }}" @class(['active' => request()->routeIs('profile.*')])>Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-logout">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Masuk</a>
                    <a href="{{ route('register') }}" class="nav-cta">Ikut nongkrong</a>
                @endauth
            </nav>

            <details class="mobile-nav">
                <summary>Menu</summary>
                <nav aria-label="Navigasi seluler">
                    <a href="{{ route('home') }}">Beranda</a>
                    <a href="{{ route('jokes.index') }}">Lihat jokes</a>
                    <a href="{{ route('leaderboard.index') }}">Peringkat</a>
                    @auth
                        <a href="{{ route('quiz.index') }}">Main trivia</a>
                        <a href="{{ route('quiz.history') }}">Riwayat main</a>
                        <a href="{{ route('jokes.create') }}">Tulis jokes</a>
                        <a href="{{ route('profile.show') }}">Profil saya</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit">Keluar</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}">Masuk</a>
                        <a href="{{ route('register') }}">Daftar akun</a>
                    @endauth
                </nav>
            </details>
        </div>
    </header>

    <main id="konten" class="shell page-content">
        @foreach (['success' => 'success', 'info' => 'info', 'error' => 'error', 'status' => 'success'] as $flash => $type)
            @if (session($flash))
                <div class="flash flash-{{ $type }}" role="{{ $type === 'error' ? 'alert' : 'status' }}">
                    {{ session($flash) }}
                </div>
            @endif
        @endforeach

        @if ($errors->any())
            <div class="flash flash-error" role="alert">
                <strong>Periksa lagi isianmu:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="shell footer-inner">
            <span class="footer-brand"><img src="{{ asset('logo.png') }}" alt="BAHUMIN" class="h-10 w-auto"></span>
            <p>Bapak Humor Indonesia. Tempat singgah buat yang suka tebakan receh.</p>
            <p class="footer-small">Diseduh untuk warkop, {{ date('Y') }}.</p>
        </div>
    </footer>
    @stack('scripts')
</body>
</html>
