@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<div class="mx-auto max-w-md warkop-card space-y-6">
    <header class="text-center">
        <h1 class="font-display text-2xl">Bergabung dengan Bahumin</h1>
        <p class="text-text-secondary mt-1">Buat akun untuk bermain trivia & berbagi jokes</p>
    </header>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label for="username" class="block text-sm font-semibold mb-1">Username</label>
            <input id="username" type="text" name="username" required
                   value="{{ old('username') }}"
                   class="warkop-input" autocomplete="username">
            @error('username')
                <p class="mt-1 text-xs text-error-warm">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="name" class="block text-sm font-semibold mb-1">Nama Lengkap</label>
            <input id="name" type="text" name="name" required
                   value="{{ old('name') }}"
                   class="warkop-input" autocomplete="name">
            @error('name')
                <p class="mt-1 text-xs text-error-warm">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-semibold mb-1">Email</label>
            <input id="email" type="email" name="email" required
                   value="{{ old('email') }}"
                   class="warkop-input" autocomplete="email">
            @error('email')
                <p class="mt-1 text-xs text-error-warm">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="field-label">Kata Sandi</label>
            <input id="password" type="password" name="password" required class="warkop-input" autocomplete="new-password">
            @error('password')<p class="field-error">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-semibold mb-1">Konfirmasi Kata Sandi</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                   class="warkop-input" autocomplete="new-password">
        </div>

        <button type="submit" class="warkop-btn-primary w-full">Daftar</button>
    </form>

    <p class="text-center text-sm text-text-secondary">
        Sudah punya akun? <a href="{{ route('login') }}" class="text-primary hover:underline">Masuk di sini</a>
    </p>
</div>
@endsection