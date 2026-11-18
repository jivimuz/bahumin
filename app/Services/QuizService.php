<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Question;
use App\Models\QuizSession;
use App\Models\QuizSessionQuestion;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class QuizService
{
    public function __construct(
        protected GamificationService $gamificationService
    ) {}

    /**
     * Start a new quiz session for a user.
     */
    public function createSession(User $user, ?int $categoryId = null, int $totalQuestions = 5): QuizSession
    {
        $query = Question::where('status', 'published');

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $questions = $query->inRandomOrder()->limit($totalQuestions)->get();

        if ($questions->isEmpty()) {
            // Fallback to any published questions if category is empty
            $questions = Question::where('status', 'published')
                ->inRandomOrder()
                ->limit($totalQuestions)
                ->get();
        }

        if ($questions->isEmpty()) {
            throw new Exception('Belum ada pertanyaan tersedia di Warung.');
        }

        return DB::transaction(function () use ($user, $categoryId, $questions) {
            $session = QuizSession::create([
                'user_id' => $user->id,
                'category_id' => $categoryId,
                'status' => 'in_progress',
                'total_questions' => $questions->count(),
                'correct_answers' => 0,
                'score' => 0,
                'xp_earned' => 0,
            ]);

            foreach ($questions as $index => $q) {
                QuizSessionQuestion::create([
                    'quiz_session_id' => $session->id,
                    'question_id' => $q->id,
                    'order_index' => $index + 1,
                ]);
            }

            return $session;
        });
    }

    /**
     * Validate and record user answer for a question in the session.
     */
    public function answerQuestion(QuizSession $session, int $questionId, string $userAnswer): array
    {
        if ($session->status !== 'in_progress') {
            throw new Exception('Sesi kuis sudah selesai atau tidak aktif.');
        }

        $sessionQuestion = $session->sessionQuestions()
            ->where('question_id', $questionId)
            ->first();

        if (! $sessionQuestion) {
            throw new Exception('Pertanyaan tidak ditemukan dalam sesi ini.');
        }

        if ($sessionQuestion->answered_at !== null) {
            throw new Exception('Pertanyaan ini sudah kamu jawab sebelumnya.');
        }

        $question = $sessionQuestion->question;
        $isCorrect = false;
        $correctAnswerText = '';

        if ($question->type === 'multiple_choice') {
            $correctOption = $question->options()->where('is_correct', true)->first();
            $correctAnswerText = $correctOption ? $correctOption->option_text : '';

            $selectedOption = $question->options()->where('id', $userAnswer)->first();
            $isCorrect = $selectedOption && $selectedOption->is_correct;
            $savedAnswer = $selectedOption ? $selectedOption->option_text : $userAnswer;
        } else {
            // Short answer normalization
            $normalizedUser = $this->normalizeText($userAnswer);
            $accepted = $question->accepted_answers ?? [];

            foreach ($accepted as $ans) {
                if ($this->normalizeText($ans) === $normalizedUser) {
                    $isCorrect = true;
                    break;
                }
            }

            $correctAnswerText = ! empty($accepted) ? $accepted[0] : '';
            $savedAnswer = $userAnswer;
        }

        $sessionQuestion->update([
            'user_answer' => $savedAnswer,
            'is_correct' => $isCorrect,
            'answered_at' => now(),
        ]);

        $xpAwarded = 0;
        if ($isCorrect) {
            $session->increment('correct_answers');
            $xpAwarded = random_int(2, 5);
            $this->gamificationService->awardXp(
                $session->user,
                $xpAwarded,
                "Jawaban benar kuis sesi #{$session->id}",
                'quiz_session_question',
                (string) $sessionQuestion->id,
                "quiz_question_correct_{$session->id}_{$sessionQuestion->id}"
            );
        }

        $session->increment('xp_earned', $xpAwarded);

        // Check if all questions are answered
        $unansweredCount = $session->sessionQuestions()->whereNull('answered_at')->count();

        return [
            'is_correct' => $isCorrect,
            'correct_answer' => $correctAnswerText,
            'punchline' => $question->punchline,
            'all_answered' => $unansweredCount === 0,
            'remaining_count' => $unansweredCount,
        ];
    }

    /**
     * Complete the quiz session and award XP safely.
     */
    public function completeSession(QuizSession $session): array
    {
        if ($session->status === 'completed') {
            return [
                'session' => $session,
                'already_completed' => true,
                'xp_awarded' => $session->xp_earned,
                'unlocked_achievements' => [],
            ];
        }

        $totalQuestions = $session->total_questions;
        $correctCount = $session->sessionQuestions()->where('is_correct', true)->count();
        $score = $totalQuestions > 0 ? (int) round(($correctCount / $totalQuestions) * 100) : 0;
        $xpEarned = $session->sessionQuestions()->sum('is_correct') ? 0 : 0; // XP already awarded per correct answer (2-5)
        $isPerfect = ($correctCount === $totalQuestions && $totalQuestions > 0);

        $user = $session->user;

        DB::transaction(function () use ($session, $correctCount, $score) {
            $session->update([
                'status' => 'completed',
                'correct_answers' => $correctCount,
                'score' => $score,
                'completed_at' => now(),
            ]);
        });

        $completedSessionsCount = QuizSession::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $unlocked1 = $this->gamificationService->checkAchievements($user, 'quiz_completed', $completedSessionsCount);
        $unlocked2 = [];

        if ($isPerfect) {
            $unlocked2 = $this->gamificationService->checkAchievements($user, 'perfect_score', 1);
        }

        return [
            'session' => $session->fresh(),
            'already_completed' => false,
            'score' => $score,
            'correct_count' => $correctCount,
            'total_questions' => $totalQuestions,
            'xp_awarded' => $session->xp_earned,
            'is_perfect' => $isPerfect,
            'unlocked_achievements' => array_merge($unlocked1, $unlocked2),
        ];
    }

    /**
     * Text normalization for short answer checks.
     */
    protected function normalizeText(string $text): string
    {
        $text = mb_strtolower(trim($text));
        // Remove punctuation
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', '', $text);

        // Normalize whitespace
        return preg_replace('/\s+/', ' ', $text);
    }
}
