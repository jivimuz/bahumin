@extends('layouts.app')
@section('title', 'Login admin')
@section('content')
<div class="form-page warkop-card">
    <header class="page-heading">
        <h1>Login admin</h1>
        <p>Area khusus pengelola Bahumin. User biasa tetap masuk melalui halaman login user.</p>
    </header>
    @if (session('success'))<div class="flash flash-success" role="status">{{ session('success') }}</div>@endif
    <form method="POST" action="{{ route('admin.login.submit') }}" class="form-stack">
        @csrf
        <div>
            <label class="field-label" for="login">Email atau Username admin</label>
            <input class="warkop-input" id="login" type="text" name="login" value="{{ old('login') }}" required autocomplete="username" autofocus>
            @error('login')<p class="field-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="field-label" for="password">Kata sandi</label>
            <input class="warkop-input" id="password" type="password" name="password" required autocomplete="current-password">
        </div>
        <label style="display: flex; align-items: center; gap: 8px; min-height: 44px"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
        <button class="warkop-btn-primary" type="submit">Masuk ke konsol admin</button>
    </form>
    <p class="field-help" style="margin-top: 20px">Bukan pengelola? <a class="text-link" href="{{ route('login') }}">Buka login user</a></p>
</div>
@endsection
