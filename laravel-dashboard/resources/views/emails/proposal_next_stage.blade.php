<x-mail::message>
# Menunggu Persetujuan Anda

Proposal ini telah disetujui oleh tahap sebelumnya dan sekarang menunggu persetujuan Anda di tahap **{{ $stageName }}**.

**Detail Proposal:**
- **NOP:** {{ $proposal['nop'] }}
- **Program:** {{ $proposal['program'] }}
- **Kategori:** {{ $proposal['kategori'] }}
- **Diajukan Oleh:** {{ $proposal['assign_by'] }}

<x-mail::button :url="config('app.url') . '/approvals'">
Lihat & Setujui
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
