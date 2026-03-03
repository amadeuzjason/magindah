@extends('layout')

@section('content')
<div class="space-y-6">
    <!-- Header with Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="glass p-4 rounded-2xl border border-gray-700/30 flex flex-col justify-between">
            <span class="text-xs text-gray-400 uppercase tracking-wider">Pending Approval</span>
            <div class="flex items-end justify-between mt-2">
                <span class="text-2xl font-bold text-white" id="stat-pending">0</span>
                <span class="p-2 rounded-lg bg-amber-500/10 text-amber-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                    </svg>
                </span>
            </div>
        </div>
        <div class="glass p-4 rounded-2xl border border-gray-700/30 flex flex-col justify-between">
            <span class="text-xs text-gray-400 uppercase tracking-wider">Approved Today</span>
            <div class="flex items-end justify-between mt-2">
                <span class="text-2xl font-bold text-white" id="stat-approved">0</span>
                <span class="p-2 rounded-lg bg-emerald-500/10 text-emerald-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
            </div>
        </div>
        <div class="glass p-4 rounded-2xl border border-gray-700/30 flex flex-col justify-between">
            <span class="text-xs text-gray-400 uppercase tracking-wider">Rejected Today</span>
            <div class="flex items-end justify-between mt-2">
                <span class="text-2xl font-bold text-white" id="stat-rejected">0</span>
                <span class="p-2 rounded-lg bg-red-500/10 text-red-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </span>
            </div>
        </div>
    </div>

    <!-- Filters & Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-2 bg-slate-900/50 p-1 rounded-xl border border-gray-700/50">
            <button onclick="filterStatus('all')" class="filter-btn active px-4 py-2 rounded-lg text-xs font-medium text-gray-400 hover:text-white transition-all">All</button>
            <button onclick="filterStatus('submitted')" class="filter-btn px-4 py-2 rounded-lg text-xs font-medium text-gray-400 hover:text-white transition-all">Submitted</button>
            <button onclick="filterStatus('approved')" class="filter-btn px-4 py-2 rounded-lg text-xs font-medium text-gray-400 hover:text-white transition-all">Approved</button>
            <button onclick="filterStatus('rejected')" class="filter-btn px-4 py-2 rounded-lg text-xs font-medium text-gray-400 hover:text-white transition-all">Rejected</button>
        </div>
        
        <div class="relative">
            <input type="text" id="searchApproval" placeholder="Search proposal..." class="pl-10 pr-4 py-2 bg-slate-900/50 border border-gray-700/50 rounded-xl text-xs text-gray-300 focus:outline-none focus:border-blue-500/50 w-64">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </div>

    <!-- Approval List -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" id="approvalList">
        <!-- Cards will be injected here -->
        <div class="col-span-full text-center py-12 text-gray-500 text-sm">Loading proposals...</div>
    </div>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="fixed inset-0 z-50 hidden bg-slate-900/90 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-slate-800 rounded-2xl w-full max-w-5xl h-[85vh] flex flex-col border border-gray-700 shadow-2xl">
        <div class="flex justify-between items-center p-4 border-b border-gray-700">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                </svg>
                <span id="previewTitle">Document Preview</span>
            </h3>
            <button onclick="closePreview()" class="text-gray-400 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="flex-1 bg-gray-900 relative">
            <iframe id="previewFrame" class="w-full h-full border-0" src=""></iframe>
            <div id="previewFallback" class="hidden absolute inset-0 flex items-center justify-center text-gray-400 flex-col gap-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p>Preview tidak tersedia untuk tipe file ini.</p>
                <a id="downloadLink" href="#" target="_blank" class="px-4 py-2 bg-blue-600 rounded-lg text-white text-sm hover:bg-blue-500 transition-colors">Download File</a>
            </div>
        </div>
    </div>
</div>

