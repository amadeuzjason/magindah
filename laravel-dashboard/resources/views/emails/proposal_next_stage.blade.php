<x-mail::message>
<div style="text-align: center; margin-bottom: 20px;">
    <img src="https://upload.wikimedia.org/wikipedia/commons/b/bc/Telkomsel_2021_icon.svg" alt="Telkomsel Logo" style="height: 50px;">
</div>

# Menunggu Persetujuan Anda

Proposal ini telah disetujui oleh tahap sebelumnya dan sekarang menunggu persetujuan Anda di tahap **{{ $stageName }}**.

**Detail Proposal:**
- **NOP:** {{ $proposal['nop'] }}
- **Program:** {{ $proposal['program'] }}
- **Kategori:** {{ $proposal['kategori'] }}
- **Diajukan Oleh:** {{ $proposal['proposer_name'] ?? $proposal['assign_by'] }}

<x-mail::button :url="config('app.url') . '/approvals'">
Lihat & Setujui
</x-mail::button>

Terima kasih,<br>
Magindah
</x-mail::message>
