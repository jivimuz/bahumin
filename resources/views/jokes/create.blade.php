@extends('layouts.app')

@section('title', 'Tulis Jokes')

@section('content')
<div class="mx-auto max-w-2xl warkop-card space-y-6">
    <header>
        <h1 class="font-display text-2xl">Buat Joke Baru</h1>
        <p class="text-text-secondary mt-1">Tulis setup (pertanyaan) dan punchline (jawaban lucunya).</p>
    </header>

    <form method="POST" action="{{ route('jokes.store') }}" class="space-y-5">
        @csrf

        <div>
            <label for="category_id" class="block text-sm font-semibold mb-1">Kategori</label>
            <select id="category_id" name="category_id" class="warkop-input">
                <option value="">Pilih kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            @error('category_id')
                <p class="mt-1 text-xs text-error-warm">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="setup" class="block text-sm font-semibold mb-1">Setup (Pertanyaan)</label>
            <textarea id="setup" name="setup" rows="3" required
                      placeholder="Contoh: Kenapa ayam menyeberang jalan?"
                      class="warkop-input resize-none">{{ old('setup') }}</textarea>
            @error('setup')
                <p class="mt-1 text-xs text-error-warm">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="punchline" class="block text-sm font-semibold mb-1">Punchline (Jawaban Lucunya)</label>
            <textarea id="punchline" name="punchline" rows="3" required
                      placeholder="Contoh: Karena dia ingin ke sisi lain!"
                      class="warkop-input resize-none">{{ old('punchline') }}</textarea>
            @error('punchline')
                <p class="mt-1 text-xs text-error-warm">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-3">
            <button type="submit" class="warkop-btn-primary flex-1">Posting Joke</button>
            <a href="{{ route('jokes.index') }}" class="warkop-btn-secondary flex-1 text-center">Batal</a>
        </div>
    </form>
</div>
@endsection