@extends('layout')

@section('content')
<!-- Add Quill CSS & JS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>

<style>
    /* Quill Editor Overrides for Dark Mode */
    .ql-toolbar.ql-snow { border-color: rgba(55, 65, 81, 0.5); background-color: rgba(31, 41, 55, 0.8); border-top-left-radius: 0.75rem; border-top-right-radius: 0.75rem; }
    .ql-container.ql-snow { border-color: rgba(55, 65, 81, 0.5); background-color: rgba(17, 24, 39, 0.4); border-bottom-left-radius: 0.75rem; border-bottom-right-radius: 0.75rem; color: white; font-family: inherit; }
    .ql-snow .ql-stroke { stroke: #9ca3af; }
    .ql-snow .ql-fill, .ql-snow .ql-stroke.ql-fill { fill: #9ca3af; }
    .ql-snow .ql-picker { color: #9ca3af; }
</style>

<div class="max-w-4xl mx-auto">
    <div class="bg-gray-800/50 backdrop-blur-md border border-gray-700 rounded-xl shadow-xl overflow-hidden">
        <div class="p-6 border-b border-gray-700/50 bg-gray-800/80">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Magindah
            </h2>
            <p class="text-sm text-gray-400 mt-1">Masukkan data program baru ke dalam sistem Magindah.</p>
        </div>
        
        <form id="inputForm" class="p-6 space-y-6">
            @csrf
            
            <!-- Toast Notification Container (Fixed) -->
            <div id="toastContainer" class="fixed top-20 right-5 z-[70] flex flex-col gap-3 pointer-events-none">
                <!-- Success Toast -->
                <div id="toastSuccess" style="display: none;" class="fixed top-20 right-5 z-[70] transform transition-all duration-300 ease-in-out bg-gray-800 border border-emerald-500/30 shadow-lg shadow-emerald-900/20 rounded-xl p-4 flex items-center gap-3 w-80 pointer-events-auto">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-white">Berhasil!</h4>
                        <p id="toastSuccessText" class="text-xs text-gray-400">Proposal berhasil dibuat.</p>
                    </div>
                </div>

                <!-- Error Toast -->
                <div id="toastError" style="display: none;" class="fixed top-20 right-5 z-[70] transform transition-all duration-300 ease-in-out bg-gray-800 border border-red-500/30 shadow-lg shadow-red-900/20 rounded-xl p-4 flex items-center gap-3 w-80 pointer-events-auto">
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kolom Kiri -->
                <div class="space-y-4">
                    <div>
                        <label for="nop" class="block text-sm font-medium text-gray-300 mb-1">NOP <span class="text-red-400">*</span></label>
                        <input type="text" id="nop" name="nop" required class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all cursor-not-allowed opacity-80" placeholder="Nomor Program" value="{{ $userBranch ?? '' }}" readonly>
                        <p class="text-xs text-red-400 mt-1 hidden" id="error-nop"></p>
                    </div>

                    <div>
                        <label for="program" class="block text-sm font-medium text-gray-300 mb-1">Nama Program <span class="text-red-400">*</span></label>
                        <input type="text" id="program" name="program" required class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Nama Program">
                        <p class="text-xs text-red-400 mt-1 hidden" id="error-program"></p>
                    </div>

                    <div>
                        <label for="kategori" class="block text-sm font-medium text-gray-300 mb-1">Kategori <span class="text-red-400">*</span></label>
                        <select id="kategori" name="kategori" required class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all appearance-none">
                            <option value="">Pilih Kategori</option>
                            <option value="Productivity">Productivity</option>
                            <option value="Civil">Civil</option>
                            <option value="CME">CME</option>
                            <option value="Optime">Optime</option>
                            <option value="Power">Power</option>
                            <option value="CME Electrical">CME Electrical</option>
                            <option value="PSB">PSB</option>
                            <option value="Transmisi">Transmisi</option>
                        </select>
                        <p class="text-xs text-red-400 mt-1 hidden" id="error-kategori"></p>
                    </div>

                    <div class="pt-2 border-t border-gray-700/50 mt-4 mb-2">
                        <div class="flex items-center justify-between gap-3 mb-2">
                            <h3 class="text-md font-semibold text-blue-400">Justifikasi Program</h3>
                            <button type="button" id="openJustifikasiModal" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-blue-500/15 text-blue-300 border border-blue-500/30 hover:bg-blue-500/20 transition-colors text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 20h9"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.5a2.121 2.121 0 013 3L8 18l-4 1 1-4 11.5-11.5z"/>
                                </svg>
                                Isi Justifikasi
                            </button>
                        </div>

                        <div class="text-xs text-gray-400">Kelola subbab justifikasi lewat popup agar lebih rapi, lalu data tersimpan sebagai draft lokal.</div>

                        <div id="justifikasiSummary" class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="rounded-lg border border-gray-700/60 bg-gray-900/30 p-3 flex items-center justify-between">
                                <div class="text-sm text-gray-200">Objective</div>
                                <div id="justifikasiSummaryObjective" class="text-xs text-gray-400">Belum diisi</div>
                            </div>
                            <div class="rounded-lg border border-gray-700/60 bg-gray-900/30 p-3 flex items-center justify-between">
                                <div class="text-sm text-gray-200">Alasan Kebutuhan</div>
                                <div id="justifikasiSummaryAlasan" class="text-xs text-gray-400">Belum diisi</div>
                            </div>
                            <div class="rounded-lg border border-gray-700/60 bg-gray-900/30 p-3 flex items-center justify-between">
                                <div class="text-sm text-gray-200">Distribusi Pekerjaan</div>
                                <div id="justifikasiSummaryDistribusi" class="text-xs text-gray-400">Belum diisi</div>
                            </div>
                            <div class="rounded-lg border border-gray-700/60 bg-gray-900/30 p-3 flex items-center justify-between">
                                <div class="text-sm text-gray-200">Lingkup Pekerjaan</div>
                                <div id="justifikasiSummaryLingkup" class="text-xs text-gray-400">Belum diisi</div>
                            </div>
                            <div class="rounded-lg border border-gray-700/60 bg-gray-900/30 p-3 flex items-center justify-between">
                                <div class="text-sm text-gray-200">Spesifikasi Teknis</div>
                                <div id="justifikasiSummaryTeknis" class="text-xs text-gray-400">Belum diisi</div>
                            </div>
                            <div class="rounded-lg border border-gray-700/60 bg-gray-900/30 p-3 flex items-center justify-between">
                                <div class="text-sm text-gray-200">Detail Kebutuhan (RAB)</div>
                                <div id="justifikasiSummaryRab" class="text-xs text-gray-400">Belum diisi</div>
                            </div>
                            <div class="rounded-lg border border-gray-700/60 bg-gray-900/30 p-3 flex items-center justify-between sm:col-span-2">
                                <div class="text-sm text-gray-200">Executive Summary Program</div>
                                <div id="justifikasiSummarySummary" class="text-xs text-gray-400">Belum diisi</div>
                            </div>
                        </div>

                        <div id="justifikasiFields" class="hidden">
                            <div>
                                <label for="justifikasi_objective" class="block text-sm font-medium text-gray-300 mb-1">Objective</label>
                                <textarea id="justifikasi_objective" name="justifikasi_objective" rows="2" class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Objective program..."></textarea>
                                <p class="text-xs text-red-400 mt-1 hidden" id="error-justifikasi_objective"></p>
                            </div>
                            <div>
                                <label for="justifikasi_alasan" class="block text-sm font-medium text-gray-300 mb-1">Alasan Kebutuhan</label>
                                <textarea id="justifikasi_alasan" name="justifikasi_alasan" rows="2" class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Alasan kebutuhan program..."></textarea>
                                <p class="text-xs text-red-400 mt-1 hidden" id="error-justifikasi_alasan"></p>
                            </div>
                            <div>
                                <label for="justifikasi_distribusi" class="block text-sm font-medium text-gray-300 mb-1">Distribusi Pekerjaan</label>
                                <textarea id="justifikasi_distribusi" name="justifikasi_distribusi" rows="2" class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Distribusi pekerjaan..."></textarea>
                                <p class="text-xs text-red-400 mt-1 hidden" id="error-justifikasi_distribusi"></p>
                            </div>
                            <div>
                                <label for="justifikasi_lingkup" class="block text-sm font-medium text-gray-300 mb-1">Lingkup Pekerjaan</label>
                                <textarea id="justifikasi_lingkup" name="justifikasi_lingkup" rows="2" class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Lingkup pekerjaan..."></textarea>
                                <p class="text-xs text-red-400 mt-1 hidden" id="error-justifikasi_lingkup"></p>
                            </div>
                            <div>
                                <label for="justifikasi_teknis" class="block text-sm font-medium text-gray-300 mb-1">Spesifikasi Teknis</label>
                                <textarea id="justifikasi_teknis" name="justifikasi_teknis" rows="2" class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Spesifikasi teknis..."></textarea>
                                <p class="text-xs text-red-400 mt-1 hidden" id="error-justifikasi_teknis"></p>
                            </div>
                            <div>
                                <label for="justifikasi_rab" class="block text-sm font-medium text-gray-300 mb-1">Detail Kebutuhan (RAB)</label>
                                <textarea id="justifikasi_rab" name="justifikasi_rab" rows="2" class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Detail kebutuhan (RAB)..."></textarea>
                                <p class="text-xs text-red-400 mt-1 hidden" id="error-justifikasi_rab"></p>
                            </div>
                            <div>
                                <label for="justifikasi_summary" class="block text-sm font-medium text-gray-300 mb-1">Executive Summary Program</label>
                                <textarea id="justifikasi_summary" name="justifikasi_summary" rows="2" class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Executive summary program..."></textarea>
                                <p class="text-xs text-red-400 mt-1 hidden" id="error-justifikasi_summary"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="space-y-4">
                    <div class="space-y-4">
                        <div>
                            <label for="budget" class="block text-sm font-medium text-gray-300 mb-1">Budget</label>
                            <input type="number" id="budget" name="budget" class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="0">
                        </div>
                    </div>

                    <div>
                        <label for="revenue" class="block text-sm font-medium text-gray-300 mb-1">Revenue</label>
                        <input type="number" id="revenue" name="revenue" class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="0">
                    </div>
                    <div>
                        <label for="cost" class="block text-sm font-medium text-gray-300 mb-1">Cost</label>
                        <input type="number" id="cost" name="cost" class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Profit</label>
                        <div class="w-full bg-gray-900/50 border border-gray-700/50 rounded-lg px-4 py-2 text-white flex items-center justify-between">
                            <span id="profitDisplay" class="font-mono text-emerald-400">0.00%</span>
                            <input type="hidden" id="profit" name="profit" value="0">
                        </div>
                    </div>

                    <div>
                        <label for="incremental_1" class="block text-sm font-medium text-gray-300 mb-1">Incremental 1</label>
                        <input type="number" id="incremental_1" name="incremental_1" class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="0">
                    </div>
                    <div>
                        <label for="incremental_2" class="block text-sm font-medium text-gray-300 mb-1">Incremental 2</label>
                        <input type="number" id="incremental_2" name="incremental_2" class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="0">
                    </div>
                    <div>
                        <label for="incremental_3" class="block text-sm font-medium text-gray-300 mb-1">Incremental 3</label>
                        <input type="number" id="incremental_3" name="incremental_3" class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="0">
                    </div>

                    <!-- Pilot removed as requested -->

                    <div>
                        <label for="driven_program" class="block text-sm font-medium text-gray-300 mb-1">Driven Program</label>
                        <input type="text" id="driven_program" name="driven_program" class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Program Terkait">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Auto-filled fields: Assign By and Approved By are handled by backend -->
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-700/50">
                <button type="button" id="resetBtn" class="px-6 py-2.5 rounded-lg border border-gray-600 text-gray-300 hover:bg-gray-700 hover:text-white transition-all font-medium text-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:ring-offset-gray-900">
                    Reset Form
                </button>
                <button type="submit" id="submitBtn" class="px-6 py-2.5 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-600 text-white hover:from-blue-600 hover:to-indigo-700 transition-all font-medium text-sm shadow-lg shadow-blue-500/20 flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-900">
                    <span id="btnText">Simpan Data</span>
                    <svg id="btnSpinner" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>

<div id="justifikasiModal" class="fixed inset-0 z-50 hidden" aria-hidden="true">
    <div id="justifikasiModalBackdrop" class="absolute inset-0 bg-black/60 backdrop-blur-sm opacity-0 transition-opacity duration-200"></div>
    <div class="relative min-h-full flex items-center justify-center p-4">
        <div id="justifikasiModalPanel" class="w-full max-w-5xl glass rounded-2xl border border-slate-800/60 shadow-2xl shadow-black/40 overflow-hidden opacity-0 scale-95 translate-y-2 transition duration-200 ease-out">
            <div class="flex items-start justify-between gap-4 p-4 sm:p-6 border-b border-slate-800/60">
                <div class="min-w-0">
                    <div class="text-sm font-semibold text-white">Justifikasi Magindah</div>
                    <div class="text-xs text-slate-400">Pilih subbab, isi konten, dan simpan sebagai draft lokal.</div>
                </div>
                <button type="button" id="closeJustifikasiModal" class="inline-flex items-center justify-center h-9 w-9 rounded-lg border border-slate-700/60 text-slate-200 hover:bg-slate-800/60 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-950" aria-label="Tutup popup justifikasi">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="p-4 sm:p-6 grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-4">
                    <div class="text-[10px] font-semibold tracking-wider uppercase text-slate-500 mb-2">Subbab</div>
                    <div class="space-y-2" id="justifikasiSubbabList">
                        <button type="button" data-field="justifikasi_objective" class="justifikasi-subbab w-full flex items-center justify-between gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-200 border border-slate-800/60 bg-slate-900/30 hover:bg-slate-800/40 transition-colors">
                            <span class="flex items-center gap-3">
                                <span class="h-9 w-9 rounded-xl bg-slate-900/50 border border-slate-800/60 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 20h9"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.5a2.121 2.121 0 013 3L8 18l-4 1 1-4 11.5-11.5z"/>
                                    </svg>
                                </span>
                                Objective
                            </span>
                            <span id="indicator-justifikasi_objective" class="text-xs text-slate-500">Kosong</span>
                        </button>

                        <button type="button" data-field="justifikasi_alasan" class="justifikasi-subbab w-full flex items-center justify-between gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-200 border border-slate-800/60 bg-slate-900/30 hover:bg-slate-800/40 transition-colors">
                            <span class="flex items-center gap-3">
                                <span class="h-9 w-9 rounded-xl bg-slate-900/50 border border-slate-800/60 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 3h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                                    </svg>
                                </span>
                                Alasan Kebutuhan
                            </span>
                            <span id="indicator-justifikasi_alasan" class="text-xs text-slate-500">Kosong</span>
                        </button>

                        <button type="button" data-field="justifikasi_distribusi" class="justifikasi-subbab w-full flex items-center justify-between gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-200 border border-slate-800/60 bg-slate-900/30 hover:bg-slate-800/40 transition-colors">
                            <span class="flex items-center gap-3">
                                <span class="h-9 w-9 rounded-xl bg-slate-900/50 border border-slate-800/60 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h8"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h8"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 17h8"/>
                                    </svg>
                                </span>
                                Distribusi Pekerjaan
                            </span>
                            <span id="indicator-justifikasi_distribusi" class="text-xs text-slate-500">Kosong</span>
                        </button>

                        <button type="button" data-field="justifikasi_lingkup" class="justifikasi-subbab w-full flex items-center justify-between gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-200 border border-slate-800/60 bg-slate-900/30 hover:bg-slate-800/40 transition-colors">
                            <span class="flex items-center gap-3">
                                <span class="h-9 w-9 rounded-xl bg-slate-900/50 border border-slate-800/60 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 12h10"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 18h16"/>
                                    </svg>
                                </span>
                                Lingkup Pekerjaan
                            </span>
                            <span id="indicator-justifikasi_lingkup" class="text-xs text-slate-500">Kosong</span>
                        </button>

                        <button type="button" data-field="justifikasi_teknis" class="justifikasi-subbab w-full flex items-center justify-between gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-200 border border-slate-800/60 bg-slate-900/30 hover:bg-slate-800/40 transition-colors">
                            <span class="flex items-center gap-3">
                                <span class="h-9 w-9 rounded-xl bg-slate-900/50 border border-slate-800/60 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 1v6"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 17v6"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.2 4.2l4.2 4.2"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.6 15.6l4.2 4.2"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M1 12h6"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 12h6"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.2 19.8l4.2-4.2"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.6 8.4l4.2-4.2"/>
                                    </svg>
                                </span>
                                Spesifikasi Teknis
                            </span>
                            <span id="indicator-justifikasi_teknis" class="text-xs text-slate-500">Kosong</span>
                        </button>

                        <button type="button" data-field="justifikasi_rab" class="justifikasi-subbab w-full flex items-center justify-between gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-200 border border-slate-800/60 bg-slate-900/30 hover:bg-slate-800/40 transition-colors">
                            <span class="flex items-center gap-3">
                                <span class="h-9 w-9 rounded-xl bg-slate-900/50 border border-slate-800/60 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17h6"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 21h12a2 2 0 002-2V5a2 2 0 00-2-2H6a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </span>
                                Detail Kebutuhan (RAB)
                            </span>
                            <span id="indicator-justifikasi_rab" class="text-xs text-slate-500">Kosong</span>
                        </button>

                        <button type="button" data-field="justifikasi_summary" class="justifikasi-subbab w-full flex items-center justify-between gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-200 border border-slate-800/60 bg-slate-900/30 hover:bg-slate-800/40 transition-colors">
                            <span class="flex items-center gap-3">
                                <span class="h-9 w-9 rounded-xl bg-slate-900/50 border border-slate-800/60 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 12h16"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 18h10"/>
                                    </svg>
                                </span>
                                Executive Summary
                            </span>
                            <span id="indicator-justifikasi_summary" class="text-xs text-slate-500">Kosong</span>
                        </button>
                    </div>
                </div>

                <div class="md:col-span-8">
                    <!-- Standard editor panel (for non-RAB fields) -->
                    <div id="justifikasiEditorPanel" class="rounded-2xl border border-slate-800/60 bg-slate-900/30 overflow-hidden">
                        <div class="p-4 border-b border-slate-800/60">
                            <div id="justifikasiEditorTitle" class="text-sm font-semibold text-white">Objective</div>
                            <div id="justifikasiEditorHint" class="text-xs text-slate-400 mt-1">Tulis ringkas, jelas, dan relevan dengan program.</div>
                        </div>
                        <div class="p-4" style="min-height: 250px;">
                            <div id="justifikasiQuillWrapper">
                                <div id="justifikasiEditorQuill" style="height: 180px;"></div>
                            </div>
                            <textarea id="justifikasiEditorTextarea" rows="10" class="hidden w-full bg-gray-900/40 border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Mulai menulis..."></textarea>
                            <div class="mt-2 flex items-center justify-between gap-3">
                                <p id="justifikasiEditorError" class="text-xs text-red-400 hidden"></p>
                                <p id="justifikasiEditorCounter" class="text-xs text-slate-500">0 karakter</p>
                            </div>
                        </div>
                        <div class="p-4 border-t border-slate-800/60 flex items-center justify-end gap-3">
                            <button type="button" id="closeJustifikasiModalBottom" class="px-4 py-2 rounded-lg border border-slate-700/60 text-slate-200 hover:bg-slate-800/50 transition-colors text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-950">Selesai</button>
                        </div>
                    </div>

                    <!-- RAB Excel Upload Panel (shown only when justifikasi_rab is selected) -->
                    <div id="rabUploadPanel" class="hidden rounded-2xl border border-slate-800/60 bg-slate-900/30 overflow-hidden">
                        <div class="p-4 border-b border-slate-800/60">
                            <div class="text-sm font-semibold text-white">Detail Kebutuhan (RAB)</div>
                            <div class="text-xs text-slate-400 mt-1">Upload file Excel (.xlsx/.xls) BOQ. Kolom: PR Item, No, Item Deskripsi, Qty, UoM, Harga Satuan, Harga Total, Keterangan.</div>
                        </div>
                        <div class="p-4 space-y-4">
                            <div id="rabModalDropZone" style="border:2px dashed rgba(99,102,241,0.4);border-radius:12px;padding:24px;text-align:center;cursor:pointer;transition:all 0.2s;background:rgba(99,102,241,0.04);"
                                onclick="document.getElementById('rabModalFileInput').click()">
                                <input type="file" id="rabModalFileInput" accept=".xlsx,.xls" class="hidden">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto mb-2 text-indigo-400 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                </svg>
                                <p class="text-sm text-slate-300 font-medium">Klik atau drag & drop file Excel</p>
                                <p class="text-xs text-slate-500 mt-1">.xlsx / .xls</p>
                            </div>
                            <div id="rabModalFileIndicator" class="hidden flex items-center gap-3 p-3 rounded-xl bg-indigo-500/10 border border-indigo-500/20">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="text-sm text-indigo-300" id="rabModalFileName">—</span>
                                <span class="ml-auto text-xs text-emerald-400 font-medium" id="rabModalRowCount"></span>
                            </div>
                            <div id="rabModalPreview" class="hidden overflow-x-auto rounded-xl border border-slate-700/40" style="max-height:240px;">
                                <table id="rabModalTable" style="width:100%;border-collapse:collapse;font-size:0.75rem;">
                                    <thead id="rabModalThead" style="position:sticky;top:0;background:#1e293b;z-index:1;"></thead>
                                    <tbody id="rabModalTbody"></tbody>
                                    <tfoot id="rabModalTfoot"></tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="p-4 border-t border-slate-800/60 flex items-center justify-end">
                            <button type="button" id="closeJustifikasiModalBottomRab" class="px-4 py-2 rounded-lg border border-slate-700/60 text-slate-200 hover:bg-slate-800/50 transition-colors text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-950">Selesai</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('inputForm');
        const submitBtn = document.getElementById('submitBtn');
        const resetBtn = document.getElementById('resetBtn');
        const btnText = document.getElementById('btnText');
        const btnSpinner = document.getElementById('btnSpinner');
        const toastSuccess = document.getElementById('toastSuccess');
        const toastError = document.getElementById('toastError');
        const toastErrorText = document.getElementById('toastErrorText');
        const toastSuccessText = document.getElementById('toastSuccessText');

        // Profit Calculation Logic
        const revenueInput = document.getElementById('revenue');
        const costInput = document.getElementById('cost');
        const profitInput = document.getElementById('profit');
        const profitDisplay = document.getElementById('profitDisplay');

        function calculateProfit() {
            if (!revenueInput || !costInput || !profitInput || !profitDisplay) return;
            const rev = parseFloat(revenueInput.value) || 0;
            const cost = parseFloat(costInput.value) || 0;
            let profitVal = 0;
            let displayVal = "0.00%";
            if (rev > 0) {
                profitVal = ((rev - cost) / rev) * 100;
                displayVal = profitVal.toFixed(2) + "%";
            }
            profitInput.value = profitVal.toFixed(2);
            profitDisplay.textContent = displayVal;
            if (profitVal < 0) {
                profitDisplay.className = "font-mono text-red-400";
            } else {
                profitDisplay.className = "font-mono text-emerald-400";
            }
        }
        
        if (revenueInput) revenueInput.addEventListener('input', calculateProfit);
        if (costInput) costInput.addEventListener('input', calculateProfit);

        const openJustifikasiModalBtn = document.getElementById('openJustifikasiModal');
        const justifikasiModal = document.getElementById('justifikasiModal');
        const justifikasiModalBackdrop = document.getElementById('justifikasiModalBackdrop');
        const justifikasiModalPanel = document.getElementById('justifikasiModalPanel');
        const closeJustifikasiModalBtn = document.getElementById('closeJustifikasiModal');
        const closeJustifikasiModalBottomBtn = document.getElementById('closeJustifikasiModalBottom');
        const justifikasiSubbabList = document.getElementById('justifikasiSubbabList');
        const justifikasiEditorTitle = document.getElementById('justifikasiEditorTitle');
        const justifikasiEditorHint = document.getElementById('justifikasiEditorHint');
        const justifikasiEditorTextarea = document.getElementById('justifikasiEditorTextarea');
        const justifikasiQuillWrapper = document.getElementById('justifikasiQuillWrapper');
        const justifikasiEditorError = document.getElementById('justifikasiEditorError');
        const justifikasiEditorCounter = document.getElementById('justifikasiEditorCounter');

        // Init Quill
        let quill = null;
        if (document.getElementById('justifikasiEditorQuill')) {
            quill = new Quill('#justifikasiEditorQuill', {
                theme: 'snow',
                placeholder: 'Mulai menulis...',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['image'],
                        ['clean']
                    ]
                }
            });
            
            quill.on('text-change', function() {
                setJustifikasiValue(activeJustifikasiField, quill.root.innerHTML);
                if (justifikasiEditorCounter) {
                    justifikasiEditorCounter.textContent = `${quill.getText().trim().length} karakter`;
                }
                validateJustifikasiField(activeJustifikasiField, { showEditorError: true });
                updateJustifikasiSummaries();
                scheduleDraftSave();
            });
        }

        const justifikasiFields = [
            'justifikasi_objective',
            'justifikasi_alasan',
            'justifikasi_distribusi',
            'justifikasi_lingkup',
            'justifikasi_teknis',
            'justifikasi_rab',
            'justifikasi_summary'
        ];

        const justifikasiMeta = {
            justifikasi_objective: { title: 'Objective', hint: 'Tulis ringkas, jelas, dan relevan dengan program.', placeholder: 'Objective program...' },
            justifikasi_alasan: { title: 'Alasan Kebutuhan', hint: 'Jelaskan alasan kebutuhan dan urgensi program.', placeholder: 'Alasan kebutuhan program...' },
            justifikasi_distribusi: { title: 'Distribusi Pekerjaan', hint: 'Jabarkan pembagian pekerjaan, peran, dan pihak terlibat.', placeholder: 'Distribusi pekerjaan...' },
            justifikasi_lingkup: { title: 'Lingkup Pekerjaan', hint: 'Rinci lingkup pekerjaan serta batasan scope.', placeholder: 'Lingkup pekerjaan...' },
            justifikasi_teknis: { title: 'Spesifikasi Teknis', hint: 'Tuliskan spesifikasi teknis yang dibutuhkan.', placeholder: 'Spesifikasi teknis...' },
            justifikasi_rab: { title: 'Detail Kebutuhan (RAB)', hint: 'Cantumkan detail kebutuhan dan estimasi komponen biaya.', placeholder: 'Detail kebutuhan (RAB)...' },
            justifikasi_summary: { title: 'Executive Summary Program', hint: 'Ringkas program: tujuan, manfaat, dan highlight utama.', placeholder: 'Executive summary program...' }
        };

        const draftKey = 'excelDataStudio.magindahDraft.v1';
        let draftTimer = null;
        let activeJustifikasiField = 'justifikasi_objective';

        function getJustifikasiValue(fieldId) {
            const el = document.getElementById(fieldId);
            return el ? el.value : '';
        }

        function setJustifikasiValue(fieldId, value) {
            const el = document.getElementById(fieldId);
            if (el) el.value = value;
        }

        function setSummaryState(summaryEl, isFilled, isValid = true) {
            if (!summaryEl) return;
            if (!isFilled) {
                summaryEl.textContent = 'Belum diisi';
                summaryEl.className = 'text-xs text-gray-400';
            } else if (!isValid) {
                summaryEl.textContent = 'Tidak Memenuhi Kriteria';
                summaryEl.className = 'text-xs text-red-400 font-semibold';
            } else {
                summaryEl.textContent = 'Memenuhi Kriteria';
                summaryEl.className = 'text-xs text-emerald-400 font-semibold';
            }
        }

        function setIndicatorState(fieldId, isFilled, isValid = true) {
            const indicator = document.getElementById(`indicator-${fieldId}`);
            if (!indicator) return;
            if (!isFilled) {
                indicator.textContent = 'Kosong';
                indicator.className = 'text-xs text-slate-500';
            } else if (!isValid) {
                indicator.textContent = 'Tidak Memenuhi';
                indicator.className = 'text-xs text-red-400 font-semibold';
            } else {
                indicator.textContent = 'Memenuhi';
                indicator.className = 'text-xs text-emerald-400 font-semibold';
            }
        }

        function checkFieldValidity(fieldId) {
            const raw = getJustifikasiValue(fieldId);
            const plainText = raw.replace(/<[^>]*>?/gm, '').replace(/&nbsp;/g, ' ').trim();
            if (plainText.length === 0) return { isFilled: false, isValid: false };
            return { isFilled: true, isValid: plainText.length >= 100 };
        }

        function updateJustifikasiSummaries() {
            const summaries = {
                'justifikasi_objective': document.getElementById('justifikasiSummaryObjective'),
                'justifikasi_alasan': document.getElementById('justifikasiSummaryAlasan'),
                'justifikasi_distribusi': document.getElementById('justifikasiSummaryDistribusi'),
                'justifikasi_lingkup': document.getElementById('justifikasiSummaryLingkup'),
                'justifikasi_teknis': document.getElementById('justifikasiSummaryTeknis'),
                'justifikasi_rab': document.getElementById('justifikasiSummaryRab'),
                'justifikasi_summary': document.getElementById('justifikasiSummarySummary')
            };

            justifikasiFields.forEach(fieldId => {
                const { isFilled, isValid } = checkFieldValidity(fieldId);
                setSummaryState(summaries[fieldId], isFilled, isValid);
                setIndicatorState(fieldId, isFilled, isValid);
            });
        }

        function validateJustifikasiField(fieldId, opts = { showEditorError: false }) {
            const raw = getJustifikasiValue(fieldId);
            
            // If it's a rich text field, test plain text length
            let plainText = raw.replace(/<[^>]*>?/gm, '').replace(/&nbsp;/g, ' ').trim();
            // Fallback for teknis which is raw text
            if (fieldId === 'justifikasi_teknis') {
                plainText = raw.trim();
            }

            const errorText = plainText.length > 0 && plainText.length < 100 ? 'Minimal 100 karakter jika diisi.' : '';

            const errorEl = document.getElementById(`error-${fieldId}`);
            const inputEl = document.getElementById(fieldId);
            if (errorEl) {
                if (errorText) {
                    errorEl.textContent = errorText;
                    errorEl.classList.remove('hidden');
                } else {
                    errorEl.textContent = '';
                    errorEl.classList.add('hidden');
                }
            }

            if (inputEl) {
                if (errorText) {
                    inputEl.classList.add('border-red-500', 'ring-1', 'ring-red-500');
                    inputEl.classList.remove('border-gray-700');
                } else {
                    inputEl.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
                    inputEl.classList.add('border-gray-700');
                }
            }

            if (opts.showEditorError && justifikasiEditorError && justifikasiEditorTextarea) {
                if (errorText) {
                    justifikasiEditorError.textContent = errorText;
                    justifikasiEditorError.classList.remove('hidden');
                    justifikasiEditorTextarea.classList.add('border-red-500', 'ring-1', 'ring-red-500');
                    justifikasiEditorTextarea.classList.remove('border-gray-700');
                } else {
                    justifikasiEditorError.textContent = '';
                    justifikasiEditorError.classList.add('hidden');
                    justifikasiEditorTextarea.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
                    justifikasiEditorTextarea.classList.add('border-gray-700');
                }
            }

            return !errorText;
        }

        function validateJustifikasiAll() {
            let ok = true;
            justifikasiFields.forEach(fieldId => {
                const fieldOk = validateJustifikasiField(fieldId, { showEditorError: fieldId === activeJustifikasiField });
                if (!fieldOk) ok = false;
            });
            return ok;
        }

        function setActiveSubbabButton(fieldId) {
            if (!justifikasiSubbabList) return;
            justifikasiSubbabList.querySelectorAll('.justifikasi-subbab').forEach(btn => {
                const isActive = btn.getAttribute('data-field') === fieldId;
                btn.classList.remove('bg-blue-500/15', 'ring-1', 'ring-blue-500/30', 'text-blue-200');
                btn.classList.add('bg-slate-900/30', 'text-slate-200', 'border', 'border-slate-800/60');
                if (isActive) {
                    btn.classList.remove('bg-slate-900/30', 'text-slate-200');
                    btn.classList.add('bg-blue-500/15', 'ring-1', 'ring-blue-500/30', 'text-blue-200');
                }
            });
        }

        function setEditorForField(fieldId) {
            activeJustifikasiField = fieldId;
            const meta = justifikasiMeta[fieldId] || { title: fieldId, hint: '', placeholder: 'Mulai menulis...' };

            const editorPanel = document.getElementById('justifikasiEditorPanel');
            const rabPanel = document.getElementById('rabUploadPanel');

            // Toggle RAB panel vs. standard editor
            if (fieldId === 'justifikasi_rab') {
                if (editorPanel) editorPanel.classList.add('hidden');
                if (rabPanel) rabPanel.classList.remove('hidden');
                setActiveSubbabButton(fieldId);
                return;
            }

            if (editorPanel) editorPanel.classList.remove('hidden');
            if (rabPanel) rabPanel.classList.add('hidden');

            if (justifikasiEditorTitle) justifikasiEditorTitle.textContent = meta.title;
            if (justifikasiEditorHint) justifikasiEditorHint.textContent = meta.hint;
            
            const rawVal = getJustifikasiValue(fieldId);
            
            if (justifikasiEditorTextarea) justifikasiEditorTextarea.classList.add('hidden');
            if (justifikasiQuillWrapper) {
                justifikasiQuillWrapper.classList.remove('hidden');
                if (quill) {
                    quill.root.innerHTML = rawVal; // Load HTML safely
                    if (justifikasiEditorCounter) justifikasiEditorCounter.textContent = `${quill.getText().trim().length} karakter`;
                }
            }

            setActiveSubbabButton(fieldId);
            validateJustifikasiField(fieldId, { showEditorError: true });
        }

        function openJustifikasiModal(fieldId = activeJustifikasiField) {
            if (!justifikasiModal || !justifikasiModalBackdrop || !justifikasiModalPanel) return;
            justifikasiModal.classList.remove('hidden');
            requestAnimationFrame(() => {
                justifikasiModalBackdrop.classList.remove('opacity-0');
                justifikasiModalBackdrop.classList.add('opacity-100');
                justifikasiModalPanel.classList.remove('opacity-0', 'scale-95', 'translate-y-2');
                justifikasiModalPanel.classList.add('opacity-100', 'scale-100', 'translate-y-0');
            });
            document.documentElement.classList.add('overflow-hidden');
            updateJustifikasiSummaries();
            setEditorForField(fieldId);
            if (fieldId === 'justifikasi_teknis') {
                if (justifikasiEditorTextarea) justifikasiEditorTextarea.focus();
            } else {
                if (quill) quill.focus();
            }
        }

        function closeJustifikasiModal() {
            if (!justifikasiModal || !justifikasiModalBackdrop || !justifikasiModalPanel) return;
            justifikasiModalBackdrop.classList.add('opacity-0');
            justifikasiModalBackdrop.classList.remove('opacity-100');
            justifikasiModalPanel.classList.add('opacity-0', 'scale-95', 'translate-y-2');
            justifikasiModalPanel.classList.remove('opacity-100', 'scale-100', 'translate-y-0');
            window.setTimeout(() => {
                justifikasiModal.classList.add('hidden');
            }, 200);
            document.documentElement.classList.remove('overflow-hidden');
        }

        function serializeDraft() {
            const data = {};
            form.querySelectorAll('input, select, textarea').forEach(el => {
                if (!el.name || el.name === '_token') return;
                if (el.type === 'checkbox') data[el.name] = el.checked ? '1' : '0';
                else data[el.name] = el.value;
            });
            return data;
        }

        function applyDraft(data) {
            if (!data || typeof data !== 'object') return;
            const esc = (window.CSS && typeof CSS.escape === 'function') ? CSS.escape : (s) => String(s).replace(/"/g, '\\"');
            Object.entries(data).forEach(([name, value]) => {
                const el = form.querySelector(`[name="${esc(name)}"]`);
                if (!el) return;
                if (el.type === 'checkbox') el.checked = value === '1' || value === true;
                else el.value = value;
            });
        }

        function saveDraftNow() {
            try {
                localStorage.setItem(draftKey, JSON.stringify(serializeDraft()));
            } catch (e) {
                return;
            }
        }

        function scheduleDraftSave() {
            if (draftTimer) window.clearTimeout(draftTimer);
            draftTimer = window.setTimeout(saveDraftNow, 250);
        }

        function clearDraft() {
            try {
                localStorage.removeItem(draftKey);
            } catch (e) {
                return;
            }
        }

        function loadDraft() {
            let raw = null;
            try {
                raw = localStorage.getItem(draftKey);
            } catch (e) {
                raw = null;
            }
            if (!raw) return;
            try {
                const data = JSON.parse(raw);
                applyDraft(data);
            } catch (e) {
                return;
            }
        }

        loadDraft();
        updateJustifikasiSummaries();
        hideToasts();

        form.addEventListener('input', function(e) {
            if (!e.target || !e.target.name || e.target.name === '_token') return;
            scheduleDraftSave();
            if (justifikasiFields.includes(e.target.id)) updateJustifikasiSummaries();
        });

        if (openJustifikasiModalBtn) {
            openJustifikasiModalBtn.addEventListener('click', function() {
                openJustifikasiModal(activeJustifikasiField);
            });
        }
        if (closeJustifikasiModalBtn) closeJustifikasiModalBtn.addEventListener('click', closeJustifikasiModal);
        if (closeJustifikasiModalBottomBtn) closeJustifikasiModalBottomBtn.addEventListener('click', closeJustifikasiModal);
        if (justifikasiModalBackdrop) justifikasiModalBackdrop.addEventListener('click', closeJustifikasiModal);
        if (justifikasiSubbabList) {
            justifikasiSubbabList.addEventListener('click', function(e) {
                const btn = e.target.closest('.justifikasi-subbab');
                if (!btn) return;
                const fieldId = btn.getAttribute('data-field');
                if (!fieldId) return;
                setEditorForField(fieldId);
            });
        }
        if (justifikasiEditorTextarea) {
            justifikasiEditorTextarea.addEventListener('input', function() {
                if (activeJustifikasiField === 'justifikasi_teknis') {
                    setJustifikasiValue(activeJustifikasiField, justifikasiEditorTextarea.value);
                    if (justifikasiEditorCounter) justifikasiEditorCounter.textContent = `${justifikasiEditorTextarea.value.length} karakter`;
                    validateJustifikasiField(activeJustifikasiField, { showEditorError: true });
                    updateJustifikasiSummaries();
                    scheduleDraftSave();
                }
            });
        }
        document.addEventListener('keydown', function(e) {
            if (e.key !== 'Escape') return;
            if (justifikasiModal && !justifikasiModal.classList.contains('hidden')) closeJustifikasiModal();
        });

        // Reset functionality
        resetBtn.addEventListener('click', function() {
            if(confirm('Apakah Anda yakin ingin mengosongkan form?')) {
                form.reset();
                hideToasts();
                clearErrors();
                clearDraft();
                updateJustifikasiSummaries();
            }
        });

        // Form submission
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Basic client-side validation check
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            if (!validateJustifikasiAll()) {
                showToast('error', 'Justifikasi: minimal 10 karakter jika diisi.');
                return;
            }

            // Prepare UI
            setLoading(true);
            hideToasts();
            clearErrors();

            const formData = new FormData(form);
            
            try {
                const response = await fetch("{{ route('magindah.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();

                if (response.ok) {
                    // Success
                    showToast('success', result.message || 'Proposal berhasil dibuat.');
                    form.reset();
                    clearDraft();
                    updateJustifikasiSummaries();
                    closeJustifikasiModal();
                    // Auto redirect to dashboard after 3.5s
                    setTimeout(() => {
                        window.location.href = "/";
                    }, 3500);
                } else {
                    // Error
                    if (response.status === 422) {
                        showValidationErrors(result.errors);
                        showToast('error', 'Mohon periksa kembali inputan Anda.');
                    } else {
                        showToast('error', result.message || 'Terjadi kesalahan pada server.');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('error', 'Gagal menghubungi server. Periksa koneksi Anda.');
            } finally {
                setLoading(false);
            }
        });

        function setLoading(isLoading) {
            submitBtn.disabled = isLoading;
            resetBtn.disabled = isLoading;
            if (isLoading) {
                btnText.textContent = 'Menyimpan...';
                btnSpinner.classList.remove('hidden');
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            } else {
                btnText.textContent = 'Simpan Data';
                btnSpinner.classList.add('hidden');
                submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
            }
        }

        function showToast(type, message) {
            const toast = type === 'success' ? toastSuccess : toastError;
            const textEl = type === 'success' ? toastSuccessText : toastErrorText;
            
            if (message && textEl) textEl.textContent = message;
            
            toast.style.display = 'flex';
            
            window.setTimeout(() => {
                toast.style.display = 'none';
            }, type === 'success' ? 3000 : 5000);
        }

        function hideToasts() {
            toastSuccess.style.display = 'none';
            toastError.style.display = 'none';
        }

        function showValidationErrors(errors) {
            for (const [field, messages] of Object.entries(errors)) {
                const errorEl = document.getElementById(`error-${field}`);
                const inputEl = document.getElementById(field);
                
                if (errorEl && messages.length > 0) {
                    errorEl.textContent = messages[0];
                    errorEl.classList.remove('hidden');
                }
                
                if (inputEl) {
                    inputEl.classList.add('border-red-500', 'ring-1', 'ring-red-500');
                    inputEl.classList.remove('border-gray-700');
                }
            }
        }

        function clearErrors() {
            document.querySelectorAll('[id^="error-"]').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });
            
            form.querySelectorAll('input, select, textarea').forEach(el => {
                el.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
                el.classList.add('border-gray-700');
            });
        }
    });
