# Magindah - Integrated Proposal & Approval System

Magindah adalah platform manajemen proposal digital yang dirancang untuk menyederhanakan alur kerja pengajuan, peninjauan, hingga persetujuan proposal teknis secara terintegrasi. Dibangun menggunakan framework Laravel, sistem ini menawarkan antarmuka modern yang responsif dan fitur manajemen data yang kuat.

## 🌟 Fitur Utama

### 1. Dashboard Analytics
*   **Ringkasan Statistik**: Visualisasi total proposal, status pending, approved, dan rejected.
*   **Grafik Interaktif**: Distribusi proposal berdasarkan kategori dan wilayah (NOP).
*   **Role-Based View**: Tampilan dashboard yang menyesuaikan dengan jabatan dan lokasi branch user.

### 2. Sistem Pengajuan Magindah
*   **Input Terstruktur**: Form pengajuan dengan validasi ketat untuk Justifikasi Teknis, Operasional, dan Layanan.
*   **Excel RAB Importer**: Fitur untuk mengunggah file Excel RAB yang secara otomatis dikonversi menjadi tabel di dalam sistem dan PDF.
*   **Draft System**: Penyimpanan otomatis (auto-save) untuk mencegah kehilangan data saat pengisian form.

### 3. Workflow Persetujuan (Approvals)
*   **Multi-Stage Approval**: Alur persetujuan bertahap mulai dari Manager wilayah hingga General Manager RNOP Sulawesi.
*   **Notifikasi Email**: Pengiriman notifikasi otomatis via Gmail kepada pengaju dan pemberi persetujuan pada setiap tahapan status.
*   **Detail Stage**: Pelacakan status persetujuan yang transparan (Stage Approval: Manager SQA/MBA/NOS/RNOP).

### 4. Manajemen User & Keamanan
*   **Role-Based Access Control (RBAC)**: Pembatasan akses fitur berdasarkan Jabatan (Engineer, Staff, Supervisor, Manager, GM, VP).
*   **Branch Management**: Pengelompokan user berdasarkan wilayah operasional (NOP Makassar, Manado, Kendari, Palu, SQA Sulawesi, dll).
*   **Datalist Input**: Mempermudah administrasi user dengan pilihan drop-down yang fleksibel.

### 5. Dokumentasi Profesional
*   **PDF Generator**: Konversi proposal menjadi dokumen PDF resmi yang siap cetak.
*   **Digital Signature**: Integrasi tanda tangan digital otomatis pada dokumen PDF berdasarkan status persetujuan akhir.

## 🛠️ Arsitektur Teknologi

*   **Backend**: Laravel 10.x
*   **Frontend**: Tailwind CSS, Blade Templates, Vanilla JavaScript
*   **Database**: MySQL
*   **Document Processing**: DomPDF (PDF Generation), SheetJS (Excel Parsing)
*   **Mailing**: Laravel Mail with Gmail SMTP Integration

## 📥 Panduan Instalasi

1. **Clone Repository**:
   ```bash
   git clone https://github.com/amadeuzjason/magindah.git
   ```

2. **Install Dependencies**:
   ```bash
   composer install
   npm install && npm run dev
   ```

3. **Konfigurasi Environment**:
   * Salin `.env.example` ke `.env`.
   * Atur koneksi database dan kredensial SMTP Gmail untuk fitur notifikasi.

4. **Generate Key & Migrate**:
   ```bash
   php artisan key:generate
   php artisan migrate --seed
   ```

5. **Jalankan Server**:
   ```bash
   php artisan serve
   ```

## 📝 Catatan Rilis
Sistem ini terus dikembangkan untuk mendukung efisiensi operasional dengan fokus pada integritas data dan kemudahan penggunaan bagi seluruh jajaran manajemen dan staff operasional.

---
© 2026 Magindah — Developed by Janiator Jr. Powered by **Telkomsel**.
