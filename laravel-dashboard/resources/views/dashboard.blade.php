@extends('layout')

@section('content')
<div class="flex flex-col gap-8 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-white tracking-wide">Dashboard Magindah</h2>
            <p class="text-sm text-gray-400">Ringkasan statistik proposal dan aktivitas sistem.</p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="glass p-6 rounded-2xl border border-gray-700/30 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Total Proposal</p>
                <h3 class="text-3xl font-bold text-white" id="stat-total">0</h3>
            </div>
            <div class="p-3 rounded-xl bg-blue-500/10 text-blue-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>
        
        <div class="glass p-6 rounded-2xl border border-gray-700/30 flex items-center justify-between">
            <div>
                <p class="text-xs text-amber-400/80 uppercase tracking-wider mb-1">Pending Approval</p>
                <h3 class="text-3xl font-bold text-white" id="stat-pending">0</h3>
            </div>
            <div class="p-3 rounded-xl bg-amber-500/10 text-amber-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="glass p-6 rounded-2xl border border-gray-700/30 flex items-center justify-between">
            <div>
                <p class="text-xs text-emerald-400/80 uppercase tracking-wider mb-1">Approved</p>
                <h3 class="text-3xl font-bold text-white" id="stat-approved">0</h3>
            </div>
            <div class="p-3 rounded-xl bg-emerald-500/10 text-emerald-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="glass p-6 rounded-2xl border border-gray-700/30 flex items-center justify-between">
            <div>
                <p class="text-xs text-red-400/80 uppercase tracking-wider mb-1">Rejected</p>
                <h3 class="text-3xl font-bold text-white" id="stat-rejected">0</h3>
            </div>
            <div class="p-3 rounded-xl bg-red-500/10 text-red-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        @if(Session::get('username') === 'admin')
        <div class="glass p-6 rounded-2xl border border-gray-700/30 flex items-center justify-between col-span-1 md:col-span-4 lg:col-span-1">
            <div>
                <p class="text-xs text-indigo-400/80 uppercase tracking-wider mb-1">Total Users</p>
                <h3 class="text-3xl font-bold text-white">{{ $totalUsers ?? 0 }}</h3>
            </div>
            <div class="p-3 rounded-xl bg-indigo-500/10 text-indigo-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
        </div>
        @endif
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- NOP Chart -->
        <div class="glass p-6 rounded-3xl border border-gray-700/30">
            <div class="mb-4">
                <h3 class="text-lg font-bold text-white">Distribusi per NOP</h3>
                <p class="text-xs text-gray-400">Jumlah proposal berdasarkan NOP.</p>
            </div>
            <div class="h-64 relative flex justify-center items-center">
                <canvas id="nopChart"></canvas>
            </div>
        </div>

        <!-- Kategori Chart -->
        <div class="glass p-6 rounded-3xl border border-gray-700/30">
            <div class="mb-4">
                <h3 class="text-lg font-bold text-white">Distribusi per Kategori</h3>
                <p class="text-xs text-gray-400">Jumlah proposal berdasarkan kategori.</p>
            </div>
            <div class="h-64 relative flex justify-center items-center">
                <canvas id="kategoriChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Proposals / Proposals to Approve -->
    <div class="glass p-6 rounded-3xl border border-gray-700/30">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-lg font-bold text-white">
                    @if($isApproverManager)
                        Proposal yang perlu diapprove
                    @elseif($isNopManager)
                        Proposal Terbaru
                    @else
                        Proposal Terbaru
                    @endif
                </h3>
                <p class="text-xs text-gray-400">
                    @if($isApproverManager)
                        Daftar proposal yang menunggu tindakan persetujuan Anda.
                    @else
                        Daftar proposal yang baru saja diajukan.
                    @endif
                </p>
            </div>
            <a href="{{ route('approvals') }}" class="text-xs font-semibold text-blue-400 hover:text-blue-300">Lihat Semua &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-300">
                <thead class="bg-gray-800/50 text-gray-400 text-xs">
                    <tr>
                        <th class="px-4 py-3 font-semibold rounded-tl-lg">NOP</th>
                        <th class="px-4 py-3 font-semibold">Nama Program</th>
                        <th class="px-4 py-3 font-semibold">Kategori</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold">Tanggal</th>
                        @if($isApproverManager)
                        <th class="px-4 py-3 font-semibold rounded-tr-lg text-center">Aksi</th>
                        @else
                        <th class="px-4 py-3 font-semibold rounded-tr-lg text-right">Tanggal</th>
                        @endif
                    </tr>
                </thead>
                <tbody id="recentProposalsTable" class="divide-y divide-gray-800/50">
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500 text-xs">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Toast Notification Container (Fixed) -->
        <div id="toastContainer" class="fixed top-20 right-5 z-50 flex flex-col gap-3 pointer-events-none">
            <!-- Success Toast -->
            <div id="toastSuccess" style="display: none;" class="fixed top-20 right-5 z-50 transform transition-all duration-300 ease-in-out bg-gray-800 border border-emerald-500/30 shadow-lg shadow-emerald-900/20 rounded-xl p-4 flex items-center gap-3 w-80 pointer-events-auto">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-white">Berhasil!</h4>
                    <p id="toastSuccessText" class="text-xs text-gray-400">Status proposal diperbarui.</p>
                </div>
            </div>

            <!-- Error Toast -->
            <div id="toastError" style="display: none;" class="fixed top-20 right-5 z-50 transform transition-all duration-300 ease-in-out bg-gray-800 border border-red-500/30 shadow-lg shadow-red-900/20 rounded-xl p-4 flex items-center gap-3 w-80 pointer-events-auto">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-500/10 flex items-center justify-center text-red-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-white">Gagal!</h4>
                    <p id="toastErrorText" class="text-xs text-gray-400">Terjadi kesalahan.</p>
                </div>
            </div>
        </div>
    </div>

    @if(Session::get('username') === 'admin')
    <!-- Admin User Management Table -->
    <div class="glass p-6 rounded-3xl border border-gray-700/30">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-lg font-bold text-white">Manajemen User</h3>
                <p class="text-xs text-gray-400">Ringkasan daftar pengguna terdaftar.</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300">Kelola Semua User &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-300">
                <thead class="bg-gray-800/50 text-gray-400 text-xs">
                    <tr>
                        <th class="px-4 py-3 font-semibold rounded-tl-lg">Nama</th>
                        <th class="px-4 py-3 font-semibold">Email</th>
                        <th class="px-4 py-3 font-semibold">Jabatan</th>
                        <th class="px-4 py-3 font-semibold rounded-tr-lg">Lokasi / Branch</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/50">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-800/30 transition-colors">
                        <td class="px-4 py-3 border-b border-gray-800/30 font-medium text-white">{{ $user->name }}</td>
                        <td class="px-4 py-3 border-b border-gray-800/30 text-xs text-gray-400">{{ $user->email }}</td>
                        <td class="px-4 py-3 border-b border-gray-800/30 text-xs">
                            @if($user->jabatan)
                                <span class="px-2 py-0.5 rounded bg-indigo-500/10 text-indigo-300 border border-indigo-500/20">{{ $user->jabatan }}</span>
                            @else
                                <span class="text-gray-600 italic">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 border-b border-gray-800/30 text-xs text-gray-400">{{ $user->lokasi_branch ?: '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500 text-xs">Belum ada user terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const currentUser = '{{ $username }}';
    const userJabatan = '{{ $jabatan }}';
    const userBranch = '{{ $branch }}';
    const isApproverManager = {{ $isApproverManager ? 'true' : 'false' }};

    document.addEventListener("DOMContentLoaded", () => {
        fetchData();
    });

    function fetchData() {
        fetch("/api/data?context=dashboard")
            .then(res => res.json())
            .then(data => {
                const rows = data.rows || [];
                const counts = data.counts || { all: 0, submitted: 0, approved: 0, rejected: 0 };
                
                // Update stats
                document.getElementById('stat-total').textContent = counts.all;
                document.getElementById('stat-pending').textContent = counts.submitted;
                document.getElementById('stat-approved').textContent = counts.approved;
                document.getElementById('stat-rejected').textContent = counts.rejected;

                // Use global chart data from backend if available
                const nopCounts = data.nop_counts || {};
                const kategoriCounts = data.kategori_counts || {};

                renderChart('nopChart', nopCounts, 'Distribusi NOP');
                renderChart('kategoriChart', kategoriCounts, 'Distribusi Kategori');

                renderRecentProposals(rows);
            })
            .catch(err => {
                console.error("Failed to load dashboard data:", err);
            });
    }

    function renderChart(canvasId, dataObj, label) {
        const labels = Object.keys(dataObj);
        const data = Object.values(dataObj);
        const bgColors = labels.map((_, i) => `hsl(${i * (360 / Math.max(labels.length, 1))}, 70%, 55%)`);

        const ctx = document.getElementById(canvasId);
        if (!ctx) return;
        
        // Pick pie chart for circular representation
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data,
                    backgroundColor: bgColors,
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { color: '#94a3b8', font: { size: 11 }, padding: 15 }
                    }
                }
            }
        });
    }

    function renderRecentProposals(rows) {
        const tbody = document.getElementById('recentProposalsTable');
        
        // Sort by ingest_timestamp desc
        const sorted = [...rows].sort((a, b) => {
            return new Date(b.ingest_timestamp || 0) - new Date(a.ingest_timestamp || 0);
        });

        // Take top 5
        const recent = sorted.slice(0, 5);

        if (recent.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="px-4 py-8 text-center text-gray-500 text-xs">Belum ada proposal.</td></tr>`;
            return;
        }

        tbody.innerHTML = recent.map(p => {
            const status = (p.STATUS || 'SUBMITTED').toUpperCase();
            let statusClass = 'text-blue-400 bg-blue-400/10 border-blue-400/20';
            if (status === 'APPROVED') statusClass = 'text-emerald-400 bg-emerald-400/10 border-emerald-400/20';
            if (status === 'REJECTED') statusClass = 'text-red-400 bg-red-400/10 border-red-400/20';
            
            const dateStr = p.ingest_timestamp ? new Date(p.ingest_timestamp).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : '-';

            // Approval Logic (same as approvals page)
            let canApprove = false;
            if (currentUser !== 'admin' && status !== 'APPROVED' && status !== 'REJECTED') {
                if (p.approval_stage === 'Manager_NOP') {
                    canApprove = (userJabatan === 'Manager NOP' && ['Makassar', 'Kendari', 'Palu'].includes(userBranch));
                    if (!canApprove) canApprove = ['NOP-MKS', 'NOP-PALU', 'NOP-MANADO'].includes(currentUser);
                } else if (p.approval_stage === 'Manager_SQA_MBA') {
                    const isMBA = ['power', 'cme electrical', 'psb', 'transmisi'].includes((p.KATEGORI || '').toLowerCase());
                    if (userJabatan === 'Manager SQA' && !isMBA) canApprove = true;
                    if (userJabatan === 'Manager MBA' && isMBA) canApprove = true;
                    if (!canApprove) canApprove = ['manager_sqa', 'manager_mba'].includes(currentUser);
                } else if (p.approval_stage === 'Manager_NOS') {
                    canApprove = (userJabatan === 'Manager NOS');
                    if (!canApprove) canApprove = (currentUser === 'manager_nos');
                } else if (p.approval_stage === 'GM_RNOP') {
                    canApprove = (userJabatan === 'General Manager' && userBranch === 'RNOP Sulawesi');
                    if (!canApprove) canApprove = (currentUser === 'manager_gm');
                }
            }

            let actionCol = `<td class="px-4 py-3 border-b border-gray-800/30 text-right text-xs text-gray-500">${dateStr}</td>`;
            if (isApproverManager) {
                actionCol = `<td class="px-4 py-3 border-b border-gray-800/30 text-xs text-gray-500">${dateStr}</td>`;
                let buttons = '<span class="text-gray-600 italic">No Action</span>';
                if (canApprove) {
                    buttons = `
                        <div class="flex gap-2 justify-center">
                            <button onclick="approve('${p.id}')" class="px-3 py-1 rounded bg-emerald-600 hover:bg-emerald-500 text-white text-[10px] font-bold transition-all">Approve</button>
                            <button onclick="reject('${p.id}')" class="px-3 py-1 rounded bg-red-600 hover:bg-red-500 text-white text-[10px] font-bold transition-all">Reject</button>
                        </div>
                    `;
                } else if (status === 'APPROVED' || status === 'REJECTED') {
                    buttons = `<span class="text-gray-500">Processed</span>`;
                }
                actionCol += `<td class="px-4 py-3 border-b border-gray-800/30 text-center">${buttons}</td>`;
            }

            return `
                <tr class="hover:bg-slate-800/30 transition-colors">
                    <td class="px-4 py-3 border-b border-gray-800/30"><span class="font-mono text-xs text-gray-400">${p.NOP || '-'}</span></td>
                    <td class="px-4 py-3 border-b border-gray-800/30 font-medium text-white line-clamp-1" style="max-width:200px" title="${p.PROGRAM}">${p.PROGRAM || '-'}</td>
                    <td class="px-4 py-3 border-b border-gray-800/30 text-xs">${p.KATEGORI || '-'}</td>
                    <td class="px-4 py-3 border-b border-gray-800/30">
                        <div class="flex flex-col gap-1">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold tracking-wider uppercase border ${statusClass} inline-block w-fit">${status}</span>
                            <span class="text-[9px] text-gray-500 uppercase tracking-tighter">${(p.approval_stage || 'SUBMITTED').replace(/_/g, ' ')}</span>
                        </div>
                    </td>
                    ${actionCol}
                </tr>
            `;
        }).join('');
    }

    function approve(id) {
        if (!confirm('Setujui proposal ini?')) return;
        updateStatus(id, 'Approved');
    }

    function reject(id) {
        if (!confirm('Tolak proposal ini?')) return;
        updateStatus(id, 'Rejected');
    }

    function updateStatus(id, status) {
        fetch("/api/update-status", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({ id, status })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const toast = document.getElementById('toastSuccess');
                document.getElementById('toastSuccessText').textContent = data.message || `Proposal berhasil di-${status.toLowerCase()}.`;
                toast.style.display = 'flex';
                setTimeout(() => toast.style.display = 'none', 3000);
                fetchData(); // Reload stats and table
            } else {
                const toast = document.getElementById('toastError');
                document.getElementById('toastErrorText').textContent = data.message;
                toast.style.display = 'flex';
                setTimeout(() => toast.style.display = 'none', 5000);
            }
        })
        .catch(err => {
            console.error(err);
            const toast = document.getElementById('toastError');
            document.getElementById('toastErrorText').textContent = 'Terjadi kesalahan koneksi.';
            toast.style.display = 'flex';
            setTimeout(() => toast.style.display = 'none', 5000);
        });
    }
</script>
@endpush
