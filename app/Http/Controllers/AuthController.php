<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        $credentials = $request->validate([
            'email' => []
        ]);
    }

    public function logout() {}
}
