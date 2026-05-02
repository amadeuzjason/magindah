@extends('layout')

@section('content')
<div class="max-w-2xl mx-auto space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.users.index') }}" class="p-2 rounded-lg hover:bg-white/5 text-gray-400 hover:text-white transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-white">Buat User Baru</h2>
            <p class="text-sm text-slate-400 mt-0.5">Tambahkan akun pengguna baru ke sistem.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-lg bg-green-500/10 border border-green-500/20 text-green-400 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="glass p-8 rounded-3xl border border-gray-700/30">
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs text-gray-400 mb-1.5 ml-1">Nama Lengkap <span class="text-red-400">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required 
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700/50 text-gray-200 text-sm focus:border-blue-500/50 outline-none transition-all" placeholder="Nama Lengkap User">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1.5 ml-1">Username <span class="text-red-400">*</span></label>
                        <input type="text" name="username" value="{{ old('username') }}" required 
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700/50 text-gray-200 text-sm focus:border-blue-500/50 outline-none transition-all" placeholder="username_login">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1.5 ml-1">Email <span class="text-red-400">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required 
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700/50 text-gray-200 text-sm focus:border-blue-500/50 outline-none transition-all" placeholder="email@example.com">
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs text-gray-400 mb-1.5 ml-1">No. Telepon</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number') }}" 
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700/50 text-gray-200 text-sm focus:border-blue-500/50 outline-none transition-all" placeholder="08xxxxxxxxxx">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1.5 ml-1">Jabatan</label>
                        <input type="text" name="jabatan" value="{{ old('jabatan') }}" 
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700/50 text-gray-200 text-sm focus:border-blue-500/50 outline-none transition-all" placeholder="Contoh: Staff Admin">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1.5 ml-1">Lokasi Branch</label>
                        <input type="text" name="lokasi_branch" value="{{ old('lokasi_branch') }}" 
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700/50 text-gray-200 text-sm focus:border-blue-500/50 outline-none transition-all" placeholder="Contoh: NOP Makassar">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1.5 ml-1">Password Default <span class="text-red-400">*</span></label>
                        <input type="text" name="password" required 
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700/50 text-gray-200 text-sm focus:border-blue-500/50 outline-none transition-all" placeholder="Minimal 8 karakter">
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-6 border-t border-gray-800/50">
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white font-medium text-sm transition-all shadow-lg shadow-blue-900/20 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Buat User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