<style>
    .filter-btn.active { background: #3b82f6; color: white; }
</style>

@push('scripts')
<script>
    let allProposals = [];
    let currentFilter = 'all';

    function loadProposals() {
        fetch("/api/data")
            .then(res => {
                if (!res.ok) throw new Error("HTTP " + res.status);
                return res.json();
            })
            .then(data => {
                // Map columns to objects
                const cols = data.columns;
                allProposals = data.rows.map(row => {
                    return row;
                });
                
                updateStats();
                renderProposals();
            })
            .catch(err => {
                console.error("Error loading proposals:", err);
                document.getElementById('approvalList').innerHTML = `<div class="col-span-full text-center py-12 text-red-400 text-sm">Gagal memuat data approval: ${err.message}</div>`;
            });
    }

    function updateStats() {
        // Status in DB: SUBMITTED, APPROVED, REJECTED
        const pending = allProposals.filter(p => (p.STATUS || 'SUBMITTED').toUpperCase() === 'SUBMITTED').length;
        const approved = allProposals.filter(p => (p.STATUS || '').toUpperCase() === 'APPROVED').length; // Should filter by date=today in real app
        const rejected = allProposals.filter(p => (p.STATUS || '').toUpperCase() === 'REJECTED').length;

        document.getElementById('stat-pending').textContent = pending;
        document.getElementById('stat-approved').textContent = approved;
        document.getElementById('stat-rejected').textContent = rejected;
    }

    function filterStatus(status) {
        currentFilter = status;
        document.querySelectorAll('.filter-btn').forEach(btn => {
            if (btn.textContent.toLowerCase() === status || (status === 'all' && btn.textContent === 'All')) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
        renderProposals();
    }

    function renderProposals() {
        const container = document.getElementById('approvalList');
        const searchTerm = document.getElementById('searchApproval').value.toLowerCase();
        
        let filtered = allProposals.filter(p => {
            const status = (p.STATUS || 'SUBMITTED').toLowerCase();
            const matchesStatus = currentFilter === 'all' ? true : status === currentFilter;
            
            const prog = (p.PROGRAM || '').toLowerCase();
            const nop = (p.NOP || '').toLowerCase();
            const matchesSearch = prog.includes(searchTerm) || nop.includes(searchTerm);
            
            return matchesStatus && matchesSearch;
        });

        if (filtered.length === 0) {
            container.innerHTML = `<div class="col-span-full flex flex-col items-center justify-center py-12 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 opacity-20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="text-sm">Tidak ada proposal ditemukan.</span>
            </div>`;
            return;
        }

        container.innerHTML = filtered.map(p => {
            const status = (p.STATUS || 'SUBMITTED').toUpperCase();
            let statusBadge = '';
            let actionButtons = '';
            
            if (status === 'APPROVED') {
                statusBadge = `<span class="px-2 py-1 rounded-md bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">Approved</span>`;
            } else if (status === 'REJECTED') {
                statusBadge = `<span class="px-2 py-1 rounded-md bg-red-500/10 border border-red-500/20 text-red-400 text-[10px] font-bold uppercase tracking-wider">Rejected</span>`;
            } else {
                statusBadge = `<span class="px-2 py-1 rounded-md bg-blue-500/10 border border-blue-500/20 text-blue-400 text-[10px] font-bold uppercase tracking-wider">Submitted</span>`;
                actionButtons = `
                    <div class="flex gap-2 mt-4 pt-4 border-t border-gray-700/50">
                        <button onclick="approve('${p.id}')" class="flex-1 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold transition-all">Approve</button>
                        <button onclick="reject('${p.id}')" class="flex-1 py-2 rounded-lg bg-red-600 hover:bg-red-500 text-white text-xs font-semibold transition-all">Reject</button>
                    </div>
                `;
            }

            // Parse filename
            let filename = 'Document';
            if (p.PROPOSAL) {
                // Split by forward slash or backslash
                filename = p.PROPOSAL.split(/[/\\]/).pop();
                if (filename.startsWith('proposal_')) {
                     const parts = filename.split('_');
                     if (parts.length >= 3) filename = parts.slice(2).join('_');
                }
            }

            // Format timestamp
            const date = p.ingest_timestamp ? new Date(p.ingest_timestamp).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute:'2-digit' }) : '-';

            return `
            <div class="glass p-5 rounded-2xl border border-gray-700/30 hover:border-blue-500/30 transition-all group relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path></svg>
                </div>
                
                <div class="flex justify-between items-start mb-3 relative z-10">
                    <div class="flex flex-col">
                        <span class="text-[10px] text-gray-500 font-mono mb-1">${p.NOP}</span>
                        <h4 class="text-sm font-bold text-white line-clamp-1" title="${p.PROGRAM}">${p.PROGRAM}</h4>
                    </div>
                    ${statusBadge}
                </div>
                
                <div class="space-y-2 mb-4 relative z-10">
                    <div class="flex items-center gap-2 text-xs text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <span>${p.KATEGORI}</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>${date}</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-400">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>${p['ASSIGN BY'] || 'System'}</span>
                    </div>
                     ${p['APPROVED BY'] ? `
                    <div class="flex items-center gap-2 text-xs ${(p.STATUS || '').toUpperCase() === 'REJECTED' ? 'text-red-400/80' : 'text-emerald-400/80'}">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${(p.STATUS || '').toUpperCase() === 'REJECTED' ? 'M6 18L18 6M6 6l12 12' : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'}" />
                        </svg>
                        <span>${(p.STATUS || '').toUpperCase() === 'REJECTED' ? 'Rejected by' : 'Approved by'} ${p['APPROVED BY']}</span>
                    </div>` : ''}
                </div>

                <div class="flex items-center justify-between relative z-10">
                     <button onclick="previewDoc('${p.PROPOSAL}', '${filename}')" class="flex items-center gap-2 text-xs text-blue-400 hover:text-blue-300 transition-colors group/link">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover/link:underline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        ${filename}
                    </button>
                    <span class="text-[10px] text-gray-600 font-mono">ID: ${p.id}</span>
                </div>

                ${actionButtons}
            </div>
            `;
        }).join('');
    }

    function previewDoc(path, title) {
        if (!path) {
            alert('File proposal tidak ditemukan.');
            return;
        }
        
        // Clean path to be web accessible
        // Use relative path from root
        const url = "/" + path;
        
        const modal = document.getElementById('previewModal');
        const frame = document.getElementById('previewFrame');
        const fallback = document.getElementById('previewFallback');
        const downloadLink = document.getElementById('downloadLink');
        
        document.getElementById('previewTitle').textContent = title;
        
        // Check extension
        const ext = path.split('.').pop().toLowerCase();
        
        if (ext === 'pdf') {
            frame.src = url;
            frame.classList.remove('hidden');
            fallback.classList.add('hidden');
        } else {
            frame.classList.add('hidden');
            fallback.classList.remove('hidden');
            downloadLink.href = url;
        }
        
        modal.classList.remove('hidden');
    }

    function closePreview() {
        document.getElementById('previewModal').classList.add('hidden');
        document.getElementById('previewFrame').src = '';
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
        // Since we don't have a direct API for updating status in this controller setup easily exposed,
        // we might need to create one or use the existing reject endpoint logic if adaptable.
        // But let's assume we use the existing structure.
        // Actually, route list showed: POST /api/reject [DashboardController::class, 'reject']
        // We need an approve endpoint too.
        
        // For now, let's use a generic status update endpoint if we create it, 
        // OR reuse reject for rejection.
        
        // Let's call a new endpoint we will ensure exists.
        
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
                // Update local data
                const idx = allProposals.findIndex(p => p.id == id);
                if (idx !== -1) {
                    allProposals[idx].STATUS = status.toUpperCase();
                    allProposals[idx]['APPROVED BY'] = "{{ session('username', 'Admin') }}";
                    renderProposals();
                    updateStats();
                }
            } else {
                alert('Gagal mengupdate status: ' + data.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan koneksi.');
        });
    }

    document.getElementById('searchApproval').addEventListener('input', renderProposals);
    document.addEventListener("DOMContentLoaded", loadProposals);
</script>
@endpush
@endsection
