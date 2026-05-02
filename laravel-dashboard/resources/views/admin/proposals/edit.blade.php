@extends('layout')

@section('content')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
{{-- SheetJS for Excel parsing --}}
<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>

<style>
    .ql-toolbar.ql-snow { border-color: rgba(55,65,81,0.5); background-color: rgba(31,41,55,0.8); border-top-left-radius: 0.75rem; border-top-right-radius: 0.75rem; }
    .ql-container.ql-snow { border-color: rgba(55,65,81,0.5); background-color: rgba(17,24,39,0.4); border-bottom-left-radius: 0.75rem; border-bottom-right-radius: 0.75rem; color: white; font-family: inherit; min-height: 120px; }
    .ql-snow .ql-stroke { stroke: #9ca3af; }
    .ql-snow .ql-fill, .ql-snow .ql-stroke.ql-fill { fill: #9ca3af; }
    .ql-snow .ql-picker { color: #9ca3af; }
    .tab-btn { padding: 8px 16px; border-radius: 8px; font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: all 0.15s; border: 1px solid transparent; }
    .tab-btn.active { background: rgba(99,102,241,0.2); color: #a5b4fc; border-color: rgba(99,102,241,0.3); }
    .tab-btn:not(.active) { color: #64748b; background: rgba(30,41,59,0.5); }
    .tab-btn:not(.active):hover { color: #94a3b8; }
    .tab-panel { display: none; }
    .tab-panel.active { display: block; }
    /* RAB table preview */
    .rab-preview-table { width: 100%; border-collapse: collapse; font-size: 0.78rem; }
    .rab-preview-table th { background: #1e293b; color: #94a3b8; padding: 8px 10px; text-align: left; border-bottom: 1px solid rgba(100,116,139,0.3); font-size: 0.7rem; text-transform: uppercase; }
    .rab-preview-table td { padding: 7px 10px; border-bottom: 1px solid rgba(100,116,139,0.1); color: #e2e8f0; }
    .rab-preview-table tr:hover td { background: rgba(59,130,246,0.04); }
    .rab-preview-table tfoot td { font-weight: 700; color: #f59e0b; border-top: 1px solid rgba(245,158,11,0.3); }
    .drop-zone {
        border: 2px dashed rgba(99,102,241,0.4);
        border-radius: 12px;
        padding: 32px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: rgba(99,102,241,0.04);
    }
    .drop-zone:hover, .drop-zone.drag-over { border-color: rgba(99,102,241,0.7); background: rgba(99,102,241,0.08); }
</style>

<div class="max-w-5xl mx-auto space-y-8">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('approvals') }}" class="p-2 rounded-lg bg-slate-800/50 hover:bg-slate-700/50 text-slate-400 hover:text-white transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-white">Edit Proposal <span class="text-indigo-400">(Admin Mode)</span></h2>
            <p class="text-sm text-slate-400">Ubah detail proposal termasuk data justifikasi.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.proposal.update', $proposal['id']) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Tab Navigation -->
        <div class="flex items-center gap-2 flex-wrap">
            <button type="button" class="tab-btn active" data-tab="tab-main">📋 Data Utama</button>
            <button type="button" class="tab-btn" data-tab="tab-justifikasi">📝 Justifikasi</button>
            <button type="button" class="tab-btn" data-tab="tab-rab">📊 RAB (Excel)</button>
        </div>

        <!-- TAB 1: Data Utama -->
        <div id="tab-main" class="tab-panel active glass p-8 rounded-3xl border border-gray-700/30 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs text-gray-400 mb-1.5 ml-1">Program</label>
                    <input type="text" name="program" value="{{ old('program', $proposal['PROGRAM']) }}" required
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700/50 text-gray-200 text-sm focus:border-blue-500/50 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1.5 ml-1">NOP</label>
                    <input type="text" name="nop" value="{{ old('nop', $proposal['NOP']) }}" required
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700/50 text-gray-200 text-sm focus:border-blue-500/50 outline-none transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs text-gray-400 mb-1.5 ml-1">Kategori</label>
                    <select name="kategori" required class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700/50 text-gray-200 text-sm focus:border-blue-500/50 outline-none transition-all appearance-none cursor-pointer">
                        @php $kv = old('kategori', strtolower($proposal['KATEGORI'])); @endphp
                        <option value="Productivity" {{ strtolower($kv) == 'productivity' ? 'selected' : '' }}>Productivity</option>
                        <option value="Civil"        {{ strtolower($kv) == 'civil' ? 'selected' : '' }}>Civil</option>
                        <option value="CME"          {{ strtolower($kv) == 'cme' ? 'selected' : '' }}>CME</option>
                        <option value="Optime"       {{ strtolower($kv) == 'optime' ? 'selected' : '' }}>Optime</option>
                        <option value="Power"        {{ strtolower($kv) == 'power' ? 'selected' : '' }}>Power</option>
                        <option value="CME Electrical" {{ strtolower($kv) == 'cme electrical' ? 'selected' : '' }}>CME Electrical</option>
                        <option value="PSB"          {{ strtolower($kv) == 'psb' ? 'selected' : '' }}>PSB</option>
                        <option value="Transmisi"    {{ strtolower($kv) == 'transmisi' ? 'selected' : '' }}>Transmisi</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1.5 ml-1">Budget</label>
                    <input type="number" step="0.01" name="budget" value="{{ old('budget', $proposal['BUDGET']) }}"
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700/50 text-gray-200 text-sm focus:border-blue-500/50 outline-none transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs text-gray-400 mb-1.5 ml-1">Revenue</label>
                    <input type="number" step="0.01" name="revenue" id="admin_revenue" value="{{ old('revenue', $proposal['REVENUE']) }}"
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700/50 text-gray-200 text-sm focus:border-blue-500/50 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1.5 ml-1">Cost</label>
                    <input type="number" step="0.01" name="cost" id="admin_cost" value="{{ old('cost', $proposal['COST']) }}"
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700/50 text-gray-200 text-sm focus:border-blue-500/50 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1.5 ml-1">Profit (auto)</label>
                    <div class="w-full bg-slate-900/80 border border-slate-700/30 rounded-xl px-4 py-2.5 flex items-center justify-between">
                        <span id="admin_profitDisplay" class="font-mono text-emerald-400">—</span>
                        <input type="hidden" name="profit" id="admin_profit" value="{{ old('profit', $proposal['PROFIT']) }}">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs text-gray-400 mb-1.5 ml-1">Incremental 1</label>
                    <input type="number" step="0.01" name="incremental_1" value="{{ old('incremental_1', $proposal['INCREMENTAL 1']) }}"
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700/50 text-gray-200 text-sm focus:border-blue-500/50 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1.5 ml-1">Incremental 2</label>
                    <input type="number" step="0.01" name="incremental_2" value="{{ old('incremental_2', $proposal['INCREMENTAL 2']) }}"
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700/50 text-gray-200 text-sm focus:border-blue-500/50 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1.5 ml-1">Incremental 3</label>
                    <input type="number" step="0.01" name="incremental_3" value="{{ old('incremental_3', $proposal['INCREMENTAL 3']) }}"
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700/50 text-gray-200 text-sm focus:border-blue-500/50 outline-none transition-all">
                </div>
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5 ml-1">Driven Program</label>
                <input type="text" name="driven_program" value="{{ old('driven_program', $proposal['DRIVEN PROGRAM']) }}"
                    class="w-full px-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700/50 text-gray-200 text-sm focus:border-blue-500/50 outline-none transition-all">
            </div>
        </div>

        <!-- TAB 2: Justifikasi (rich text) -->
        <div id="tab-justifikasi" class="tab-panel glass p-8 rounded-3xl border border-gray-700/30 space-y-6">
            @php
                $justSections = [
                    ['key' => 'justifikasi_objective',  'label' => '1. Objective',               'hint' => 'Tulis ringkas, jelas, dan relevan.'],
                    ['key' => 'justifikasi_alasan',     'label' => '2. Alasan Kebutuhan',        'hint' => 'Jelaskan alasan dan urgensi program.'],
                    ['key' => 'justifikasi_distribusi', 'label' => '3. Distribusi Pekerjaan',    'hint' => 'Pembagian pekerjaan dan pihak terlibat.'],
                    ['key' => 'justifikasi_lingkup',    'label' => '4. Lingkup Pekerjaan',       'hint' => 'Rinci lingkup dan batasan scope.'],
                    ['key' => 'justifikasi_summary',    'label' => '7. Executive Summary',       'hint' => 'Ringkas tujuan, manfaat, dan highlight.'],
                ];
            @endphp

            @foreach($justSections as $js)
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-0.5">{{ $js['label'] }}</label>
                <p class="text-xs text-slate-500 mb-2">{{ $js['hint'] }}</p>
                <div id="quill_{{ $js['key'] }}" style="min-height: 100px;"></div>
                <textarea id="{{ $js['key'] }}" name="{{ $js['key'] }}" class="hidden">{{ old($js['key'], $proposal[$js['key']] ?? '') }}</textarea>
            </div>
            @endforeach

            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-0.5">5. Spesifikasi Teknis</label>
                <p class="text-xs text-slate-500 mb-2">Tuliskan spesifikasi teknis yang dibutuhkan.</p>
                <textarea name="justifikasi_teknis" rows="4"
                    class="w-full px-4 py-3 rounded-xl bg-slate-900/80 border border-slate-700/50 text-gray-200 text-sm focus:border-blue-500/50 outline-none transition-all resize-none"
                    placeholder="Spesifikasi teknis...">{{ old('justifikasi_teknis', $proposal['justifikasi_teknis'] ?? '') }}</textarea>
            </div>
        </div>

        <!-- TAB 3: RAB Excel Upload -->
        <div id="tab-rab" class="tab-panel glass p-8 rounded-3xl border border-gray-700/30 space-y-5">
            <div>
                <h3 class="text-base font-semibold text-white mb-1">6. Detail Kebutuhan (RAB)</h3>
                <p class="text-xs text-slate-400">Upload file Excel (.xlsx/.xls) yang berisi tabel BOQ. Kolom yang dibaca: <span class="text-blue-300">PR Item, No, Item Deskripsi, Quantity, UoM, Harga Satuan, Harga Total, Keterangan</span>.</p>
            </div>

            <!-- Drop zone -->
            <div class="drop-zone" id="rabDropZone" onclick="document.getElementById('rabFileInput').click()">
                <input type="file" id="rabFileInput" accept=".xlsx,.xls" class="hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-3 text-indigo-400 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                </svg>
                <p class="text-sm font-medium text-slate-300">Klik untuk pilih file atau drag & drop di sini</p>
                <p class="text-xs text-slate-500 mt-1">Format: .xlsx, .xls</p>
            </div>

            <!-- File indicator -->
            <div id="rabFileIndicator" class="hidden flex items-center gap-3 p-3 rounded-xl bg-indigo-500/10 border border-indigo-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="text-sm text-indigo-300" id="rabFileName">—</span>
                <button type="button" onclick="clearRabFile()" class="ml-auto text-xs text-slate-500 hover:text-red-400 transition-colors">✕ Hapus</button>
            </div>

            <!-- Table preview -->
            <div id="rabPreviewWrapper" class="hidden">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Preview Tabel RAB</span>
                    <span class="text-xs text-slate-500" id="rabRowCount">0 baris</span>
                </div>
                <div class="overflow-x-auto rounded-xl border border-slate-700/40">
                    <table class="rab-preview-table w-full" id="rabPreviewTable">
                        <thead id="rabPreviewHead"></thead>
                        <tbody id="rabPreviewBody"></tbody>
                        <tfoot id="rabPreviewFoot"></tfoot>
                    </table>
                </div>
            </div>

            <!-- Existing RAB display (if any) -->
            @if(!empty($proposal['justifikasi_rab']))
            <div>
                <div class="text-xs text-slate-500 mb-2 uppercase tracking-wider font-semibold">Data RAB Tersimpan</div>
                <div class="rounded-xl border border-slate-700/40 overflow-x-auto p-3 bg-slate-900/40 text-sm text-slate-300">
                    {!! $proposal['justifikasi_rab'] !!}
                </div>
                <p class="text-xs text-slate-500 mt-2">Upload file Excel baru di atas untuk mengganti data RAB ini.</p>
            </div>
            @endif

            <!-- Hidden textarea to store RAB HTML -->
            <textarea name="justifikasi_rab" id="justifikasi_rab" class="hidden">{{ old('justifikasi_rab', $proposal['justifikasi_rab'] ?? '') }}</textarea>
        </div>

        <!-- Submit -->
        <div class="flex justify-end pt-2">
            <button type="submit" class="px-8 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white font-semibold text-sm transition-all shadow-lg shadow-indigo-900/30 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                Simpan Semua Perubahan
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Tab switching ──
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById(btn.dataset.tab).classList.add('active');
        });
    });

    // ── Profit auto-calc ──
    const rev = document.getElementById('admin_revenue');
    const cost = document.getElementById('admin_cost');
    const profitInput = document.getElementById('admin_profit');
    const profitDisplay = document.getElementById('admin_profitDisplay');

    function calcProfit() {
        const r = parseFloat(rev.value) || 0;
        const c = parseFloat(cost.value) || 0;
        let p = 0;
        if (r > 0) p = ((r - c) / r) * 100;
        profitInput.value = p.toFixed(2);
        profitDisplay.textContent = p.toFixed(2) + '%';
        profitDisplay.className = 'font-mono ' + (p < 0 ? 'text-red-400' : 'text-emerald-400');
    }
    if (rev) { rev.addEventListener('input', calcProfit); cost.addEventListener('input', calcProfit); calcProfit(); }

    // ── Quill editors for justifikasi fields ──
    const richFields = [
        'justifikasi_objective',
        'justifikasi_alasan',
        'justifikasi_distribusi',
        'justifikasi_lingkup',
        'justifikasi_summary',
    ];

    const quillInstances = {};
    richFields.forEach(fieldId => {
        const editorEl = document.getElementById('quill_' + fieldId);
        const hiddenEl = document.getElementById(fieldId);
        if (!editorEl || !hiddenEl) return;

        const q = new Quill('#quill_' + fieldId, {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    ['image', 'clean']
                ]
            }
        });

        if (hiddenEl.value) {
            q.root.innerHTML = hiddenEl.value;
        }

        q.on('text-change', () => {
            hiddenEl.value = q.root.innerHTML;
        });

        quillInstances[fieldId] = q;
    });

    // ── RAB Excel Upload ──
    const rabFileInput = document.getElementById('rabFileInput');
    const rabDropZone = document.getElementById('rabDropZone');
    const rabFileIndicator = document.getElementById('rabFileIndicator');
    const rabFileName = document.getElementById('rabFileName');
    const rabPreviewWrapper = document.getElementById('rabPreviewWrapper');
    const rabPreviewHead = document.getElementById('rabPreviewHead');
    const rabPreviewBody = document.getElementById('rabPreviewBody');
    const rabPreviewFoot = document.getElementById('rabPreviewFoot');
    const rabRowCount = document.getElementById('rabRowCount');
    const justifikasiRabTextarea = document.getElementById('justifikasi_rab');

    // Drag events
    rabDropZone.addEventListener('dragover', e => { e.preventDefault(); rabDropZone.classList.add('drag-over'); });
    rabDropZone.addEventListener('dragleave', () => rabDropZone.classList.remove('drag-over'));
    rabDropZone.addEventListener('drop', e => {
        e.preventDefault();
        rabDropZone.classList.remove('drag-over');
        const files = e.dataTransfer.files;
        if (files.length) processRabFile(files[0]);
    });

    rabFileInput.addEventListener('change', function () {
        if (this.files.length) processRabFile(this.files[0]);
    });

    function processRabFile(file) {
        if (!file.name.match(/\.xlsx?$/i)) {
            alert('Hanya file .xlsx atau .xls yang diperkenankan.');
            return;
        }

        rabFileName.textContent = file.name;
        rabFileIndicator.classList.remove('hidden');

        const reader = new FileReader();
        reader.onload = function (e) {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, { type: 'array' });
            const sheet = workbook.Sheets[workbook.SheetNames[0]];
            const rows = XLSX.utils.sheet_to_json(sheet, { header: 1, defval: '' });

            if (!rows.length) {
                alert('Sheet Excel kosong.');
                return;
            }

            // Detect header row (find row with "Item Deskripsi" or similar)
            let headerRowIdx = 0;
            for (let i = 0; i < Math.min(10, rows.length); i++) {
                const rowStr = rows[i].join('|').toLowerCase();
                if (rowStr.includes('item deskripsi') || rowStr.includes('deskripsi') || rowStr.includes('harga satuan')) {
                    headerRowIdx = i;
                    break;
                }
            }

            const headers = rows[headerRowIdx];
            const dataRows = rows.slice(headerRowIdx + 1).filter(r => r.some(c => String(c).trim() !== ''));

            // Build table HTML (for PDF rendering)
            let tableHtml = '<table class="rab-table" style="width:100%;border-collapse:collapse;font-size:9.5pt;">';
            tableHtml += '<thead><tr>';
            headers.forEach(h => {
                tableHtml += `<th style="background:#2c3e50;color:#fff;padding:6px 8px;border:1px solid #ccc;font-weight:bold;">${String(h).trim()}</th>`;
            });
            tableHtml += '</tr></thead><tbody>';

            let grandTotal = null;
            const grandTotalLabel = 'grand total';

            dataRows.forEach(row => {
                const rowArr = headers.map((_, i) => row[i] !== undefined ? row[i] : '');
                const rowText = rowArr.join('').toLowerCase();
                const isGrandTotal = rowText.includes(grandTotalLabel);

                if (isGrandTotal) {
                    // Store for footer
                    grandTotal = rowArr;
                    return;
                }

                tableHtml += '<tr>';
                rowArr.forEach(cell => {
                    const val = String(cell).trim();
                    tableHtml += `<td style="padding:5px 8px;border:1px solid #ddd;vertical-align:top;">${val}</td>`;
                });
                tableHtml += '</tr>';
            });

            tableHtml += '</tbody>';

            if (grandTotal) {
                tableHtml += '<tfoot><tr>';
                grandTotal.forEach(cell => {
                    const val = String(cell).trim();
                    tableHtml += `<td style="padding:6px 8px;border:1px solid #ddd;font-weight:bold;background:#eef8ff;">${val}</td>`;
                });
                tableHtml += '</tr></tfoot>';
            }

            tableHtml += '</table>';

            // Store in hidden textarea
            justifikasiRabTextarea.value = tableHtml;

            // Render preview
            renderPreview(headers, dataRows.filter(r => {
                const rt = r.join('').toLowerCase();
                return !rt.includes(grandTotalLabel);
            }), grandTotal);
        };
        reader.readAsArrayBuffer(file);
    }

    function renderPreview(headers, dataRows, grandTotal) {
        // Head
        rabPreviewHead.innerHTML = '<tr>' + headers.map(h =>
            `<th>${String(h).trim()}</th>`
        ).join('') + '</tr>';

        // Body
        rabPreviewBody.innerHTML = dataRows.map(row => {
            const cells = headers.map((_, i) => row[i] !== undefined ? row[i] : '');
            return '<tr>' + cells.map(c => `<td>${String(c).trim()}</td>`).join('') + '</tr>';
        }).join('');

        // Foot
        if (grandTotal) {
            rabPreviewFoot.innerHTML = '<tr>' + grandTotal.map(c =>
                `<td>${String(c).trim()}</td>`
            ).join('') + '</tr>';
        } else {
            rabPreviewFoot.innerHTML = '';
        }

        rabRowCount.textContent = `${dataRows.length} baris`;
        rabPreviewWrapper.classList.remove('hidden');
    }

    window.clearRabFile = function () {
        rabFileInput.value = '';
        rabFileIndicator.classList.add('hidden');
        rabPreviewWrapper.classList.add('hidden');
        rabFileName.textContent = '—';
        // Keep old saved value in textarea (don't clear unless explicitly uploading new)
    };

});
</script>
@endpush
@endsection
