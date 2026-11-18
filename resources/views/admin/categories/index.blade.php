@extends('layouts.admin')
@section('title', 'Kelola kategori')
@section('content')
<header class="page-heading"><h1>Kelola kategori</h1><p>Kategori aktif dapat dipilih saat membuat jokes dan bermain trivia.</p></header>
<section class="warkop-card" style="margin-bottom: 28px">
    <h2>Tambah kategori</h2>
    <form class="form-actions" method="POST" action="{{ route('admin.categories.store') }}">
        @csrf
        <div><label class="field-label" for="new-name">Nama</label><input class="warkop-input" id="new-name" name="name" value="{{ old('name') }}" required></div>
        <div><label class="field-label" for="new-description">Deskripsi</label><input class="warkop-input" id="new-description" name="description" value="{{ old('description') }}"></div>
        <button class="warkop-btn-primary" type="submit">Tambah kategori</button>
    </form>
</section>
<div id="admin-categories-list" data-ajax-list data-endpoint="{{ route('admin.categories.index') }}" data-type="admin-categories" class="grid gap-4"></div>
@endsection
