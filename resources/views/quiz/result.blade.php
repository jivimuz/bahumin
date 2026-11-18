@extends('layouts.app')

@section('title', 'Hasil Trivia')

@section('content')
<div class="mx-auto max-w-2xl space-y-6">
    <!-- Result Card -->
    <div class="warkop-card space-y-4 text-center">
        <div class="py-4">
            @if ($session->score >= $session->total_questions * 0.8)
                <h1 class="font-display text-3xl text-success-warm">Luar Biasa!</h1>
                <p class="text-text-secondary mt-1">Kamu benar-benar Suhu Warkop!</p>
            @elseif ($session->score >= $session->total_questions * 0.5)
                <h1 class="font-display text-3xl text-accent">Bagus!</h1>
                <p class="text-text-secondary mt-1">Sudah mulai paham humor warkop nih.</p>
            @else
                <h1 class="font-display text-3xl text-error-warm">Garing Banget!</h1>
                <p class="text-text-secondary mt-1">Coba lagi, bapak-bapak pasti bisa!</p>
            @endif
        </div>

        <div class="grid grid-cols-3 gap-4 text-center bg-surface-raised rounded-lg p-4">
            <div>
                <div class="font-mono text-2xl text-accent">{{ $session->score }}</div>
                <div class="text-xs text-text-secondary">Benar</div>
            </div>
            <div>
                <div class="font-mono text-2xl text-error-warm">{{ $session->total_questions - $session->score }}</div>
                <div class="text-xs text-text-secondary">Salah</div>
            </div>
            <div>
                <div class="font-mono text-2xl text-primary">{{ $session->xp_earned }}</div>
                <div class="text-xs text-text-secondary">XP Didapat</div>
            </div>
        </div>

        <div class="flex gap-3 justify-center">
            <a href="{{ route('quiz.index') }}" class="warkop-btn-primary">Main Lagi</a>
            <a href="{{ route('leaderboard.index') }}" class="warkop-btn-secondary">Lihat Peringkat</a>
        </div>
    </div>

    <!-- Detail Answers -->
    <section class="warkop-card space-y-4">
        <h2 class="font-display text-xl border-b border-border-warm pb-2">Detail Jawaban</h2>
        <div class="space-y-3 max-h-96 overflow-y-auto">
            @foreach ($session->sessionQuestions as $sq)
                <div class="p-3 rounded-lg
                    {{ $sq->is_correct ? 'bg-success-warm/5 border border-success-warm' : 'bg-error-warm/5 border border-error-warm' }}">
                    <div class="flex items-start gap-3">
                        <span class="w-6 h-6 flex items-center justify-center rounded-full border font-mono font-semibold
                            {{ $sq->is_correct ? 'text-success-warm border-success-warm' : 'text-error-warm border-error-warm' }}">
                            {{ $sq->is_correct ? '✓' : '✗' }}
                        </span>
                        <div class="flex-1">
                            <p class="font-medium">{{ $sq->question->question_text }}</p>
                            <p class="text-sm text-text-secondary mt-1">
                                Jawabanmu: {{ $sq->user_answer ?? 'Tidak dijawab' }}
                                @php $correctOption = $sq->question->options->firstWhere('is_correct', true); @endphp
                                @if (!$sq->is_correct && $correctOption)
                                    <br>Jawaban benar: {{ $correctOption->option_text }}
                                @endif
                            </p>
                            @if ($sq->question->punchline)
                                <p class="text-xs text-text-secondary mt-1 italic">{{ $sq->question->punchline }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</div>
@endsection