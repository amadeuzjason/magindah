<x-mail::message>
<div style="text-align: center; margin-bottom: 20px;">
    <img src="https://upload.wikimedia.org/wikipedia/commons/b/bc/Telkomsel_2021_icon.svg" alt="Telkomsel Logo" style="height: 50px;">
</div>

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
Magindah
</x-mail::message>