</script>

<script>
// ── RAB Excel Upload (Magindah Modal) ──
document.addEventListener('DOMContentLoaded', function () {
    const rabModalFileInput    = document.getElementById('rabModalFileInput');
    const rabModalDropZone     = document.getElementById('rabModalDropZone');
    const rabModalFileIndicator= document.getElementById('rabModalFileIndicator');
    const rabModalFileName     = document.getElementById('rabModalFileName');
    const rabModalRowCount     = document.getElementById('rabModalRowCount');
    const rabModalPreview      = document.getElementById('rabModalPreview');
    const rabModalThead        = document.getElementById('rabModalThead');
    const rabModalTbody        = document.getElementById('rabModalTbody');
    const rabModalTfoot        = document.getElementById('rabModalTfoot');
    const justifikasiRabHidden = document.getElementById('justifikasi_rab');
    const closeRabBtn          = document.getElementById('closeJustifikasiModalBottomRab');

    if (!rabModalFileInput) return;

    // Drag events
    if (rabModalDropZone) {
        rabModalDropZone.addEventListener('dragover', e => {
            e.preventDefault();
            rabModalDropZone.style.borderColor = 'rgba(99,102,241,0.7)';
            rabModalDropZone.style.background = 'rgba(99,102,241,0.1)';
        });
        rabModalDropZone.addEventListener('dragleave', () => {
            rabModalDropZone.style.borderColor = 'rgba(99,102,241,0.4)';
            rabModalDropZone.style.background = 'rgba(99,102,241,0.04)';
        });
        rabModalDropZone.addEventListener('drop', e => {
            e.preventDefault();
            rabModalDropZone.style.borderColor = 'rgba(99,102,241,0.4)';
            rabModalDropZone.style.background = 'rgba(99,102,241,0.04)';
            if (e.dataTransfer.files.length) processRabExcel(e.dataTransfer.files[0]);
        });
    }

    rabModalFileInput.addEventListener('change', function () {
        if (this.files.length) processRabExcel(this.files[0]);
    });

    // Close button
    if (closeRabBtn) {
        const justifikasiModal = document.getElementById('justifikasiModal');
        const justifikasiModalBackdrop = document.getElementById('justifikasiModalBackdrop');
        const justifikasiModalPanel = document.getElementById('justifikasiModalPanel');
        closeRabBtn.addEventListener('click', () => {
            if (!justifikasiModal) return;
            justifikasiModalBackdrop.classList.add('opacity-0');
            justifikasiModalBackdrop.classList.remove('opacity-100');
            justifikasiModalPanel.classList.add('opacity-0', 'scale-95', 'translate-y-2');
            justifikasiModalPanel.classList.remove('opacity-100', 'scale-100', 'translate-y-0');
            window.setTimeout(() => justifikasiModal.classList.add('hidden'), 200);
            document.documentElement.classList.remove('overflow-hidden');
        });
    }

    function processRabExcel(file) {
        if (!file.name.match(/\.xlsx?$/i)) {
            alert('Hanya file .xlsx atau .xls yang diperkenankan.');
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            try {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, { type: 'array' });
                const sheet = workbook.Sheets[workbook.SheetNames[0]];
                const rows = XLSX.utils.sheet_to_json(sheet, { header: 1, defval: '' });

                if (!rows.length) { alert('Sheet Excel kosong.'); return; }

                // Detect header row
                let headerRowIdx = 0;
                for (let i = 0; i < Math.min(10, rows.length); i++) {
                    const rs = rows[i].join('|').toLowerCase();
                    if (rs.includes('item deskripsi') || rs.includes('deskripsi') || rs.includes('harga satuan')) {
                        headerRowIdx = i; break;
                    }
                }

                const headers = rows[headerRowIdx];
                const dataRows = rows.slice(headerRowIdx + 1).filter(r => r.some(c => String(c).trim() !== ''));

                const grandTotalLabel = 'grand total';
                let grandTotalRow = null;
                const bodyRows = dataRows.filter(r => {
                    const rt = r.join('').toLowerCase();
                    if (rt.includes(grandTotalLabel)) { grandTotalRow = r; return false; }
                    return true;
                });

                // Build HTML for PDF (saved to hidden textarea)
                let html = '<table class="rab-table" style="width:100%;border-collapse:collapse;font-size:9.5pt;">';
                html += '<thead><tr>' + headers.map(h =>
                    `<th style="background:#2c3e50;color:#fff;padding:6px 8px;border:1px solid #ccc;font-weight:bold;">${String(h).trim()}</th>`
                ).join('') + '</tr></thead><tbody>';
                bodyRows.forEach(row => {
                    html += '<tr>' + headers.map((_, i) =>
                        `<td style="padding:5px 8px;border:1px solid #ddd;vertical-align:top;">${String(row[i] !== undefined ? row[i] : '').trim()}</td>`
                    ).join('') + '</tr>';
                });
                html += '</tbody>';
                if (grandTotalRow) {
                    html += '<tfoot><tr>' + grandTotalRow.map(c =>
                        `<td style="padding:6px 8px;border:1px solid #ddd;font-weight:bold;background:#eef8ff;">${String(c).trim()}</td>`
                    ).join('') + '</tr></tfoot>';
                }
                html += '</table>';

                // Store into hidden field
                if (justifikasiRabHidden) {
                    justifikasiRabHidden.value = html;
                    // Trigger update indicators
                    const ev = new Event('input', { bubbles: true });
                    justifikasiRabHidden.dispatchEvent(ev);
                }

                // Show preview
                rabModalFileName.textContent = file.name;
                rabModalRowCount.textContent = `${bodyRows.length} baris`;
                rabModalFileIndicator.classList.remove('hidden');

                // Render preview table with contenteditable
                rabModalThead.innerHTML = '<tr>' + headers.map(h =>
                    `<th style="padding:7px 10px;color:#94a3b8;font-size:0.7rem;text-transform:uppercase;border-bottom:1px solid rgba(100,116,139,0.3);">${String(h).trim()}</th>`
                ).join('') + '</tr>';
                
                rabModalTbody.innerHTML = bodyRows.map(row =>
                    '<tr>' + headers.map((_, i) =>
                        `<td contenteditable="true" style="padding:6px 10px;border-bottom:1px solid rgba(100,116,139,0.1);color:#e2e8f0;outline:none;transition:background 0.2s;" class="hover:bg-slate-800/50 focus:bg-slate-800/80">${String(row[i] !== undefined ? row[i] : '').trim()}</td>`
                    ).join('') + '</tr>'
                ).join('');
                
                rabModalTfoot.innerHTML = grandTotalRow
                    ? '<tr>' + grandTotalRow.map(c =>
                        `<td contenteditable="true" style="padding:6px 10px;font-weight:700;color:#f59e0b;border-top:1px solid rgba(245,158,11,0.3);outline:none;">${String(c).trim()}</td>`
                    ).join('') + '</tr>'
                    : '';

                // Sync editable table to hidden HTML field
                function syncRabTableToHidden() {
                    let syncHtml = '<table class="rab-table" style="width:100%;border-collapse:collapse;font-size:9.5pt;">';
                    syncHtml += '<thead><tr>';
                    const ths = rabModalThead.querySelectorAll('th');
                    ths.forEach(th => {
                        syncHtml += `<th style="background:#2c3e50;color:#fff;padding:6px 8px;border:1px solid #ccc;font-weight:bold;">${th.innerText.trim()}</th>`;
                    });
                    syncHtml += '</tr></thead><tbody>';
                    
                    const rows = rabModalTbody.querySelectorAll('tr');
                    rows.forEach(tr => {
                        syncHtml += '<tr>';
                        const tds = tr.querySelectorAll('td');
                        tds.forEach(td => {
                            syncHtml += `<td style="padding:5px 8px;border:1px solid #ddd;vertical-align:top;">${td.innerText.trim()}</td>`;
                        });
                        syncHtml += '</tr>';
                    });
                    syncHtml += '</tbody>';
                    
                    const footRows = rabModalTfoot.querySelectorAll('tr');
                    if (footRows.length) {
                        syncHtml += '<tfoot>';
                        footRows.forEach(tr => {
                            syncHtml += '<tr>';
                            const tds = tr.querySelectorAll('td');
                            tds.forEach(td => {
                                syncHtml += `<td style="padding:6px 8px;border:1px solid #ddd;font-weight:bold;background:#eef8ff;">${td.innerText.trim()}</td>`;
                            });
                            syncHtml += '</tr>';
                        });
                        syncHtml += '</tfoot>';
                    }
                    syncHtml += '</table>';
                    
                    if (justifikasiRabHidden) {
                        justifikasiRabHidden.value = syncHtml;
                        updateJustifikasiSummaries();
                        scheduleDraftSave();
                    }
                }

                // Add listeners to table for syncing
                rabModalTbody.addEventListener('input', syncRabTableToHidden);
                rabModalTfoot.addEventListener('input', syncRabTableToHidden);

                rabModalPreview.classList.remove('hidden');

            } catch (err) {
                console.error('RAB parse error:', err);
                alert('Gagal membaca file Excel. Pastikan format file valid.');
            }
        };
        reader.readAsArrayBuffer(file);
    }
});
</script>
@endpush
@endsection

