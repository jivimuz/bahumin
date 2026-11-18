@extends('layouts.app')

@section('title', 'Trivia')

@section('content')
<header class="page-heading heading-with-action">
    <div><h1>Arena tebak-tebakan</h1><p>Pilih meja, jawab {{ $totalQuestions > 0 ? 'soal yang tersedia' : 'pertanyaan' }}, lalu kumpulkan XP.</p></div>
    <a href="{{ route('quiz.history') }}" class="warkop-btn-secondary">Riwayat main</a>
</header>
@if ($categories->isEmpty() || $totalQuestions === 0)
    <div class="empty-state"><p>Belum ada soal yang bisa dimainkan. Kembali lagi setelah admin menambahkan soal.</p></div>
@else
    <div class="joke-grid">
        @foreach ($categories as $category)
            <article class="warkop-card joke-card">
                <span class="joke-number">{{ $category->questions_count }} SOAL</span>
                <h2 class="joke-setup">{{ $category->name }}</h2>
                <p>{{ $category->description ?: 'Tebakan pilihan dari meja warkop.' }}</p>
                <form method="POST" action="{{ route('quiz.start') }}" style="margin-top: auto">
                    @csrf
                    <input type="hidden" name="category_id" value="{{ $category->id }}">
                    <button type="submit" class="warkop-btn-primary" @disabled($category->questions_count === 0)>Main kategori ini</button>
                </form>
            </article>
        @endforeach
    </div>
    <form method="POST" action="{{ route('quiz.start') }}" class="warkop-card heading-with-action" style="margin-top: 24px">
        @csrf
        <div><h2>Meja campuran</h2><p style="margin-bottom: 0">Soal acak dari seluruh kategori.</p></div>
        <button type="submit" class="warkop-btn-primary">Main meja campuran</button>
    </form>
@endif
@endsection