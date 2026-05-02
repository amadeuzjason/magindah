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
        <div class="flex items-center gap-2 overflow-x-auto pb-1 sm:pb-0">
            <button onclick="setTab('submitted')" id="tab-submitted" class="px-4 py-2 rounded-lg bg-blue-500/20 text-blue-400 font-semibold text-sm transition-all focus:outline-none whitespace-nowrap">Submitted / Pending</button>
            <button onclick="setTab('approved')" id="tab-approved" class="px-4 py-2 rounded-lg bg-slate-800 text-gray-400 hover:text-gray-200 hover:bg-slate-700 font-semibold text-sm transition-all focus:outline-none whitespace-nowrap">Approve</button>
            <button onclick="setTab('rejected')" id="tab-rejected" class="px-4 py-2 rounded-lg bg-slate-800 text-gray-400 hover:text-gray-200 hover:bg-slate-700 font-semibold text-sm transition-all focus:outline-none whitespace-nowrap">Reject</button>
            <button onclick="setTab('all')" id="tab-all" class="px-4 py-2 rounded-lg bg-slate-800 text-gray-400 hover:text-gray-200 hover:bg-slate-700 font-semibold text-sm transition-all focus:outline-none whitespace-nowrap">All</button>
            
            <span class="px-2 py-0.5 rounded-full bg-slate-700 text-gray-300 text-xs font-bold" id="pendingCountBadge">0</span>
        </div>

        <!-- Toast Notification Container (Fixed) -->
        <div id="toastContainer" class="fixed top-20 right-5 z-50 flex flex-col gap-3 pointer-events-none">
            <!-- Success Toast -->
            <div id="toastSuccess" class="transform translate-x-full transition-all duration-300 ease-in-out bg-gray-800 border border-emerald-500/30 shadow-lg shadow-emerald-900/20 rounded-xl p-4 flex items-center gap-3 w-80 pointer-events-auto">
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
            <div id="toastError" class="transform translate-x-full transition-all duration-300 ease-in-out bg-gray-800 border border-red-500/30 shadow-lg shadow-red-900/20 rounded-xl p-4 flex items-center gap-3 w-80 pointer-events-auto">
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
        
        <div class="flex items-center gap-3">
            <div class="relative">
                <select id="sortOrder" class="appearance-none pl-4 pr-10 py-2 bg-slate-900/50 border border-gray-700/50 rounded-xl text-xs text-gray-300 focus:outline-none focus:border-blue-500/50 cursor-pointer">
                    <option value="newest">Terbaru (Newest)</option>
                    <option value="oldest">Terlama (Oldest)</option>
                    <option value="budget_desc">Budget (High-Low)</option>
                    <option value="name_asc">Nama (A-Z)</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                </div>
            </div>

            <div class="relative">
                <input type="text" id="searchApproval" placeholder="Search proposal..." class="pl-10 pr-4 py-2 bg-slate-900/50 border border-gray-700/50 rounded-xl text-xs text-gray-300 focus:outline-none focus:border-blue-500/50 w-64">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Approval List -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" id="approvalList">
        <!-- Cards will be injected here -->
        <div class="col-span-full text-center py-12 text-gray-500 text-sm">Loading proposals...</div>
    </div>
    
    <!-- Pagination Controls -->
    <div id="paginationControls" class="mt-8 flex justify-center gap-2 flex-wrap"></div>
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
    let currentPage = 1;
    const itemsPerPage = 10;
    let currentTab = 'submitted';
    const currentUser = '{{ session('username') }}';

    function setTab(tab) {
        currentTab = tab;
        currentPage = 1;
        ['submitted', 'approved', 'rejected', 'all'].forEach(t => {
            const el = document.getElementById('tab-' + t);
            if (!el) return;
            if (t === tab) {
                el.className = 'px-4 py-2 rounded-lg bg-blue-500/20 text-blue-400 font-semibold text-sm transition-all focus:outline-none whitespace-nowrap';
            } else {
                el.className = 'px-4 py-2 rounded-lg bg-slate-800 text-gray-400 hover:text-gray-200 hover:bg-slate-700 font-semibold text-sm transition-all focus:outline-none whitespace-nowrap';
            }
        });
        renderProposals();
    }

    function loadProposals() {
        const container = document.getElementById('approvalList');
        container.innerHTML = '<div class="col-span-full text-center py-12 text-gray-500 text-sm">Loading proposals...</div>';
        
        fetch(`/api/data?context=approvals`)
            .then(res => {
                if (!res.ok) throw new Error("HTTP " + res.status);
                return res.json();
            })
            .then(data => {
                allProposals = data.rows || [];
                updateStats(data.counts);
                renderProposals();
            })
            .catch(err => {
                console.error("Error loading proposals:", err);
                container.innerHTML = `<div class="col-span-full text-center py-12 text-red-400 text-sm">Gagal memuat data approval: ${err.message}</div>`;
            });
    }

    function updateStats(counts) {
        if (!counts) return;
        document.getElementById('stat-pending').textContent = counts.submitted || 0;
        document.getElementById('stat-approved').textContent = counts.approved || 0;
        document.getElementById('stat-rejected').textContent = counts.rejected || 0;
    }

    function renderProposals() {
        const container = document.getElementById('approvalList');
        const searchTerm = document.getElementById('searchApproval').value.toLowerCase();
        const sortOrder = document.getElementById('sortOrder').value;
        
        // Filter by Tab
        let filtered = allProposals.filter(p => {
            const status = (p.STATUS || 'SUBMITTED').toUpperCase();
            if (currentTab === 'submitted') return status !== 'APPROVED' && status !== 'REJECTED';
            if (currentTab === 'approved') return status === 'APPROVED';
            if (currentTab === 'rejected') return status === 'REJECTED';
            return true; // 'all'
        });

        // Filter by Search
        filtered = filtered.filter(p => {
            const prog = (p.PROGRAM || '').toLowerCase();
            const nop = (p.NOP || '').toLowerCase();
            return prog.includes(searchTerm) || nop.includes(searchTerm);
        });

        // Update Badge
        document.getElementById('pendingCountBadge').textContent = filtered.length;

        // Sort
        filtered.sort((a, b) => {
            if (sortOrder === 'newest') {
                return new Date(b.ingest_timestamp || 0) - new Date(a.ingest_timestamp || 0);
            } else if (sortOrder === 'oldest') {
                return new Date(a.ingest_timestamp || 0) - new Date(b.ingest_timestamp || 0);
            } else if (sortOrder === 'budget_desc') {
                return (parseFloat(b.BUDGET) || 0) - (parseFloat(a.BUDGET) || 0);
            } else if (sortOrder === 'name_asc') {
                return (a.PROGRAM || '').localeCompare(b.PROGRAM || '');
            }
            return 0;
        });

        const totalPages = Math.ceil(filtered.length / itemsPerPage);
        if (currentPage > totalPages) currentPage = totalPages || 1;
        
        const start = (currentPage - 1) * itemsPerPage;
        const pagedData = filtered.slice(start, start + itemsPerPage);

        const pageContainer = document.getElementById('paginationControls');
        pageContainer.innerHTML = '';

        if (filtered.length === 0) {
            container.innerHTML = `<div class="col-span-full flex flex-col items-center justify-center py-12 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 opacity-20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Belum ada proposal di tab ini.
            </div>`;
            return;
        }

        if (totalPages > 1) {
            const createBtn = (page, text, disabled = false) => {
                const btn = document.createElement('button');
                btn.className = `px-3 py-1.5 text-xs font-medium rounded-lg transition-colors border ${page === currentPage ? 'bg-blue-600 border-blue-500 text-white' : 'bg-slate-800 border-gray-700 text-gray-400 hover:bg-slate-700 hover:text-white'} ${disabled ? 'opacity-50 cursor-not-allowed' : ''}`;
                btn.innerHTML = text;
                if (!disabled) btn.onclick = () => { currentPage = page; renderProposals(); window.scrollTo({top: 0, behavior: 'smooth'}); };
                return btn;
            };

            if (currentPage > 1) pageContainer.appendChild(createBtn(currentPage - 1, '&laquo; Prev'));
            
            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                    pageContainer.appendChild(createBtn(i, i));
                } else if (i === currentPage - 3 || i === currentPage + 3) {
                    const dots = document.createElement('span');
                    dots.className = 'px-2 py-1 text-gray-500';
                    dots.innerText = '...';
                    pageContainer.appendChild(dots);
                }
            }

            if (currentPage < totalPages) pageContainer.appendChild(createBtn(currentPage + 1, 'Next &raquo;'));
        }

        container.innerHTML = pagedData.map(p => {
            const status = (p.STATUS || 'SUBMITTED').toUpperCase();
            let statusBadge = '';
            let actionButtons = '';
            
            const stageText = (p.approval_stage || 'Submitted').replace(/_/g, ' ');
            
            statusBadge = `<span class="px-2 py-1 rounded-md bg-blue-500/10 border border-blue-500/20 text-blue-400 text-[10px] font-bold uppercase tracking-wider">${stageText}</span>`;
            
            if (status === 'APPROVED') {
                 statusBadge = `<span class="px-2 py-1 rounded-md bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">APPROVED</span>`;
            } else if (status === 'REJECTED') {
                 statusBadge = `<span class="px-2 py-1 rounded-md bg-red-500/10 border border-red-500/20 text-red-400 text-[10px] font-bold uppercase tracking-wider">REJECTED</span>`;
            }

            // Approval Logic - Admin CANNOT approve, only non-admin managers can
            const isMBA = ['power', 'cme electrical', 'psb', 'transmisi'].includes((p.KATEGORI || '').toLowerCase());
            let canApprove = false;

            if (currentUser !== 'admin') {
                if (p.approval_stage === 'Manager_NOP') {
                    const nopManagers = ['NOP-MKS', 'NOP-PALU', 'NOP-MANADO'];
                    canApprove = nopManagers.includes(currentUser);
                } else if (p.approval_stage === 'Manager_SQA_MBA') {
                    canApprove = ['manager_sqa', 'manager_mba'].includes(currentUser);
                } else if (p.approval_stage === 'Manager_NOS') {
                    canApprove = ['manager_nos'].includes(currentUser);
                }
            }

            if (status === 'APPROVED' || status === 'REJECTED') {
                canApprove = false;
            }

            if (canApprove) {
                actionButtons = `
                    <div class="flex gap-2 mt-4 pt-4 border-t border-gray-700/50">
                        <button onclick="approve('${p.id}')" class="flex-1 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold transition-all">Approve</button>
                        <button onclick="reject('${p.id}')" class="flex-1 py-2 rounded-lg bg-red-600 hover:bg-red-500 text-white text-xs font-semibold transition-all">Reject</button>
                    </div>
                `;
            }

            // Admin always gets Edit button on ALL proposals
            if (currentUser === 'admin') {
                actionButtons = `
                    <div class="mt-4 pt-4 border-t border-gray-700/30">
                        <a href="/admin/proposal/${p.id}/edit" class="flex items-center justify-center gap-2 w-full py-2 rounded-lg bg-indigo-600/80 hover:bg-indigo-500 text-white text-xs font-semibold transition-all border border-indigo-500/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Proposal
                        </a>
                    </div>
                `;
            }

            // Parse filename
            let filename = 'Document';
            if (p.PROPOSAL) {
                filename = p.PROPOSAL.split(/[/\\]/).pop();
                if (filename.startsWith('proposal_')) {
                     const parts = filename.split('_');
                     if (parts.length >= 3) filename = parts.slice(2).join('_');
                }
            }

            // Format timestamp
            const date = p.ingest_timestamp ? new Date(p.ingest_timestamp).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute:'2-digit' }) : '-';

            // Budget formatting
            const budget = p.BUDGET ? new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(p.BUDGET) : 'Rp 0';

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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>${budget}</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-400">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>${p['ASSIGN BY'] || 'System'}</span>
                    </div>
                </div>

                <div class="flex items-center justify-between relative z-10 mt-3 pt-3 border-t border-gray-700/30">
                        <button onclick="previewDoc('${p.id}', '${filename}', '${p.PROPOSAL}', '${status}')" class="flex items-center gap-2 text-xs text-blue-400 hover:text-blue-300 transition-colors group/link">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover/link:underline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        ${filename}
                    </button>
                    <div class="flex gap-2 items-center">
                            ${status === 'APPROVED' ? `
                            <a href="/proposal/${p.id}/pdf" target="_blank" class="flex items-center gap-1 px-2 py-1 bg-slate-800 border border-gray-600 hover:border-blue-500 rounded text-[10px] text-gray-300 hover:text-white transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Unduh PDF
                            </a>
                            ` : `<span class="text-[10px] text-gray-500 italic">Belum bisa diunduh</span>`}
                    </div>
                </div>

                ${actionButtons}
            </div>
            `;
        }).join('');
    }

    function previewDoc(id, title, path, status) {
        const url = `/proposal/${id}/pdf`;
        const modal = document.getElementById('previewModal');
        const frame = document.getElementById('previewFrame');
        const fallback = document.getElementById('previewFallback');
        const downloadLink = document.getElementById('downloadLink');
        
        document.getElementById('previewTitle').textContent = title;
        
        frame.src = url;
        frame.classList.remove('hidden');
        fallback.classList.add('hidden');
        
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
                // Show success toast
                const toast = document.getElementById('toastSuccess');
                document.getElementById('toastSuccessText').textContent = data.message || `Proposal berhasil di-${status.toLowerCase()}.`;
                toast.classList.remove('translate-x-full');
                setTimeout(() => toast.classList.add('translate-x-full'), 3000);

                // Reload data to reflect changes
                loadProposals();
            } else {
                // Show error toast
                const toast = document.getElementById('toastError');
                document.getElementById('toastErrorText').textContent = data.message;
                toast.classList.remove('translate-x-full');
                setTimeout(() => toast.classList.add('translate-x-full'), 5000);
            }
        })
        .catch(err => {
            console.error(err);
            const toast = document.getElementById('toastError');
            document.getElementById('toastErrorText').textContent = 'Terjadi kesalahan koneksi.';
            toast.classList.remove('translate-x-full');
            setTimeout(() => toast.classList.add('translate-x-full'), 5000);
        });
    }

    document.getElementById('searchApproval').addEventListener('input', () => { currentPage = 1; renderProposals(); });
    document.getElementById('sortOrder').addEventListener('change', () => { currentPage = 1; renderProposals(); });
    document.addEventListener("DOMContentLoaded", () => loadProposals());
</script>
@endpush
@endsection
