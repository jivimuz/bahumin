@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="mx-auto max-w-md warkop-card space-y-6">
    <header>
        <h1 class="font-display text-2xl">Edit Profil</h1>
        <p class="text-text-secondary mt-1">Ubah informasi akunmu</p>
    </header>

    <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block text-sm font-semibold mb-1">Nama</label>
            <input id="name" type="text" name="name" required
                   value="{{ old('name', $user->name) }}"
                   class="warkop-input">
            @error('name')
                <p class="mt-1 text-xs text-error-warm">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="bio" class="block text-sm font-semibold mb-1">Bio</label>
            <textarea id="bio" name="bio" rows="3"
                      placeholder="Ceritakan sedikit tentang dirimu..."
                      class="warkop-input resize-none">{{ old('bio', $user->bio) }}</textarea>
            @error('bio')
                <p class="mt-1 text-xs text-error-warm">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <span class="field-label">Email</span>
            <p>{{ $user->email }}</p>
            <p class="field-help">Email tidak dapat diubah dari halaman ini.</p>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="warkop-btn-primary flex-1">Simpan</button>
            <a href="{{ route('profile.show') }}" class="warkop-btn-secondary flex-1 text-center">Batal</a>
        </div>
    </form>
</div>
@endsection