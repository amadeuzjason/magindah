<x-mail::message>
# Proposal Telah Diapprove

Selamat! Proposal Anda telah disetujui oleh seluruh pihak terkait.

**Detail Proposal:**
- **NOP:** {{ $proposal['nop'] }}
- **Program:** {{ $proposal['program'] }}
- **Kategori:** {{ $proposal['kategori'] }}

<x-mail::button :url="config('app.url') . '/dashboard'">
Lihat Dashboard
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
