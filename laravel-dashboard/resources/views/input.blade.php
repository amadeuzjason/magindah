@extends('layout')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-gray-800/50 backdrop-blur-md border border-gray-700 rounded-xl shadow-xl overflow-hidden">
        <div class="p-6 border-b border-gray-700/50 bg-gray-800/80">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Input Data Baru
            </h2>
            <p class="text-sm text-gray-400 mt-1">Masukkan data program baru ke dalam sistem.</p>
        </div>
        
        <form id="inputForm" class="p-6 space-y-6">
            @csrf
            
            <!-- Alert Messages -->
            <div id="alertSuccess" class="hidden bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-lg flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>Data berhasil disimpan!</span>
            </div>
            
            <div id="alertError" class="hidden bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <span id="errorText">Terjadi kesalahan saat menyimpan data.</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kolom Kiri -->
                <div class="space-y-4">
                    <div>
                        <label for="nop" class="block text-sm font-medium text-gray-300 mb-1">NOP <span class="text-red-400">*</span></label>
                        <input type="text" id="nop" name="nop" required class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Nomor Program">
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
                            <option value="New Product">New Product</option>
                            <option value="Enhancement">Enhancement</option>
                            <option value="Bug Fix">Bug Fix</option>
                            <option value="Infrastructure">Infrastructure</option>
                            <option value="Other">Other</option>
                        </select>
                        <p class="text-xs text-red-400 mt-1 hidden" id="error-kategori"></p>
                    </div>

                    <div>
                        <label for="justifikasi" class="block text-sm font-medium text-gray-300 mb-1">Justifikasi</label>
                        <textarea id="justifikasi" name="justifikasi" rows="3" class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Alasan pengajuan program..."></textarea>
                        <p class="text-xs text-red-400 mt-1 hidden" id="error-justifikasi"></p>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="proposal" class="block text-sm font-medium text-gray-300 mb-1">Proposal (PDF Only)</label>
                            <input type="file" id="proposal" name="proposal" accept=".pdf" class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-500 file:text-white hover:file:bg-blue-600">
                            <p class="text-xs text-red-400 mt-1 hidden" id="error-proposal"></p>
                        </div>
                        <div>
                            <label for="budget" class="block text-sm font-medium text-gray-300 mb-1">Budget</label>
                            <input type="number" id="budget" name="budget" class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="0">
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label for="revenue" class="block text-sm font-medium text-gray-300 mb-1">Revenue</label>
                            <input type="number" id="revenue" name="revenue" class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="0">
                        </div>
                        <div>
                            <label for="cost" class="block text-sm font-medium text-gray-300 mb-1">Cost</label>
                            <input type="number" id="cost" name="cost" class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="0">
                        </div>
                        <div>
                            <label for="profit" class="block text-sm font-medium text-gray-300 mb-1">Profit</label>
                            <input type="number" id="profit" name="profit" class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="0">
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label for="incremental_1" class="block text-xs font-medium text-gray-400 mb-1">Incremental 1</label>
                            <input type="number" id="incremental_1" name="incremental_1" class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-3 py-2 text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="0">
                        </div>
                        <div>
                            <label for="incremental_2" class="block text-xs font-medium text-gray-400 mb-1">Incremental 2</label>
                            <input type="number" id="incremental_2" name="incremental_2" class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-3 py-2 text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="0">
                        </div>
                        <div>
                            <label for="incremental_3" class="block text-xs font-medium text-gray-400 mb-1">Incremental 3</label>
                            <input type="number" id="incremental_3" name="incremental_3" class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-3 py-2 text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="0">
                        </div>
                    </div>

                    <div>
                        <label for="pilot" class="block text-sm font-medium text-gray-300 mb-1">Pilot</label>
                        <input type="text" id="pilot" name="pilot" class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Nama Pilot Project">
                    </div>

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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('inputForm');
        const submitBtn = document.getElementById('submitBtn');
        const resetBtn = document.getElementById('resetBtn');
        const btnText = document.getElementById('btnText');
        const btnSpinner = document.getElementById('btnSpinner');
        const alertSuccess = document.getElementById('alertSuccess');
        const alertError = document.getElementById('alertError');
        const errorText = document.getElementById('errorText');

        // Reset functionality
        resetBtn.addEventListener('click', function() {
            if(confirm('Apakah Anda yakin ingin mengosongkan form?')) {
                form.reset();
                hideAlerts();
                clearErrors();
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

            // Prepare UI
            setLoading(true);
            hideAlerts();
            clearErrors();

            const formData = new FormData(form);
            // No need to convert to JSON manually, send FormData directly for file upload support
            
            try {
                const response = await fetch("{{ route('input.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                        // Content-Type is automatically set with boundary for FormData
                    },
                    body: formData
                });

                const result = await response.json();

                if (response.ok) {
                    // Success
                    showAlert('success');
                    form.reset();
                    // Auto redirect to dashboard after 1.5s
                    setTimeout(() => {
                        window.location.href = "/";
                    }, 1500);
                } else {
                    // Error
                    if (response.status === 422) {
                        // Validation errors
                        showValidationErrors(result.errors);
                        showAlert('error', 'Mohon periksa kembali inputan Anda.');
                    } else {
                        // Server error
                        showAlert('error', result.message || 'Terjadi kesalahan pada server.');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('error', 'Gagal menghubungi server. Periksa koneksi Anda.');
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

        function showAlert(type, message) {
            if (type === 'success') {
                alertSuccess.classList.remove('hidden');
                alertError.classList.add('hidden');
            } else {
                alertSuccess.classList.add('hidden');
                alertError.classList.remove('hidden');
                if (message) errorText.textContent = message;
            }
        }

        function hideAlerts() {
            alertSuccess.classList.add('hidden');
            alertError.classList.add('hidden');
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
@endpush
@endsection
