@extends('layout')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('approvals') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-slate-800 border border-slate-700 text-slate-300 hover:text-white hover:bg-slate-700 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
        <h1 class="text-2xl font-bold text-white">Detail Proposal</h1>
        <div class="ml-auto">
            <a href="{{ route('proposal.pdf', ['id' => $proposal['id']]) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-500/20 border border-blue-500/50 text-blue-300 hover:bg-blue-500/30 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Lihat PDF
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="glass rounded-xl p-5 border border-slate-700/60 shadow-lg">
                <h3 class="text-sm font-semibold text-slate-300 uppercase tracking-wider mb-4 border-b border-slate-700/60 pb-2">Informasi Umum</h3>
                
                <div class="space-y-4">
                    <div>
                        <div class="text-xs text-slate-500 mb-1">NOP</div>
                        <div class="font-medium text-white">{{ $proposal['NOP'] ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 mb-1">Nama Program</div>
                        <div class="font-medium text-white">{{ $proposal['PROGRAM'] ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 mb-1">Kategori</div>
                        <div class="inline-flex px-2 py-1 rounded text-xs font-medium bg-slate-800 text-slate-300 border border-slate-700">{{ $proposal['KATEGORI'] ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 mb-1">Status</div>
                        @php
                            $status = strtoupper($proposal['STATUS'] ?? 'SUBMITTED');
                            $colorClass = 'bg-yellow-500/20 text-yellow-300 border-yellow-500/30';
                            if ($status === 'APPROVED') $colorClass = 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30';
                            if ($status === 'REJECTED') $colorClass = 'bg-red-500/20 text-red-300 border-red-500/30';
                        @endphp
                        <div class="inline-flex px-2 py-1 rounded text-xs font-medium border {{ $colorClass }}">
                            {{ $status }}
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 mb-1">Tahap Approval</div>
                        <div class="text-sm text-blue-300">{{ str_replace('_', ' ', $proposal['approval_stage'] ?? 'Submitted') }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 mb-1">Stage Approval</div>
                        <div class="text-sm font-semibold text-indigo-400">
                            @php
                                $stage = $proposal['approval_stage'] ?? 'Submitted';
                                if ($stage === 'Manager_SQA_MBA') {
                                    $kat = $proposal['KATEGORI'] ?? '';
                                    if (in_array($kat, ['Productivity', 'Civil', 'CME', 'Optime'])) $stage = 'Manager SQA';
                                    elseif (in_array($kat, ['Power', 'CME Electrical', 'PSB', 'Transmisi'])) $stage = 'Manager MBA';
                                    else $stage = 'Manager SQA/MBA';
                                } else {
                                    $stage = str_replace('_', ' ', $stage);
                                }
                            @endphp
                            {{ $stage }}
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 mb-1">Diajukan Oleh</div>
                        <div class="text-sm text-slate-300">{{ $proposal['ASSIGN BY'] ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 mb-1">Driven Program</div>
                        <div class="text-sm text-white font-medium">{{ $proposal['DRIVEN PROGRAM'] ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <div class="glass rounded-xl p-5 border border-slate-700/60 shadow-lg">
                <h3 class="text-sm font-semibold text-slate-300 uppercase tracking-wider mb-4 border-b border-slate-700/60 pb-2">Finansial</h3>
                
                <div class="space-y-4">
                    <div>
                        <div class="text-xs text-slate-500 mb-1">Budget</div>
                        <div class="font-mono text-white text-sm">Rp {{ number_format($proposal['BUDGET'] ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 mb-1">Revenue</div>
                        <div class="font-mono text-emerald-400 text-sm">Rp {{ number_format($proposal['REVENUE'] ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 mb-1">Cost</div>
                        <div class="font-mono text-red-400 text-sm">Rp {{ number_format($proposal['COST'] ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 mb-1">Profit</div>
                        @php
                            $profit = $proposal['PROFIT'] ?? 0;
                            $profitColor = $profit < 0 ? 'text-red-400' : 'text-emerald-400';
                        @endphp
                        <div class="font-mono {{ $profitColor }} text-sm">{{ $profit }}%</div>
                    </div>
                    <div class="pt-2 border-t border-slate-700/40">
                        <div class="text-[10px] text-slate-500 uppercase tracking-tighter mb-2">Revenue Incremental</div>
                        <div class="grid grid-cols-1 gap-3">
                            <div>
                                <div class="text-[10px] text-slate-500 mb-0.5">Incremental 1</div>
                                <div class="font-mono text-white text-xs">Rp {{ number_format($proposal['INCREMENTAL 1'] ?? 0, 0, ',', '.') }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] text-slate-500 mb-0.5">Incremental 2</div>
                                <div class="font-mono text-white text-xs">Rp {{ number_format($proposal['INCREMENTAL 2'] ?? 0, 0, ',', '.') }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] text-slate-500 mb-0.5">Incremental 3</div>
                                <div class="font-mono text-white text-xs">Rp {{ number_format($proposal['INCREMENTAL 3'] ?? 0, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="md:col-span-2 space-y-6">
            <div class="glass rounded-xl p-6 border border-slate-700/60 shadow-lg">
                <div class="flex items-center justify-between mb-6 border-b border-slate-700/60 pb-3">
                    <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Justifikasi Program
                    </h3>
                </div>
                
                <div id="textJustification" class="space-y-8">
                    <!-- Section 1 -->
                    <div class="prose prose-invert max-w-none">
                        <h4 class="text-blue-300 border-b border-slate-800 pb-2 mb-3">1. Objective</h4>
                        <div class="text-slate-300 text-sm leading-relaxed">
                            @if(!empty($proposal['justifikasi_objective']))
                                {!! $proposal['justifikasi_objective'] !!}
                            @else
                                <span class="text-slate-500 italic">Belum diisi.</span>
                            @endif
                        </div>
                    </div>

                    <!-- Section 2 -->
                    <div class="prose prose-invert max-w-none">
                        <h4 class="text-blue-300 border-b border-slate-800 pb-2 mb-3">2. Alasan Kebutuhan</h4>
                        <div class="text-slate-300 text-sm leading-relaxed">
                            @if(!empty($proposal['justifikasi_alasan']))
                                {!! $proposal['justifikasi_alasan'] !!}
                            @else
                                <span class="text-slate-500 italic">Belum diisi.</span>
                            @endif
                        </div>
                    </div>

                    <!-- Section 3 -->
                    <div class="prose prose-invert max-w-none">
                        <h4 class="text-blue-300 border-b border-slate-800 pb-2 mb-3">3. Distribusi Pekerjaan</h4>
                        <div class="text-slate-300 text-sm leading-relaxed">
                            @if(!empty($proposal['justifikasi_distribusi']))
                                {!! $proposal['justifikasi_distribusi'] !!}
                            @else
                                <span class="text-slate-500 italic">Belum diisi.</span>
                            @endif
                        </div>
                    </div>

                    <!-- Section 4 -->
                    <div class="prose prose-invert max-w-none">
                        <h4 class="text-blue-300 border-b border-slate-800 pb-2 mb-3">4. Lingkup Pekerjaan</h4>
                        <div class="text-slate-300 text-sm leading-relaxed">
                            @if(!empty($proposal['justifikasi_lingkup']))
                                {!! $proposal['justifikasi_lingkup'] !!}
                            @else
                                <span class="text-slate-500 italic">Belum diisi.</span>
                            @endif
                        </div>
                    </div>

                    <!-- Section 5 -->
                    <div class="prose prose-invert max-w-none">
                        <h4 class="text-blue-300 border-b border-slate-800 pb-2 mb-3">5. Spesifikasi Teknis</h4>
                        <div class="text-slate-300 text-sm leading-relaxed">@if(!empty($proposal['justifikasi_teknis'])){!! $proposal['justifikasi_teknis'] !!}@else<span class="text-slate-500 italic">Belum diisi.</span>@endif</div>
                    </div>

                    <!-- Section 6 -->
                    <div class="prose prose-invert max-w-none overflow-x-auto">
                        <h4 class="text-blue-300 border-b border-slate-800 pb-2 mb-3">6. Detail Kebutuhan (RAB)</h4>
                        <div class="text-slate-300 text-sm leading-relaxed bg-slate-900/50 p-4 rounded-xl border border-slate-800">
                            @if(!empty($proposal['justifikasi_rab']))
                                {!! str_replace('<table', '<table class="w-full text-left border-collapse" style="min-width: 600px;"', 
                                    str_replace('<th', '<th class="border border-slate-700 bg-slate-800 p-2 text-slate-200"', 
                                    str_replace('<td', '<td class="border border-slate-700 p-2"', $proposal['justifikasi_rab']))) !!}
                            @else
                                <span class="text-slate-500 italic">Belum diisi.</span>
                            @endif
                        </div>
                    </div>

                    <!-- Section 7 -->
                    <div class="prose prose-invert max-w-none">
                        <h4 class="text-blue-300 border-b border-slate-800 pb-2 mb-3">7. Executive Summary Program</h4>
                        <div class="text-slate-300 text-sm leading-relaxed">
                            @if(!empty($proposal['justifikasi_summary']))
                                {!! $proposal['justifikasi_summary'] !!}
                            @else
                                <span class="text-slate-500 italic">Belum diisi.</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PDF Modal -->
<div id="pdfModal" class="fixed inset-0 z-50 hidden bg-slate-900/90 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-slate-800 rounded-2xl w-full max-w-5xl h-[90vh] flex flex-col border border-gray-700 shadow-2xl overflow-hidden">
        <div class="flex justify-between items-center p-4 border-b border-gray-700 bg-slate-800/80">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                </svg>
                Preview PDF Proposal
            </h3>
            <button onclick="closePdfModal()" class="text-gray-400 hover:text-white transition-colors p-1 rounded-lg hover:bg-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="flex-1 bg-gray-900">
            <iframe id="pdfFrame" class="w-full h-full border-0" src=""></iframe>
        </div>
    </div>
</div>

<script>
function openPdfModal() {
    const modal = document.getElementById('pdfModal');
    const frame = document.getElementById('pdfFrame');
    frame.src = "{{ route('proposal.pdf', ['id' => $proposal['id']]) }}";
    modal.classList.remove('hidden');
    document.documentElement.classList.add('overflow-hidden');
}

function closePdfModal() {
    const modal = document.getElementById('pdfModal');
    const frame = document.getElementById('pdfFrame');
    modal.classList.add('hidden');
    frame.src = "";
    document.documentElement.classList.remove('overflow-hidden');
}

// Update the original Lihat PDF link to call openPdfModal
document.addEventListener('DOMContentLoaded', function() {
    const pdfBtn = document.querySelector('a[href="{{ route('proposal.pdf', ['id' => $proposal['id']]) }}"]');
    if (pdfBtn) {
        pdfBtn.addEventListener('click', function(e) {
            e.preventDefault();
            openPdfModal();
        });
    }
});
</script>
@endsection
