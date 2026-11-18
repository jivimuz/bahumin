@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<div class="space-y-8" itemscope itemtype="https://schema.org/CollectionPage">
    <!-- Hero -->
    <section class="warkop-card text-center space-y-4" style="border: 3px solid var(--ink)">
        <h1 class="font-display text-3xl md:text-5xl" itemprop="name">Bahumin · Bapak Humor Indonesia</h1>
        <p class="text-text-secondary max-w-2xl mx-auto text-lg" itemprop="description">Arena humor & trivia bergaya warkop legendaris. Tebak, jawab, dan rayakan setiap punchline bersama bapak-bapak penyuka lelucon.</p>
        @auth
            <a href="{{ route('quiz.index') }}" class="warkop-btn-primary inline-flex">Mulai Trivia</a>
        @else
            <div class="flex gap-3 justify-center">
                <a href="{{ route('login') }}" class="warkop-btn-secondary">Masuk</a>
                <a href="{{ route('register') }}" class="warkop-btn-primary">Daftar & Main</a>
            </div>
        @endauth
    </section>

    <!-- Recent Jokes -->
    <section>
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-display text-2xl">Jokes Terbaru</h2>
            <a href="{{ route('jokes.index') }}" class="text-sm text-primary hover:underline">Lihat Semua</a>
        </div>
        @if ($recentJokes->isEmpty())
            <div class="warkop-card text-center text-text-secondary py-8">Belum ada jokes. Jadilah yang pertama!</div>
        @else
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($recentJokes as $joke)
                    <article class="warkop-card space-y-2">
                        <p class="font-display text-lg">{{ $joke->setup }}</p>
                        <details class="group">
                            <summary class="cursor-pointer text-accent font-mono text-sm flex items-center gap-1">
                                <span>Buka Punchline</span>
                                <svg class="w-4 h-4 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </summary>
                            <div class="pt-2 border-t border-border-warm text-text-secondary">{{ $joke->punchline }}</div>
                        </details>
                        <footer class="flex items-center justify-between text-xs text-text-secondary border-t border-border-warm pt-2">
                            <span>oleh <a href="{{ route('profile.user', $joke->user->username) }}" class="hover:underline">{{ $joke->user->name }}</a></span>
                            <span class="warkop-badge">{{ $joke->category->name ?? 'Umum' }}</span>
                        </footer>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    <!-- Leaderboard Preview -->
    <section>
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-display text-2xl">Peringkat Mingguan</h2>
            <a href="{{ route('leaderboard.index') }}" class="text-sm text-primary hover:underline">Lihat Lengkap</a>
        </div>
        @if ($topPlayers->isEmpty())
            <div class="empty-state"><p>Papan peringkat akan terisi setelah pemain mengumpulkan XP.</p></div>
        @else
        <div class="warkop-card overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-surface-raised text-text-secondary">
                    <tr>
                        <th class="p-3 text-left">#</th>
                        <th class="p-3 text-left">Pemain</th>
                        <th class="p-3 text-left">Rank warkop</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-warm">
                    @foreach ($topPlayers as $i => $player)
                        <tr class="hover:bg-surface-raised">
                            <td class="p-3 font-mono font-semibold">{{ $i + 1 }}</td>
                            <td class="p-3"><a href="{{ route('profile.user', $player->username) }}" class="hover:underline">{{ $player->name }}</a></td>
                            <td class="p-3 font-semibold">{{ $player->rank->name ?? 'Anak Ecek-Ecek' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </section>

    @auth
    <!-- Quick Resume -->
    @if ($userRecentSession)
    <section class="warkop-card border-accent">
        <h3 class="font-display text-xl mb-2">Lanjutkan Permainan</h3>
        <p class="text-text-secondary mb-3">Kamu belum menyelesaikan sesi trivia terakhir.</p>
        <a href="{{ route('quiz.show', $userRecentSession) }}" class="warkop-btn-primary inline-flex">Lanjutkan</a>
    </section>
    @endif
    @endauth
</div>
@endsection