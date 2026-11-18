<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Joke;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'userCount' => User::count(),
            'jokeCount' => Joke::count(),
            'publishedJokeCount' => Joke::where('status', 'published')->count(),
            'categoryCount' => Category::count(),
            'latestUsers' => User::latest()->limit(5)->get(),
            'latestJokes' => Joke::with('user')->latest()->limit(5)->get(),
        ]);
    }
}
