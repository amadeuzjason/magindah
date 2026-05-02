@extends('layout')

@section('content')
<div class="max-w-2xl mx-auto space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.users.index') }}" class="p-2 rounded-lg bg-slate-800/50 hover:bg-slate-700/50 text-gray-400 hover:text-white transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-white">Edit User</h2>
            <p class="text-sm text-slate-400 mt-0.5">Perbarui informasi akun <span class="text-blue-400 font-medium">{{ $user->name }}</span>.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="glass p-8 rounded-3xl border border-gray-700/30">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs text-gray-400 mb-1.5 ml-1">Nama Lengkap <span class="text-red-400">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700/50 text-gray-200 text-sm focus:border-blue-500/50 outline-none transition-all"
                            placeholder="Nama Lengkap">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1.5 ml-1">Username <span class="text-red-400">*</span></label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700/50 text-gray-200 text-sm focus:border-blue-500/50 outline-none transition-all"
                            placeholder="username_login">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1.5 ml-1">Email <span class="text-red-400">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700/50 text-gray-200 text-sm focus:border-blue-500/50 outline-none transition-all"
                            placeholder="email@example.com">
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs text-gray-400 mb-1.5 ml-1">No. Telepon</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700/50 text-gray-200 text-sm focus:border-blue-500/50 outline-none transition-all"
                            placeholder="08xxxxxxxxxx">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1.5 ml-1">Jabatan</label>
                        <input type="text" name="jabatan" value="{{ old('jabatan', $user->jabatan) }}"
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700/50 text-gray-200 text-sm focus:border-blue-500/50 outline-none transition-all"
                            placeholder="Contoh: Staff Admin">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1.5 ml-1">Lokasi Branch</label>
                        <input type="text" name="lokasi_branch" value="{{ old('lokasi_branch', $user->lokasi_branch) }}"
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700/50 text-gray-200 text-sm focus:border-blue-500/50 outline-none transition-all"
                            placeholder="Contoh: NOP Makassar">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1.5 ml-1">Password Baru
                            <span class="text-slate-500 font-normal">(kosongkan jika tidak diubah)</span>
                        </label>
                        <input type="password" name="password"
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700/50 text-gray-200 text-sm focus:border-blue-500/50 outline-none transition-all"
                            placeholder="Minimal 8 karakter">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-gray-800/50">
                <a href="{{ route('admin.users.index') }}" 
                    class="px-5 py-2.5 rounded-lg border border-slate-700 text-slate-300 hover:bg-slate-800 transition-colors text-sm font-medium">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2.5 rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-semibold text-sm transition-all shadow-lg shadow-blue-900/20 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
