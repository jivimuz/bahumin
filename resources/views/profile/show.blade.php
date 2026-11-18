@extends('layouts.app')

@section('title', $user->name)

@section('content')
<section class="warkop-card profile-header">
    <div class="avatar-placeholder" aria-label="Inisial {{ $user->name }}">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
    <div>
        <h1>{{ $user->name }}</h1>
        <p>@{{ $user->username }} · {{ $user->bio ?: 'Warga Bahumin' }}</p>
        <ul class="stats-line">
            <li><strong>{{ number_format($user->total_xp) }}</strong> XP</li>
            <li>{{ $user->rank->name ?? 'Anak Ecek-Ecek' }} (Level {{ $user->rank->level ?? 1 }})</li>
            @php
                $nextRank = \App\Models\Rank::where('min_xp', '>', $user->total_xp)->orderBy('min_xp')->first();
            @endphp
            <li>
                @if ($nextRank)
                    Progress ke <strong>{{ $nextRank->name }}</strong>: {{ $user->rank_progress }}%
                @else
                    <span class="warkop-badge" style="background:var(--amber); color:#000">MAX RANK</span>
                @endif
            </li>
            <li>{{ $jokeCount }} jokes tayang</li>
            <li>{{ $completedSessionCount }} trivia selesai</li>
        </ul>
    </div>
    @if ($isOwnProfile)<a href="{{ route('profile.edit') }}" class="warkop-btn-secondary">Edit profil</a>@endif
</section>
<div class="profile-columns">
    <section>
        <h2>Jokes terbaru</h2>
        @if ($userJokes->isEmpty())
            <div class="empty-state"><p>Belum ada jokes dari {{ $user->name }}.</p></div>
        @else
            <div class="grid gap-4">
            @foreach ($userJokes as $joke)
                <a href="{{ route('jokes.show', $joke) }}" class="warkop-card"><h3>{{ $joke->setup }}</h3><span class="text-link">Baca dan bahas</span></a>
            @endforeach
            </div>
        @endif
    </section>
    <aside>
        <h2>Lencana warkop</h2>
        <ul class="achievement-list">
        @forelse ($allAchievements as $achievement)
            <li @class(['locked' => !in_array($achievement->id, $userAchievementIds)])>
                <strong>{{ $achievement->name }}</strong><br>
                <small>{{ $achievement->description }}</small><br>
                <span>{{ in_array($achievement->id, $userAchievementIds) ? 'Sudah diperoleh' : 'Belum diperoleh' }}</span>
            </li>
        @empty
            <li>Belum ada lencana yang tersedia.</li>
        @endforelse
        </ul>
    </aside>
</div>
@endsection