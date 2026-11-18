@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="mx-auto max-w-md warkop-card space-y-6">
    <header class="text-center">
        <h1 class="font-display text-2xl">Masuk ke Bahumin</h1>
        <p class="text-text-secondary mt-1">Masuk untuk memulai trivia & berbagi jokes</p>
    </header>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label for="login" class="block text-sm font-semibold mb-1">Email atau Username</label>
            <input id="login" type="text" name="login" required
                   value="{{ old('login') }}"
                   class="warkop-input" autocomplete="username">
            @error('login')
                <p class="mt-1 text-xs text-error-warm">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold mb-1">Kata Sandi</label>
            <input id="password" type="password" name="password" required
                   class="warkop-input" autocomplete="current-password">
            @error('password')
                <p class="mt-1 text-xs text-error-warm">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="remember" class="rounded border-border-warm">
                Ingat saya
            </label>
            <a href="{{ route('home') }}" class="text-sm text-primary hover:underline">Kembali ke beranda</a>
        </div>

        <button type="submit" class="warkop-btn-primary w-full">Masuk</button>
    </form>

    <p class="text-center text-sm text-text-secondary">
        Belum punya akun? <a href="{{ route('register') }}" class="text-primary hover:underline">Daftar di sini</a>
    </p>
</div>
@endsection