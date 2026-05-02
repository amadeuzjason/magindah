@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <h2 class="text-2xl font-bold text-white mb-6">Pengaturan Profil</h2>

    @if(session('success'))
        <div class="p-4 rounded-lg bg-green-500/10 border border-green-500/20 text-green-400">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Profile Information -->
    <div class="glass p-8 rounded-3xl border border-gray-700/30">
        <h3 class="text-lg font-semibold text-white mb-6">Informasi Profil</h3>
        
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PATCH')

            <div class="flex items-start gap-6">
                @php
                    $isManager = \Illuminate\Support\Str::contains(strtolower($user->jabatan ?? ''), ['manager', 'gm', 'vp', 'general manager', 'vice president']);
                @endphp

                @if($isManager)
                <div class="flex-shrink-0">
                    <label class="block text-sm font-medium text-gray-400 mb-2">Tanda Tangan (TTD)</label>
                    <div class="w-32 h-32 rounded-xl bg-slate-900/50 border-2 border-dashed border-gray-700 flex items-center justify-center overflow-hidden relative group">
                        @if($user->signature)
                            <img src="{{ $user->signature }}" alt="Signature" class="w-full h-full object-contain p-2">
                        @else
                            <span class="text-xs text-gray-500 text-center px-2">Belum ada TTD</span>
                        @endif
                        
                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <input type="file" name="signature" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*" onchange="previewImage(this)">
                    </div>
                    <p class="text-[10px] text-gray-500 mt-2 text-center">Klik untuk upload (Max 2MB)</p>
                </div>
                @endif

                <div class="flex-grow space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-400 mb-1.5 ml-1">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                                class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700/50 text-gray-200 text-sm focus:border-blue-500/50 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1.5 ml-1">Alamat Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700/50 text-gray-200 text-sm focus:border-blue-500/50 outline-none transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-400 mb-1.5 ml-1">No. Telepon</label>
                            <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" 
                                class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700/50 text-gray-200 text-sm focus:border-blue-500/50 outline-none transition-all" placeholder="08xxxxxxxxxx">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1.5 ml-1">Jabatan (Diatur oleh Admin)</label>
                            <input type="text" name="jabatan" value="{{ old('jabatan', $user->jabatan) }}" readonly
                                class="w-full px-4 py-2.5 rounded-xl bg-slate-900/50 border border-slate-700/50 text-gray-400 text-sm focus:outline-none cursor-not-allowed" placeholder="Belum diatur">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs text-gray-400 mb-1.5 ml-1">Lokasi / Branch (Diatur oleh Admin)</label>
                            <input type="text" name="lokasi_branch" value="{{ old('lokasi_branch', $user->lokasi_branch ?? '') }}" readonly
                                class="w-full px-4 py-2.5 rounded-xl bg-slate-900/50 border border-slate-700/50 text-gray-400 text-sm focus:outline-none cursor-not-allowed" placeholder="Belum diatur">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-800/50">
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white font-medium text-sm transition-all shadow-lg shadow-blue-900/20">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- Update Password -->
    <div class="glass p-8 rounded-3xl border border-gray-700/30">
        <div class="flex items-center gap-3 mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <h3 class="text-lg font-semibold text-white">Ubah Password</h3>
        </div>

        <form action="{{ route('profile.password') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label class="block text-xs text-gray-400 mb-1.5 ml-1">Password Saat Ini</label>
                    <input type="password" name="current_password" required 
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700/50 text-gray-200 text-sm focus:border-blue-500/50 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1.5 ml-1">Password Baru</label>
                    <input type="password" name="password" required 
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700/50 text-gray-200 text-sm focus:border-blue-500/50 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1.5 ml-1">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" required 
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700/50 text-gray-200 text-sm focus:border-blue-500/50 outline-none transition-all">
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-800/50">
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-gray-700 hover:bg-gray-600 text-white font-medium text-sm transition-all border border-gray-600 hover:border-gray-500">
                    Update Password
                </button>
            </div>
        </form>
    </div>

    <!-- Delete Account -->
    <div class="glass p-8 rounded-3xl border border-red-900/30 bg-red-900/5">
        <div class="flex items-center gap-3 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            <h3 class="text-lg font-semibold text-red-400">Hapus Akun</h3>
        </div>

        <p class="text-sm text-gray-400 mb-6">
            Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen. Sebelum menghapus akun Anda, harap unduh data atau informasi apa pun yang ingin Anda simpan.
        </p>

        <form action="{{ route('profile.destroy') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini secara permanen?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-6 py-2.5 rounded-lg bg-red-900/50 hover:bg-red-800/50 text-red-400 hover:text-red-300 font-medium text-sm transition-all border border-red-800 hover:border-red-700">
                Hapus Akun Saya
            </button>
        </form>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                // Find parent container
                const container = input.parentElement;
                // Check if img exists
                let img = container.querySelector('img');
                if (!img) {
                    // Create img if not exists (removing span)
                    const span = container.querySelector('span');
                    if (span) span.remove();
                    img = document.createElement('img');
                    img.className = "w-full h-full object-contain p-2";
                    container.insertBefore(img, container.firstChild);
                }
                img.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
