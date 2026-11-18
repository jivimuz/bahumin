<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Joke;
use App\Models\QuizSession;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $categories = Category::where('is_active', true)->withCount('questions')->get();
        $recentJokes = Joke::with(['user.rank', 'category'])->where('status', 'published')->latest()->limit(3)->get();
        $topPlayers = User::with('rank')->orderBy('total_xp', 'desc')->limit(5)->get();

        $userRecentSession = null;
        if ($user) {
            $userRecentSession = QuizSession::where('user_id', $user->id)
                ->where('status', 'in_progress')
                ->latest()
                ->first();
        }

        return view('home', compact('user', 'categories', 'recentJokes', 'topPlayers', 'userRecentSession'));
    }
}
