@extends('layouts.app')

@section('title', 'Peringkat')

@section('content')
<header class="page-heading heading-with-action">
    <div>
        <h1>Papan nama warkop</h1>
        <p>Rank ditentukan dari total XP sepanjang waktu. Tab "7 hari" mengurutkan siapa paling aktif minggu ini.</p>
    </div>
    <nav class="filter-links" aria-label="Periode peringkat">
        <button type="button" data-filter-list="leaderboard-list" data-filter-key="tab" data-filter-value="all_time" class="{{ $tab === 'all_time' ? 'active' : '' }}">Sepanjang waktu</button>
        <button type="button" data-filter-list="leaderboard-list" data-filter-key="tab" data-filter-value="weekly" class="{{ $tab === 'weekly' ? 'active' : '' }}">7 hari terakhir</button>
    </nav>
</header>

<ol id="leaderboard-list" data-ajax-list data-endpoint="{{ route('api.leaderboard') }}" data-type="leaderboard" data-filters="tab={{ $tab }}" class="leaderboard-list" aria-label="Peringkat pemain"></ol>

<aside class="warkop-card" style="margin-top: 24px" aria-label="Posisimu" data-current-rank @guest hidden @endguest>
    @if($currentUserRank)
        <strong>Posisimu: #<span data-rank-number>{{ $currentUserRank['rank'] }}</span></strong>
        <p style="margin-bottom: 0"><span data-rank-xp>{{ number_format($currentUserRank['xp']) }}</span> XP pada periode ini. <a class="text-link" href="{{ route('profile.show') }}">Buka profilmu</a></p>
    @endif
</aside>
@endsection