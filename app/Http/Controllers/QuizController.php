<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\QuizSession;
use App\Services\QuizService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function __construct(
        protected QuizService $quizService
    ) {}

    public function index(): View
    {
        $categories = Category::where('is_active', true)->withCount('questions')->get();
        $totalQuestions = $categories->sum('questions_count');

        return view('quiz.index', compact('categories', 'totalQuestions'));
    }

    public function start(Request $request): RedirectResponse
    {
        $categoryId = $request->input('category_id');
        $questionCount = (int) $request->input('count', 5);

        try {
            $session = $this->quizService->createSession(Auth::user(), $categoryId ? (int) $categoryId : null, $questionCount);

            return redirect()->route('quiz.show', $session);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(QuizSession $session): View|RedirectResponse
    {
        if ($session->user_id !== Auth::id()) {
            abort(403, 'Kamu tidak memiliki akses ke sesi kuis ini.');
        }

        if ($session->isCompleted()) {
            return redirect()->route('quiz.result', $session);
        }

        $sessionQuestions = $session->sessionQuestions()
            ->with(['question.options'])
            ->orderBy('order_index')
            ->get();

        $currentIndex = $sessionQuestions->search(fn ($sq) => $sq->answered_at === null);
        if ($currentIndex === false) {
            // All answered, complete session
            $this->quizService->completeSession($session);

            return redirect()->route('quiz.result', $session);
        }

        $currentQuestion = $sessionQuestions[$currentIndex];

        return view('quiz.play', compact('session', 'sessionQuestions', 'currentQuestion', 'currentIndex'));
    }

    public function answer(Request $request, QuizSession $session): JsonResponse|RedirectResponse
    {
        if ($session->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'question_id' => ['required', 'integer'],
            'answer' => ['required', 'string'],
        ]);

        try {
            $result = $this->quizService->answerQuestion(
                $session,
                (int) $request->input('question_id'),
                $request->input('answer')
            );

            if ($request->wantsJson()) {
                return response()->json($result);
            }

            if ($result['all_answered']) {
                $this->quizService->completeSession($session);

                return redirect()->route('quiz.result', $session);
            }

            return redirect()->route('quiz.show', $session);
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['error' => $e->getMessage()], 422);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    public function complete(QuizSession $session): RedirectResponse
    {
        if ($session->user_id !== Auth::id()) {
            abort(403);
        }

        $this->quizService->completeSession($session);

        return redirect()->route('quiz.result', $session);
    }

    public function result(QuizSession $session): View
    {
        if ($session->user_id !== Auth::id()) {
            abort(403);
        }

        // If not marked completed yet, complete now
        if (! $session->isCompleted()) {
            $this->quizService->completeSession($session);
            $session->refresh();
        }

        $sessionQuestions = $session->sessionQuestions()
            ->with(['question.options'])
            ->orderBy('order_index')
            ->get();

        $user = Auth::user()->fresh(['rank']);

        return view('quiz.result', compact('session', 'sessionQuestions', 'user'));
    }

    public function history(Request $request): View|JsonResponse
    {
        $sessions = QuizSession::where('user_id', Auth::id())
            ->with('category')
            ->latest()
            ->paginate(10);

        if ($request->expectsJson()) {
            return response()->json($sessions);
        }

        return view('quiz.history');
    }
}
