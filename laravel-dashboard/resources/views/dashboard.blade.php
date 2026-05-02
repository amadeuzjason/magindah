@extends('layout')

@section('content')
<div class="flex flex-col gap-8 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-white tracking-wide">Dashboard Magindah</h2>
            <p class="text-sm text-gray-400">Ringkasan statistik proposal dan navigasi cepat.</p>
        </div>
        <div class="flex items-center gap-3 bg-slate-900/50 p-1.5 rounded-xl border border-gray-800/50">
            <a href="{{ route('magindah.show') }}" class="px-4 py-2 rounded-lg text-xs font-semibold bg-blue-600 hover:bg-blue-500 text-white transition-colors flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Buat Proposal
            </a>
            <a href="{{ route('approvals') }}" class="px-4 py-2 rounded-lg text-xs font-semibold bg-slate-800 hover:bg-slate-700 text-white transition-colors flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                </svg>
                Approvals
            </a>
            <a href="{{ route('guide') }}" class="px-4 py-2 rounded-lg text-xs font-semibold bg-slate-800 hover:bg-slate-700 text-white transition-colors flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                Guide
            </a>
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

    <!-- Recent Proposals -->
    <div class="glass p-6 rounded-3xl border border-gray-700/30">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-lg font-bold text-white">Proposal Terbaru</h3>
                <p class="text-xs text-gray-400">Daftar proposal yang baru saja diajukan.</p>
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
                        <th class="px-4 py-3 font-semibold rounded-tr-lg text-right">Tanggal</th>
                    </tr>
                </thead>
                <tbody id="recentProposalsTable" class="divide-y divide-gray-800/50">
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500 text-xs">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let nopChartInstance = null;
    let kategoriChartInstance = null;

    document.addEventListener("DOMContentLoaded", () => {
        fetchData();
    });

    function fetchData() {
        fetch("/api/data")
            .then(res => res.json())
            .then(data => {
                const rows = data.rows || [];
                const counts = data.counts || { all: 0, submitted: 0, approved: 0, rejected: 0 };
                
                // Update stats
                document.getElementById('stat-total').textContent = counts.all;
                document.getElementById('stat-pending').textContent = counts.submitted;
                document.getElementById('stat-approved').textContent = counts.approved;
                document.getElementById('stat-rejected').textContent = counts.rejected;

                // Prepare Chart Data
                const nopCounts = {};
                const kategoriCounts = {};

                rows.forEach(r => {
                    const nop = r.NOP || 'Unknown';
                    const kat = r.KATEGORI || 'Unknown';
                    
                    nopCounts[nop] = (nopCounts[nop] || 0) + 1;
                    kategoriCounts[kat] = (kategoriCounts[kat] || 0) + 1;
                });

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
            tbody.innerHTML = `<tr><td colspan="5" class="px-4 py-8 text-center text-gray-500 text-xs">Belum ada proposal.</td></tr>`;
            return;
        }

        tbody.innerHTML = recent.map(p => {
            const status = (p.STATUS || 'SUBMITTED').toUpperCase();
            let statusClass = 'text-blue-400 bg-blue-400/10 border-blue-400/20';
            if (status === 'APPROVED') statusClass = 'text-emerald-400 bg-emerald-400/10 border-emerald-400/20';
            if (status === 'REJECTED') statusClass = 'text-red-400 bg-red-400/10 border-red-400/20';
            
            const dateStr = p.ingest_timestamp ? new Date(p.ingest_timestamp).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : '-';

            return `
                <tr class="hover:bg-slate-800/30 transition-colors">
                    <td class="px-4 py-3 border-b border-gray-800/30"><span class="font-mono text-xs text-gray-400">${p.NOP || '-'}</span></td>
                    <td class="px-4 py-3 border-b border-gray-800/30 font-medium text-white line-clamp-1" style="max-width:200px" title="${p.PROGRAM}">${p.PROGRAM || '-'}</td>
                    <td class="px-4 py-3 border-b border-gray-800/30 text-xs">${p.KATEGORI || '-'}</td>
                    <td class="px-4 py-3 border-b border-gray-800/30">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold tracking-wider uppercase border ${statusClass}">${status}</span>
                    </td>
                    <td class="px-4 py-3 border-b border-gray-800/30 text-right text-xs text-gray-500">${dateStr}</td>
                </tr>
            `;
        }).join('');
    }
</script>
@endpush
