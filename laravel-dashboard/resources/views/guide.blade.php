@extends('layout')

@section('content')
<div class="flex flex-col gap-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-white tracking-wide">Panduan Aplikasi Magindah</h2>
            <p class="text-sm text-gray-400">Langkah-langkah menggunakan aplikasi.</p>
        </div>
    </div>

    <div class="glass p-6 rounded-3xl shadow-xl border border-gray-700/30">
        <div class="space-y-6 text-gray-300 text-sm">
            <section class="border-b border-gray-700/50 pb-4">
                <h3 class="text-lg font-bold text-blue-400 mb-2">1. Input Magindah</h3>
                <p>Fitur <strong>Magindah</strong> digunakan untuk menginput data program baru.</p>
                <ul class="list-disc ml-5 mt-2 space-y-1">
                    <li>Isi data NOP, Nama Program, Kategori, Budget, Revenue, dll.</li>
                    <li>Klik <strong>Isi Justifikasi</strong> untuk mengisi subbab justifikasi.</li>
                    <li>Pada pop-up justifikasi, Anda bisa menggunakan <strong>Editor Teks</strong> untuk memasukkan tulisan, list, dan gambar.</li>
                    <li>Untuk <strong>Detail Kebutuhan (RAB)</strong>, Anda bisa mengunggah file Excel (BOQ) yang akan langsung diproses menjadi tabel.</li>
                    <li>Setelah semua terisi, klik <strong>Simpan Data</strong>. Status awal akan menjadi "Submitted" dan dikirim ke manager terkait.</li>
                </ul>
            </section>
            
            <section class="border-b border-gray-700/50 pb-4">
                <h3 class="text-lg font-bold text-blue-400 mb-2">2. Approvals</h3>
                <p>Fitur <strong>Approvals</strong> digunakan untuk melihat seluruh daftar proposal dan melakukan persetujuan (Approve/Reject) bagi user yang memiliki akses.</p>
                <ul class="list-disc ml-5 mt-2 space-y-1">
                    <li>Anda bisa melihat daftar proposal, difilter berdasarkan status atau dicari menggunakan nama/NOP.</li>
                    <li>Manager yang berhak akan melihat tombol <strong>Approve</strong> atau <strong>Reject</strong> pada proposal yang sedang dalam antrean persetujuan.</li>
                    <li>Anda bisa mengklik nama dokumen untuk <strong>Preview PDF</strong>.</li>
                    <li><strong>Catatan:</strong> Dokumen PDF tidak bisa diunduh (tombol Unduh PDF disembunyikan) jika proposal belum disetujui (Approved) sepenuhnya.</li>
                </ul>
            </section>
            
            <section>
                <h3 class="text-lg font-bold text-blue-400 mb-2">3. Profile & Pengaturan</h3>
                <p>Di halaman Profile, Anda dapat melengkapi informasi akun dan <strong>Tanda Tangan (Signature)</strong>.</p>
                <ul class="list-disc ml-5 mt-2 space-y-1">
                    <li>Tanda tangan yang diunggah akan otomatis terlampir di halaman akhir PDF proposal (Lembar Pengesahan) saat Anda menyetujui (Approve) proposal.</li>
                    <li>Bagi user Admin, terdapat menu khusus untuk menambahkan atau mengedit user.</li>
                </ul>
            </section>
        </div>
    </div>
</div>
@endsection
