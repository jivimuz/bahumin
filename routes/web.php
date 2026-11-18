<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DummyController as AdminDummyController;
use App\Http\Controllers\Admin\JokeController as AdminJokeController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JokeController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\SeoController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::get('/jokes', [JokeController::class, 'index'])->name('jokes.index');
Route::get('/api/jokes', [CatalogController::class, 'jokes'])->name('api.jokes');
Route::get('/api/leaderboard', [CatalogController::class, 'users'])->name('api.leaderboard');
Route::get('/jokes/{joke}', [JokeController::class, 'show'])->name('jokes.show');
Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');
Route::get('/user/{username}', [ProfileController::class, 'show'])->name('profile.user');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Quiz gameplay
    Route::get('/quiz', [QuizController::class, 'index'])->name('quiz.index');
    Route::post('/quiz/start', [QuizController::class, 'start'])->name('quiz.start');
    Route::get('/quiz/{session}', [QuizController::class, 'show'])->name('quiz.show');
    Route::post('/quiz/{session}/answer', [QuizController::class, 'answer'])->name('quiz.answer');
    Route::post('/quiz/{session}/complete', [QuizController::class, 'complete'])->name('quiz.complete');
    Route::get('/quiz/{session}/result', [QuizController::class, 'result'])->name('quiz.result');
    Route::get('/history', [QuizController::class, 'history'])->name('quiz.history');

    // Community jokes
    Route::get('/jokes-create', [JokeController::class, 'create'])->name('jokes.create');
    Route::post('/jokes', [JokeController::class, 'store'])->name('jokes.store');
    Route::post('/jokes/{joke}/react', [JokeController::class, 'react'])->name('jokes.react');
    Route::post('/jokes/{joke}/comment', [JokeController::class, 'comment'])->name('jokes.comment');
    Route::post('/jokes/{joke}/bookmark', [JokeController::class, 'bookmark'])->name('jokes.bookmark');

    // User profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    Route::get('/', AdminDashboardController::class)->name('dashboard');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::get('/jokes', [AdminJokeController::class, 'index'])->name('jokes.index');
    Route::put('/jokes/{joke}', [AdminJokeController::class, 'update'])->name('jokes.update');
    Route::delete('/jokes/{joke}', [AdminJokeController::class, 'destroy'])->name('jokes.destroy');
    Route::post('/dummy', [AdminDummyController::class, 'store'])->name('dummy.store');
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
});
