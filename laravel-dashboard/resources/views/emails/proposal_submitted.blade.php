<x-mail::message>
# Pengajuan Proposal Baru

Proposal baru telah diajukan dan memerlukan persetujuan Anda.

**Detail Proposal:**
- **NOP:** {{ $proposal['nop'] }}
- **Program:** {{ $proposal['program'] }}
- **Kategori:** {{ $proposal['kategori'] }}
- **Diajukan Oleh:** {{ $proposal['assign_by'] }}

<x-mail::button :url="config('app.url') . '/approvals'">
Lihat Halaman Approval
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
