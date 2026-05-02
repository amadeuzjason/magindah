<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Models\User;

class ProfileController extends Controller
{
    public function show()
    {
        $username = Session::get('username');
        if (!$username) return redirect()->route('login');
        $user = User::where('username', $username)->first();
        if (!$user) $user = (object) ['username' => $username, 'name' => $username, 'email' => '', 'phone_number' => '', 'jabatan' => '', 'signature' => null];
        return view('profile.show', compact('user'));
    }

    public function update(Request $request)
    {
        $username = Session::get('username');
        if (!$username) return redirect()->route('login');
        $user = User::where('username', $username)->first();
        if (!$user) return redirect()->route('login');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone_number' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:255',
            'lokasi_branch' => 'nullable|string|max:255',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->jabatan = $request->jabatan;
        $user->lokasi_branch = $request->lokasi_branch;

        if ($request->hasFile('signature')) {
            $jabatanLower = strtolower($user->jabatan ?? '');
            if (!Str::contains($jabatanLower, ['manager', 'gm', 'vp', 'general manager', 'vice president'])) {
                return back()->withErrors(['signature' => 'Hanya Manager dan ke atas yang dapat mengatur tanda tangan.']);
            }

            $signature = $user->signature ?? null;
            if ($signature && strpos($signature, 'storage/') !== false) {
                $oldPath = str_replace(asset('storage/'), '', $signature);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('signature')->store('signatures', 'public');
            $user->signature = asset('storage/' . $path);
        }

        $user->save();

        Session::put('user_name', $user->name);
        Session::put('user_jabatan', $user->jabatan ?? '');

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $username = Session::get('username');
        if (!$username) return redirect()->route('login');
        $user = User::where('username', $username)->first();
        if (!$user) return redirect()->route('login');

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak cocok.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    public function destroy(Request $request)
    {
        $username = Session::get('username');
        if (!$username) return redirect()->route('login');

        User::where('username', $username)->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Session::forget(['logged_in', 'username', 'user_name', 'user_jabatan']);

        return redirect('/')->with('success', 'Akun berhasil dihapus.');
    }
}
