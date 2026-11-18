@extends('layouts.admin')
@section('title', 'Konsol admin')
@section('content')
<header class="page-heading">
    <h1>Konsol admin</h1>
    <p>Ringkasan data Bahumin yang tersimpan di database.</p>
</header>
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <div class="warkop-card"><span class="score-value">{{ $userCount }}</span><span class="score-label">User</span></div>
    <div class="warkop-card"><span class="score-value">{{ $jokeCount }}</span><span class="score-label">Semua jokes</span></div>
    <div class="warkop-card"><span class="score-value">{{ $publishedJokeCount }}</span><span class="score-label">Jokes tayang</span></div>
    <div class="warkop-card"><span class="score-value">{{ $categoryCount }}</span><span class="score-label">Kategori</span></div>
</div>
<div class="profile-columns">
    <section class="warkop-card">
        <div class="heading-with-action"><h2>User terbaru</h2><a class="text-link" href="{{ route('admin.users.index') }}">Kelola users</a></div>
        @if ($latestUsers->isEmpty())<p>Belum ada user.</p>@else
        <ul class="achievement-list">@foreach ($latestUsers as $user)<li><strong>{{ $user->name }}</strong><br><small>{{ $user->email }}</small></li>@endforeach</ul>
        @endif
    </section>
    <section class="warkop-card">
        <div class="heading-with-action"><h2>Jokes terbaru</h2><a class="text-link" href="{{ route('admin.jokes.index') }}">Kelola jokes</a></div>
        @if ($latestJokes->isEmpty())<p>Belum ada jokes.</p>@else
        <ul class="achievement-list">@foreach ($latestJokes as $joke)<li><strong>{{ $joke->setup }}</strong><br><small>{{ $joke->user->name }} · {{ $joke->status }}</small></li>@endforeach</ul>
        @endif
    </section>
</div>
@endsection
