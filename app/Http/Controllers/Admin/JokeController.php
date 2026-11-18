<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Joke;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JokeController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->expectsJson()) {
            return response()->json(Joke::with(['user', 'category'])->latest()->paginate(10));
        }

        return view('admin.jokes.index', [
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Joke $joke): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:120'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'setup' => ['required', 'string', 'min:10', 'max:1000'],
            'punchline' => ['required', 'string', 'min:3', 'max:1000'],
            'status' => ['required', 'in:published,draft'],
        ]);

        $joke->update($validated);

        return redirect()->route('admin.jokes.index')->with('success', 'Jokes berhasil diperbarui.');
    }

    public function destroy(Joke $joke): RedirectResponse
    {
        $joke->delete();

        return redirect()->route('admin.jokes.index')->with('success', 'Jokes berhasil dihapus.');
    }
}
