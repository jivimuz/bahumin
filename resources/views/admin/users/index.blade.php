@extends('layouts.admin')
@section('title', 'Kelola users')
@section('content')
<header class="page-heading"><h1>Kelola users</h1><p>Ubah nama, atur akses admin, atau hapus akun. Akun sendiri tidak dapat dihapus.</p></header>
<div id="admin-users-list" data-ajax-list data-current-user="{{ auth()->id() }}" data-endpoint="{{ route('admin.users.index') }}" data-type="admin-users" class="grid gap-4"></div>
@endsection
