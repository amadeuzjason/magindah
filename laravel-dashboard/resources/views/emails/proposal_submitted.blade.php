<x-mail::message>
<div style="text-align: center; margin-bottom: 20px;">
    <img src="https://upload.wikimedia.org/wikipedia/commons/b/bc/Telkomsel_2021_icon.svg" alt="Telkomsel Logo" style="height: 50px;">
</div>

# Pengajuan Proposal Baru

Sebuah proposal baru telah diajukan dan saat ini menunggu persetujuan Anda.

**Detail Proposal:**

- **NOP:** {{ $proposal['nop'] }}
- **Program:** {{ $proposal['program'] }}
- **Kategori:** {{ $proposal['kategori'] }}
- **Diajukan oleh:** {{ $proposal['proposer_name'] ?? $proposal['assign_by'] }}

Silakan tinjau dan lakukan persetujuan melalui halaman berikut:

<x-mail::button :url="config('app.url') . '/approvals/' . ($proposal['id'] ?? '')">
Lihat Halaman Approval
</x-mail::button>

Terima kasih atas perhatian Anda.

Hormat kami,<br>
Magindah
</x-mail::message>
