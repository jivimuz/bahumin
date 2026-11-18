@extends('layouts.app')

@section('title', $joke->title ?: 'Detail jokes')

@section('content')
<div class="mx-auto max-w-2xl space-y-6">
    <article class="warkop-card space-y-4">
        <header class="flex items-start justify-between gap-4">
            <div>
                <p class="font-display text-2xl">{{ $joke->setup }}</p>
                <div class="flex items-center gap-2 mt-2 text-sm text-text-secondary">
                    <span>oleh <a href="{{ route('profile.user', $joke->user->username) }}" class="hover:underline">{{ $joke->user->name }}</a></span>
                    <span class="warkop-badge">{{ $joke->category->name ?? 'Umum' }}</span>
                    <time datetime="{{ $joke->created_at->toIso8601String() }}">{{ $joke->created_at->diffForHumans() }}</time>
                </div>
            </div>
        </header>

        <details class="group" open>
            <summary class="cursor-pointer text-accent font-mono text-lg flex items-center gap-2">
                <svg class="w-5 h-5 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                <span>Punchline</span>
            </summary>
            <div class="pt-4 border-t border-border-warm text-lg text-text-secondary font-display italic">{{ $joke->punchline }}</div>
        </details>

        <div class="filter-links" aria-label="Reaksi jokes">
            @auth
                @php $reactionCounts = $joke->reactionCounts(); @endphp
                @foreach (['ngakak' => 'Ngakak', 'garing' => 'Garing', 'bapak' => 'Bapak banget', 'jenius' => 'Jenius'] as $type => $label)
                    <form method="POST" action="{{ route('jokes.react', $joke) }}">
                        @csrf
                        <input type="hidden" name="reaction_type" value="{{ $type }}">
                        <button type="submit" class="warkop-btn-secondary" aria-pressed="{{ $joke->userReaction($user?->id) === $type ? 'true' : 'false' }}">{{ $label }}: {{ $reactionCounts[$type] }}</button>
                    </form>
                @endforeach
                <form method="POST" action="{{ route('jokes.bookmark', $joke) }}">
                    @csrf
                    <button type="submit" class="warkop-btn-secondary">{{ $joke->isBookmarkedBy($user?->id) ? 'Hapus dari simpanan' : 'Simpan jokes' }}</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="warkop-btn-secondary">Masuk untuk memberi reaksi</a>
            @endauth
        </div>
    </article>

    <!-- Comments -->
    <section class="space-y-4">
        <h2 class="font-display text-xl flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            Komentar ({{ $joke->comments->count() }})
        </h2>

        @auth
        <form method="POST" action="{{ route('jokes.comment', $joke) }}" class="warkop-card space-y-3">
            @csrf
            <label class="field-label" for="comment_text">Komentarmu</label>
            <textarea id="comment_text" name="comment_text" rows="3" placeholder="Tulis komentar..." class="warkop-input resize-none" required>{{ old('comment_text') }}</textarea>
            <button type="submit" class="warkop-btn-primary">Kirim</button>
        </form>
        @else
            <div class="warkop-card text-center py-4 text-text-secondary">
                <a href="{{ route('login') }}" class="text-primary hover:underline">Masuk</a> untuk berkomentar.
            </div>
        @endauth

        @if ($joke->comments->isEmpty())
            <p class="text-text-secondary text-center py-4">Belum ada komentar. Jadilah yang pertama!</p>
        @else
            <div class="space-y-3">
                @foreach ($joke->comments as $comment)
                    <article class="warkop-card space-y-2">
                        <header class="flex items-center gap-2 text-sm text-text-secondary">
                            <a href="{{ route('profile.user', $comment->user->username) }}" class="font-semibold hover:underline">{{ $comment->user->name }}</a>
                            <time datetime="{{ $comment->created_at->toIso8601String() }}">{{ $comment->created_at->diffForHumans() }}</time>
                        </header>
                        <p>{{ $comment->comment_text }}</p>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection