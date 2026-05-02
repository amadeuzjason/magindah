<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Session::has('logged_in')) {
            return redirect()->route('dashboard');
        }
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        try {
            $user = User::where('email', $credentials['email'])->first();
            if ($user) {
                $plainMatches = is_string($user->password) && hash_equals($user->password, $credentials['password']);
                if (Hash::check($credentials['password'], $user->password) || $plainMatches) {
                    if ($plainMatches) {
                        $user->password = Hash::make($credentials['password']);
                        $user->save();
                    }

                    Session::put('logged_in', true);
                    Session::put('username', $user->username);
                    Session::put('user_name', $user->name ?? $user->username);
                    Session::put('user_jabatan', $user->jabatan ?? '');
                    Session::put('user_branch', $user->lokasi_branch ?? '');
                    $request->session()->regenerate();
                    return redirect()->route('dashboard');
                }
            }
        } catch (\Throwable $e) {
            // Keep behavior as invalid credentials
        }

        return back()->with('error', 'Email atau password salah.');
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Session::forget(['logged_in', 'username', 'user_name', 'user_jabatan']);

        return redirect()->route('login');
    }
}
