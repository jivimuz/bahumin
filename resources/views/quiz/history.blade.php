@extends('layouts.app')
@section('title', 'Riwayat Trivia')
@section('content')
<header class="page-heading heading-with-action"><div><h1>Riwayat main</h1><p>Catatan sesi trivia yang pernah kamu mainkan.</p></div><a class="warkop-btn-primary" href="{{ route('quiz.index') }}">Pilih meja baru</a></header>
<div id="quiz-history-list" data-ajax-list data-endpoint="{{ route('quiz.history') }}" data-type="history" class="grid gap-4"></div>
@endsection
