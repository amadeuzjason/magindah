@extends('layout')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    .user-table-wrapper {
        border-radius: 1.25rem;
        background: rgba(15,23,42,0.7);
        border: 1px solid rgba(100,116,139,0.2);
        backdrop-filter: blur(12px);
        overflow: hidden;
    }
    .user-table th {
        background: rgba(30,41,59,0.9);
        font-size: 0.7rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #94a3b8;
        padding: 14px 20px;
        border-bottom: 1px solid rgba(100,116,139,0.15);
        font-weight: 600;
    }
    .user-table td {
        padding: 14px 20px;
        border-bottom: 1px solid rgba(100,116,139,0.08);
        color: #e2e8f0;
        font-size: 0.85rem;
        vertical-align: middle;
    }
    .user-table tr:last-child td { border-bottom: none; }
    .user-table tr:hover td { background: rgba(59,130,246,0.04); }
    .avatar-circle {
        width: 36px; height: 36px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.85rem;
        flex-shrink: 0;
    }
    .badge-jabatan {
        display: inline-block;
        padding: 2px 10px;
        border-radius: 99px;
        font-size: 0.7rem;
        font-weight: 600;
        background: rgba(99,102,241,0.12);
        color: #a5b4fc;
        border: 1px solid rgba(99,102,241,0.2);
    }
    .btn-edit {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 14px; border-radius: 8px;
        background: rgba(59,130,246,0.12); color: #60a5fa;
        border: 1px solid rgba(59,130,246,0.25);
        font-size: 0.78rem; font-weight: 600;
        transition: all 0.15s; cursor: pointer; text-decoration: none;
    }
    .btn-edit:hover { background: rgba(59,130,246,0.22); color: #93c5fd; }
    .btn-delete {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 14px; border-radius: 8px;
        background: rgba(239,68,68,0.1); color: #f87171;
        border: 1px solid rgba(239,68,68,0.2);
        font-size: 0.78rem; font-weight: 600;
        transition: all 0.15s; cursor: pointer;
    }
    .btn-delete:hover { background: rgba(239,68,68,0.2); color: #fca5a5; }
    .btn-create {
        display: inline-flex; align-items: center; gap-7px;
        padding: 9px 18px; border-radius: 10px;
        background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
        color: white; font-size: 0.85rem; font-weight: 600;
        border: none; cursor: pointer; text-decoration: none;
        box-shadow: 0 4px 15px rgba(99,102,241,0.3);
        transition: all 0.2s; gap: 6px;
    }
    .btn-create:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,0.4); color: white; }
    /* Delete Confirmation Modal */
    .del-modal-backdrop {
        position: fixed; inset: 0; z-index: 9999;
        background: rgba(2,6,23,0.75); backdrop-filter: blur(6px);
        display: flex; align-items: center; justify-content: center;
        opacity: 0; transition: opacity 0.2s;
        pointer-events: none;
    }
    .del-modal-backdrop.show { opacity: 1; pointer-events: all; }
    .del-modal {
        background: #0f172a;
        border: 1px solid rgba(239,68,68,0.3);
        border-radius: 1.25rem;
        padding: 32px;
        max-width: 400px;
        width: 90%;
        text-align: center;
        box-shadow: 0 25px 60px rgba(0,0,0,0.6);
        transform: scale(0.95) translateY(8px);
        transition: transform 0.2s, opacity 0.2s;
    }
    .del-modal-backdrop.show .del-modal { transform: scale(1) translateY(0); }
    .del-icon {
        width: 64px; height: 64px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        background: rgba(239,68,68,0.12); margin: 0 auto 16px;
        border: 1px solid rgba(239,68,68,0.25);
    }
    /* Toast */
    .toast-fixed { position: fixed; top: 80px; right: 20px; z-index: 99999; display: flex; flex-direction: column; gap: 10px; pointer-events: none; }
    .toast-item {
        min-width: 300px; padding: 14px 18px;
        border-radius: 12px; display: flex; align-items: center; gap: 12px;
        pointer-events: all;
        transform: translateX(140%); transition: transform 0.3s ease;
    }
    .toast-item.show { transform: translateX(0); }
    .toast-success { background: #0f172a; border: 1px solid rgba(34,197,94,0.3); }
    .toast-error { background: #0f172a; border: 1px solid rgba(239,68,68,0.3); }
</style>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-white">Manajemen User</h2>
            <p class="text-sm text-slate-400 mt-1">Kelola akun pengguna sistem Magindah.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn-create" id="btn-create-user">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Create User +
        </a>
    </div>

    <!-- Toast -->
    <div class="toast-fixed" id="toastContainer">
        <div class="toast-item toast-success" id="toastSuccess">
            <div style="width:36px;height:36px;border-radius:50%;background:rgba(34,197,94,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-white">Berhasil!</p>
                <p class="text-xs text-slate-400" id="toastSuccessText">Operasi berhasil.</p>
            </div>
        </div>
        <div class="toast-item toast-error" id="toastError">
            <div style="width:36px;height:36px;border-radius:50%;background:rgba(239,68,68,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-white">Gagal!</p>
                <p class="text-xs text-slate-400" id="toastErrorText">Terjadi kesalahan.</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('success', '{{ session('success') }}');
            });
        </script>
    @endif
    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('error', '{{ session('error') }}');
            });
        </script>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <div class="glass p-4 rounded-2xl border border-gray-700/30">
            <div class="text-xs text-slate-400 uppercase tracking-wider mb-2">Total User</div>
            <div class="text-3xl font-bold text-white">{{ $users->count() }}</div>
        </div>
        <div class="glass p-4 rounded-2xl border border-gray-700/30">
            <div class="text-xs text-slate-400 uppercase tracking-wider mb-2">Dengan Jabatan</div>
            <div class="text-3xl font-bold text-white">{{ $users->whereNotNull('jabatan')->where('jabatan', '!=', '')->count() }}</div>
        </div>
        <div class="glass p-4 rounded-2xl border border-gray-700/30">
            <div class="text-xs text-slate-400 uppercase tracking-wider mb-2">Dengan Signature</div>
            <div class="text-3xl font-bold text-white">{{ $users->whereNotNull('signature')->where('signature', '!=', '')->count() }}</div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="relative">
        <input type="text" id="userSearch" placeholder="Cari nama, username, atau email..."
            class="w-full pl-10 pr-4 py-2.5 bg-slate-900/60 border border-slate-700/50 rounded-xl text-sm text-gray-300 focus:outline-none focus:border-blue-500/50 transition-all">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 absolute left-3 top-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
    </div>

    <!-- Users Table -->
    <div class="user-table-wrapper">
        <table class="user-table w-full" id="usersTable">
            <thead>
                <tr>
                    <th class="text-left">#</th>
                    <th class="text-left">Pengguna</th>
                    <th class="text-left">Email</th>
                    <th class="text-left">Jabatan</th>
                    <th class="text-left">Lokasi / Branch</th>
                    <th class="text-left">No. Telepon</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                @forelse($users as $index => $user)
                <tr class="user-row" data-name="{{ strtolower($user->name) }}" data-username="{{ strtolower($user->username) }}" data-email="{{ strtolower($user->email) }}">
                    <td class="text-slate-500 font-mono text-xs">{{ $users->firstItem() + $index }}</td>
                    <td>
                        <div class="flex items-center gap-3">
                            @php
                                $colors = ['#6366f1','#3b82f6','#10b981','#f59e0b','#ec4899','#8b5cf6'];
                                $color = $colors[$index % count($colors)];
                                $initials = strtoupper(substr($user->name, 0, 1));
                                if (strpos($user->name, ' ') !== false) {
                                    $parts = explode(' ', $user->name);
                                    $initials = strtoupper(substr($parts[0], 0, 1) . substr($parts[count($parts)-1], 0, 1));
                                }
                            @endphp
                            <div class="avatar-circle" style="background: {{ $color }}22; color: {{ $color }}; border: 1.5px solid {{ $color }}44;">
                                {{ $initials }}
                            </div>
                            <span class="font-medium text-white">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="text-slate-300">{{ $user->email }}</td>
                    <td>
                        @if($user->jabatan)
                            <span class="badge-jabatan">{{ $user->jabatan }}</span>
                        @else
                            <span class="text-slate-600 text-xs italic">—</span>
                        @endif
                    </td>
                    <td class="text-slate-300 text-xs">
                        {{ $user->lokasi_branch ?: '—' }}
                    </td>
                    <td class="text-slate-300 font-mono text-xs">{{ $user->phone_number ?: '—' }}</td>
                    <td>
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-edit" title="Edit User">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>
                            @if($user->username !== 'admin')
                            <button type="button" class="btn-delete"
                                onclick="openDeleteModal({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                title="Hapus User">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus
                            </button>
                            @else
                            <span class="text-xs text-slate-600 italic px-2">Protected</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-12 text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-3 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Belum ada user.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-slate-800/60 bg-slate-900/20">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="del-modal-backdrop" id="deleteModal" role="dialog" aria-modal="true">
    <div class="del-modal">
        <div class="del-icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
        </div>
        <h3 class="text-lg font-bold text-white mb-2">Konfirmasi Hapus</h3>
        <p class="text-sm text-slate-400 mb-1">Anda akan menghapus user:</p>
        <p class="text-base font-semibold text-red-300 mb-5" id="deleteUserName">—</p>
        <p class="text-xs text-slate-500 mb-6">Tindakan ini tidak dapat dibatalkan. Semua data yang terkait dengan user ini akan dihapus secara permanen.</p>
        
        <div class="flex gap-3 justify-center">
            <button type="button" onclick="closeDeleteModal()"
                class="flex-1 py-2.5 rounded-xl border border-slate-700 text-slate-300 hover:bg-slate-800 transition-colors text-sm font-medium">
                Batal
            </button>
            <form id="deleteForm" method="POST" style="flex:1;">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="w-full py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white text-sm font-semibold transition-all shadow-lg shadow-red-900/30">
                    Ya, Hapus User
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Delete Modal
    function openDeleteModal(userId, userName) {
        document.getElementById('deleteUserName').textContent = userName;
        document.getElementById('deleteForm').action = `/admin/users/${userId}`;
        const modal = document.getElementById('deleteModal');
        modal.classList.add('show');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('show');
    }

    // Close on backdrop click
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });

    // Close on Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeDeleteModal();
    });

    // Search filter
    document.getElementById('userSearch').addEventListener('input', function() {
        const q = this.value.toLowerCase().trim();
        document.querySelectorAll('.user-row').forEach(row => {
            const name = row.dataset.name || '';
            const username = row.dataset.username || '';
            const email = row.dataset.email || '';
            const match = !q || name.includes(q) || username.includes(q) || email.includes(q);
            row.style.display = match ? '' : 'none';
        });
    });

    // Toast
    function showToast(type, message) {
        const id = type === 'success' ? 'toastSuccess' : 'toastError';
        const textId = type === 'success' ? 'toastSuccessText' : 'toastErrorText';
        const el = document.getElementById(id);
        const textEl = document.getElementById(textId);
        if (textEl) textEl.textContent = message;
        if (el) {
            el.classList.add('show');
            setTimeout(() => el.classList.remove('show'), 4000);
        }
    }
</script>
@endpush
@endsection
