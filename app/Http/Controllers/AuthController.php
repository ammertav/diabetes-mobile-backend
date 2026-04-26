<?php

namespace App\Http\Controllers;

use App\Enums\AuthProvider;
use App\Enums\UserType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister(Request $request)
    {
        return view('auth.register');
    }
    public function showLogin(Request $request)
    {
        return view('auth.login');
    }
    public function register() {}
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !$user->type === UserType::ADMIN) {
            dd($user);
            return back()->withErrors([
                'email' => 'Invalid credentials',
            ]);
        }

        $provider = $user->authProviders
            ->firstWhere('provider', AuthProvider::EMAIL);

        if (!$provider) {
            dd($provider);
            return back()->withErrors([
                'email' => 'Email login not available',
            ]);
        }

        if (!Hash::check($validated['password'], $provider->password_hash)) {
            dd($provider->password_hash);
            return back()->withErrors([
                'email' => 'Invalid credentials',
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
