@extends('layouts.app')

@section('title', 'Arena Trivia')

@section('content')
<div class="quiz-layout">
    <!-- Progress Header -->
    <div class="warkop-card space-y-3">
        <div class="flex items-center justify-between">
            <h1 class="font-display text-2xl">Trivia Warkop</h1>
            <div class="text-right">
                <div class="font-mono text-accent">Soal {{ $currentIndex + 1 }} / {{ $session->total_questions }}</div>
                <div class="text-xs text-text-secondary">{{ $session->score }} poin</div>
            </div>
        </div>
        <div class="progress-track">
            <div class="progress-fill" style="width: {{ (($currentIndex + 1) / $session->total_questions) * 100 }}%"></div>
        </div>
    </div>

    <!-- Question Card -->
    <div class="warkop-card space-y-6">
        <p class="quiz-question">{{ $currentQuestion->question->question_text }}</p>

        <form method="POST" action="{{ route('quiz.answer', $session) }}" class="option-list" id="quiz-form">
            @csrf
            <input type="hidden" name="question_id" value="{{ $currentQuestion->question_id }}">

            @if ($currentQuestion->question->type === 'multiple_choice')
                @foreach ($currentQuestion->question->options as $option)
                    <button type="submit" name="answer" value="{{ $option->id }}" class="option-button">
                        <span class="option-letter">{{ chr(65 + $loop->index) }}</span>
                        <span style="flex: 1">{{ $option->option_text }}</span>
                    </button>
                @endforeach
            @else
                <div>
                    <label class="field-label" for="answer">Tulis jawabanmu</label>
                    <input class="warkop-input" id="answer" name="answer" required autocomplete="off" autofocus>
                    <button type="submit" class="warkop-btn-primary" style="margin-top: 12px">Kunci jawaban</button>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection
