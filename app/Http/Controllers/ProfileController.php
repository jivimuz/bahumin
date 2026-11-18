<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\QuizSession;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(?string $username = null): View
    {
        if ($username) {
            $user = User::with(['rank', 'achievements'])->where('username', $username)->firstOrFail();
        } else {
            $user = Auth::user()->load(['rank', 'achievements']);
        }

        $allAchievements = Achievement::all();
        $userAchievementIds = $user->achievements->pluck('id')->toArray();

        $recentSessions = QuizSession::where('user_id', $user->id)
            ->where('status', 'completed')
            ->with('category')
            ->latest()
            ->limit(5)
            ->get();

        $userJokes = $user->jokes()->latest()->limit(5)->get();

        $isOwnProfile = Auth::check() && Auth::id() === $user->id;
        $jokeCount = $user->jokes()->where('status', 'published')->count();
        $completedSessionCount = $user->quizSessions()->where('status', 'completed')->count();

        return view('profile.show', compact('user', 'allAchievements', 'userAchievementIds', 'recentSessions', 'userJokes', 'isOwnProfile', 'jokeCount', 'completedSessionCount'));
    }

    public function edit(): View
    {
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'string', 'max:50'],
        ]);

        $user->update($validated);

        return redirect()->route('profile.show')->with('success', 'Profil warkop berhasil diperbarui!');
    }
}
