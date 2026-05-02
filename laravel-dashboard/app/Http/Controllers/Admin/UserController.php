<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class UserController extends Controller
{
    private function adminCheck()
    {
        if (Session::get('username') !== 'admin') {
            abort(403, 'Akses ditolak.');
        }
    }

    public function index()
    {
        $this->adminCheck();
        $users = User::orderBy('name')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $this->adminCheck();
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $this->adminCheck();

        $input = $request->all();
        if ($input['username'] === 'auto' || empty($input['username'])) {
            $input['username'] = explode('@', $input['email'])[0];
            // Ensure uniqueness
            $base = $input['username'];
            $counter = 1;
            while (User::where('username', $input['username'])->exists()) {
                $input['username'] = $base . $counter;
                $counter++;
            }
            $request->merge(['username' => $input['username']]);
        }

        $request->validate([
            'name'         => 'required|string|max:255',
            'username'     => 'required|string|max:255|unique:users,username',
            'email'        => 'required|string|email|max:255|unique:users,email',
            'phone_number' => 'nullable|string|max:20',
            'jabatan'      => 'required|string|max:255',
            'lokasi_branch'=> 'required|string|max:255',
            'password'     => 'required|string|min:8',
        ]);

        User::create([
            'name'         => $request->name,
            'username'     => $request->username,
            'email'        => $request->email,
            'phone_number' => $request->phone_number,
            'jabatan'      => $request->jabatan,
            'lokasi_branch'=> $request->lokasi_branch,
            'password'     => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dibuat.');
    }

    public function edit($id)
    {
        $this->adminCheck();
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $this->adminCheck();

        $user = User::findOrFail($id);
        $input = $request->all();
        
        if (isset($input['username']) && $input['username'] === 'auto') {
            $input['username'] = explode('@', $input['email'])[0];
            $base = $input['username'];
            $counter = 1;
            while (User::where('username', $input['username'])->where('id', '!=', $id)->exists()) {
                $input['username'] = $base . $counter;
                $counter++;
            }
            $request->merge(['username' => $input['username']]);
        }

        $request->validate([
            'name'         => 'required|string|max:255',
            'username'     => 'required|string|max:255|unique:users,username,' . $id,
            'email'        => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone_number' => 'nullable|string|max:20',
            'jabatan'      => 'required|string|max:255',
            'lokasi_branch'=> 'required|string|max:255',
            'password'     => 'nullable|string|min:8',
        ]);

        $data = [
            'name'         => $request->name,
            'username'     => $request->username,
            'email'        => $request->email,
            'phone_number' => $request->phone_number,
            'jabatan'      => $request->jabatan,
            'lokasi_branch'=> $request->lokasi_branch,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->adminCheck();

        $user = User::findOrFail($id);

        // Prevent admin from deleting themselves
        if ($user->username === 'admin') {
            return redirect()->route('admin.users.index')->with('error', 'Tidak dapat menghapus akun admin utama.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
