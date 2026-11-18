<?php

namespace App\Http\Controllers;

use App\Models\Rank;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginField = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$loginField => $credentials['login'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->is_admin) {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Selamat datang kembali di konsol admin Bahumin.');
            }

            return redirect()->intended(route('home'))->with('success', 'Selamat datang kembali di Warung Bahumin! Siap adu receh hari ini?');
        }

        return back()->withErrors([
            'login' => 'Email/username atau kata sandi tidak cocok dengan catatan warkop.',
        ])->onlyInput('login');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:30', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $initialRank = Rank::where('level', 1)->first();

        $user = User::create([
            'name' => $validated['name'],
            'username' => strtolower($validated['username']),
            'email' => strtolower($validated['email']),
            'password' => Hash::make($validated['password']),
            'rank_id' => $initialRank?->id,
            'total_xp' => 0,
            'streak_days' => 1,
            'bio' => 'Anak baru yang siap menjajal kasta humor di warung.',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('success', 'Selamat bergabung di Warung Bahumin! Gelarmu saat ini: Anak Baru Nongkrong. Gas main kuis sekarang!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('info', 'Kamu sudah keluar dari warkop. Jangan lupa mampir lagi!');
    }
}
