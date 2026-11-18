@extends('layouts.admin')
@section('title', 'Kelola jokes')
@section('content')
<header class="page-heading"><h1>Kelola jokes</h1><p>Jokes berstatus published muncul di etalase. Data dummy ditandai dari nama usernya.</p></header>
<section class="warkop-card" style="margin-bottom: 28px">
    <h2>Buat user dan jokes dummy</h2>
    <p>Satu aksi membuat satu akun dummy beserta satu jokes yang langsung tayang.</p>
    <form class="form-actions" method="POST" action="{{ route('admin.dummy.store') }}">
        @csrf
        <div><label class="field-label" for="dummy-category">Kategori</label><select class="warkop-input" id="dummy-category" name="category_id" required>@foreach($categories as $category)<option value="{{ $category->id }}">{{ $category->name }}</option>@endforeach</select></div>
        <div><label class="field-label" for="dummy-count">Jumlah, maksimal 10</label><input class="warkop-input" id="dummy-count" name="count" type="number" min="1" max="10" value="1" required></div>
        <button class="warkop-btn-primary" type="submit" @disabled($categories->isEmpty())>Buat data dummy</button>
    </form>
    @if($categories->isEmpty())<p class="field-help"><a class="text-link" href="{{ route('admin.categories.index') }}">Buat kategori terlebih dahulu.</a></p>@endif
</section>
<div id="admin-jokes-list" data-ajax-list data-categories="{{ $categories->map(fn ($category) => ['id' => $category->id, 'name' => $category->name])->toJson() }}" data-endpoint="{{ route('admin.jokes.index') }}" data-type="admin-jokes" class="grid gap-5"></div>
@endsection
