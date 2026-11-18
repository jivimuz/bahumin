<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $users = User::with('rank')->withCount('jokes')->latest()->paginate(10);

        if ($request->expectsJson()) {
            return response()->json($users);
        }

        return view('admin.users.index');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'is_admin' => ['nullable', 'boolean'],
        ]);

        $isAdmin = $request->boolean('is_admin');
        abort_if($user->is($request->user()) && ! $isAdmin, 403, 'Kamu tidak dapat mencabut akses admin akun sendiri.');

        $user->update([
            'name' => $validated['name'],
            'is_admin' => $isAdmin,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        abort_if($user->is($request->user()), 403, 'Kamu tidak dapat menghapus akun sendiri.');

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
