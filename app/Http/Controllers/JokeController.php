<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Joke;
use App\Models\JokeComment;
use App\Models\JokeReaction;
use App\Services\GamificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class JokeController extends Controller
{
    public function __construct(
        protected GamificationService $gamificationService
    ) {}

    public function index(Request $request): View
    {
        $categories = Category::all();
        $selectedCategory = $request->query('category');

        $query = Joke::with(['user.rank', 'category', 'reactions'])
            ->where('status', 'published');

        if ($selectedCategory) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $selectedCategory));
        }

        $sort = $request->query('sort', 'latest');
        if ($sort === 'popular') {
            $query->withCount('reactions')->orderBy('reactions_count', 'desc');
        } else {
            $query->latest();
        }

        $jokes = $query->paginate(10);
        $user = Auth::user();

        return view('jokes.index', compact('jokes', 'categories', 'selectedCategory', 'sort', 'user'));
    }

    public function create(): View
    {
        $categories = Category::all();

        return view('jokes.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:120'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'setup' => ['required', 'string', 'min:10', 'max:1000'],
            'punchline' => ['required', 'string', 'min:3', 'max:1000'],
        ]);

        $user = Auth::user();

        $joke = Joke::create([
            'user_id' => $user->id,
            'category_id' => $validated['category_id'] ?? null,
            'title' => $validated['title'],
            'setup' => $validated['setup'],
            'punchline' => $validated['punchline'],
            'status' => 'published',
        ]);

        // Reward XP for sharing jokes (capped at +5 XP)
        $this->gamificationService->awardXp(
            $user,
            5,
            'Berbagi Jokes ke Warung',
            'joke',
            (string) $joke->id,
            "joke_post_{$joke->id}"
        );

        // Check creator achievement
        $jokesCount = Joke::where('user_id', $user->id)->count();
        $this->gamificationService->checkAchievements($user, 'jokes_posted', $jokesCount);

        return redirect()->route('jokes.index')->with('success', 'Jokes berhasil dibagikan ke warkop! +5 XP masuk kantong.');
    }

    public function show(Joke $joke): View
    {
        abort_unless(
            $joke->status === 'published' || Auth::id() === $joke->user_id || (Auth::check() && Auth::user()->is_admin),
            404
        );

        $joke->increment('views_count');
        $joke->load(['user.rank', 'category', 'comments.user.rank', 'reactions']);
        $user = Auth::user();

        return view('jokes.show', compact('joke', 'user'));
    }

    public function react(Request $request, Joke $joke): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'reaction_type' => ['required', 'in:ngakak,garing,bapak,jenius'],
        ]);

        $userId = Auth::id();
        $existing = JokeReaction::where('joke_id', $joke->id)->where('user_id', $userId)->first();

        if ($existing) {
            if ($existing->reaction_type === $validated['reaction_type']) {
                $existing->delete();
                $currentReaction = null;
            } else {
                $existing->update(['reaction_type' => $validated['reaction_type']]);
                $currentReaction = $validated['reaction_type'];
            }
        } else {
            JokeReaction::create([
                'joke_id' => $joke->id,
                'user_id' => $userId,
                'reaction_type' => $validated['reaction_type'],
            ]);
            $currentReaction = $validated['reaction_type'];
        }

        if ($request->wantsJson()) {
            return response()->json([
                'current_reaction' => $currentReaction,
                'counts' => $joke->fresh()->reactionCounts(),
            ]);
        }

        return back();
    }

    public function comment(Request $request, Joke $joke): RedirectResponse
    {
        $validated = $request->validate([
            'comment_text' => ['required', 'string', 'min:2', 'max:500'],
        ]);

        JokeComment::create([
            'joke_id' => $joke->id,
            'user_id' => Auth::id(),
            'comment_text' => $validated['comment_text'],
        ]);

        return back()->with('success', 'Komentar recehmu berhasil terkirim!');
    }

    public function bookmark(Request $request, Joke $joke): JsonResponse|RedirectResponse
    {
        $userId = Auth::id();
        $bookmarked = false;

        $existing = $joke->bookmarks()->where('user_id', $userId)->first();
        if ($existing) {
            $existing->delete();
        } else {
            $joke->bookmarks()->create(['user_id' => $userId]);
            $bookmarked = true;
        }

        if ($request->wantsJson()) {
            return response()->json(['bookmarked' => $bookmarked]);
        }

        return back();
    }
}
