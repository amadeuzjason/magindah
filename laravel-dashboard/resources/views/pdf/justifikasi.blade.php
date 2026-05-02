<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Justifikasi Program - {{ $proposal['NOP'] ?? 'N/A' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 16pt;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 12pt;
            color: #666;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table th, .info-table td {
            text-align: left;
            padding: 5px 10px;
            border: 1px solid #ddd;
        }
        .info-table th {
            width: 30%;
            background-color: #f9f9f9;
        }
        .section-title {
            background-color: #f0f0f0;
            padding: 8px 10px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            border-left: 4px solid #333;
        }
        .content {
            margin-bottom: 15px;
            padding: 0 10px;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        .content p {
            margin: 0 0 10px;
            white-space: pre-wrap;
            word-break: break-all;
        }
        .content img {
            max-width: 100%;
            height: auto;
        }
        .empty-field {
            color: #aaa;
            font-style: italic;
            font-size: 10pt;
        }
        .rab-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 9.5pt;
            table-layout: fixed;
            word-wrap: break-word;
        }
        .rab-table th {
            background-color: #2c3e50;
            color: #fff;
            padding: 6px 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #ccc;
            word-wrap: break-word;
        }
        .rab-table td {
            padding: 5px 8px;
            border: 1px solid #ddd;
            vertical-align: top;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        .rab-table tr:nth-child(even) td {
            background-color: #f9f9f9;
        }
        .rab-table tfoot td {
            font-weight: bold;
            background-color: #eef;
        }
        .signatures {
            margin-top: 40px;
            width: 100%;
            page-break-inside: avoid;
        }
        .signature-row {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: bottom;
            padding: 10px;
        }
        .sign-text {
            padding-bottom: 5px;
            min-height: 40px;
            text-align: center;
            font-family: inherit;
            color: #000;
        }
        .sign-title {
            font-weight: bold;
            font-size: 10pt;
            margin-bottom: 40px;
        }
        .italic-sm {
            font-style: italic;
            font-size: 9pt;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Borang Mangindah</h1>
        <p>Justifikasi Program</p>
    </div>

    <table class="info-table">
        <tr>
            <th>NOP</th>
            <td>{{ $proposal['NOP'] ?? '-' }}</td>
        </tr>
        <tr>
            <th>Nama Program</th>
            <td>{{ $proposal['PROGRAM'] ?? '-' }}</td>
        </tr>
        <tr>
            <th>Kategori</th>
            <td>{{ $proposal['KATEGORI'] ?? '-' }}</td>
        </tr>
        <tr>
            <th>Status Approval</th>
            <td><strong>{{ strtoupper($proposal['STATUS'] ?? 'DRAFT') }}</strong> (Tahap: {{ str_replace('_', ' ', $proposal['approval_stage'] ?? 'Submitted') }})</td>
        </tr>
        <tr>
            <th>Tanggal Pengajuan</th>
            <td>{{ $proposal['ingest_timestamp'] ? \Carbon\Carbon::parse($proposal['ingest_timestamp'])->translatedFormat('d F Y H:i') . ' WITA' : '-' }}</td>
        </tr>
    </table>

    <div class="section-title">1. Objective</div>
    <div class="content">
        @if(!empty($proposal['justifikasi_objective']))
            {!! $proposal['justifikasi_objective'] !!}
        @else
            <p class="empty-field">Belum diisi.</p>
        @endif
    </div>

    <div class="section-title">2. Alasan Kebutuhan</div>
    <div class="content">
        @if(!empty($proposal['justifikasi_alasan']))
            {!! $proposal['justifikasi_alasan'] !!}
        @else
            <p class="empty-field">Belum diisi.</p>
        @endif
    </div>

    <div class="section-title">3. Distribusi Pekerjaan</div>
    <div class="content">
        @if(!empty($proposal['justifikasi_distribusi']))
            {!! $proposal['justifikasi_distribusi'] !!}
        @else
            <p class="empty-field">Belum diisi.</p>
        @endif
    </div>

    <div class="section-title">4. Lingkup Pekerjaan</div>
    <div class="content">
        @if(!empty($proposal['justifikasi_lingkup']))
            {!! $proposal['justifikasi_lingkup'] !!}
        @else
            <p class="empty-field">Belum diisi.</p>
        @endif
    </div>

    <div class="section-title">5. Spesifikasi Teknis</div>
    <div class="content">
        @if(!empty($proposal['justifikasi_teknis']))
            {!! nl2br(e($proposal['justifikasi_teknis'])) !!}
        @else
            <p class="empty-field">Belum diisi.</p>
        @endif
    </div>

    <div class="section-title">6. Detail Kebutuhan (RAB)</div>
    <div class="content">
        @if(!empty($proposal['justifikasi_rab']))
            {!! $proposal['justifikasi_rab'] !!}
        @else
            <p class="empty-field">Belum diisi.</p>
        @endif
    </div>

    <div class="section-title">7. Executive Summary Program</div>
    <div class="content">
        @if(!empty($proposal['justifikasi_summary']))
            {!! $proposal['justifikasi_summary'] !!}
        @else
            <p class="empty-field">Belum diisi.</p>
        @endif
    </div>

    <!-- Tanda Tangan -->
    <div class="signatures">
        <h3 style="text-align: center; margin-bottom: 30px; border-top: 1px solid #ccc; padding-top: 20px;">Lembar Pengesahan</h3>
        
        <div class="signature-row">
            <div class="signature-box">
                <div class="sign-title">Pembuat / Pemohon</div>
                <div class="sign-text">{!! $proposal['sign_user'] ?? '<br><br><i style="color: #666; font-size: 9pt;">Belum Ditandatangani</i><div style="border-bottom: 1px solid #333; margin: 5px auto; padding-bottom: 5px; width: 80%;">&nbsp;</div><span style="font-size: 10pt; font-weight: bold; color: black;">Manager NOP Makassar</span>' !!}</div>
            </div>
            <div class="signature-box">
                <div class="sign-title">Mengetahui / Menyetujui</div>
                <div class="sign-text">{!! $proposal['sign_manager_sqa_mba'] ?? '<br><br><i style="color: #666; font-size: 9pt;">Belum Ditandatangani</i><div style="border-bottom: 1px solid #333; margin: 5px auto; padding-bottom: 5px; width: 80%;">&nbsp;</div><span style="font-size: 10pt; font-weight: bold; color: black;">Manager SQA / MBA Sulawesi</span>' !!}</div>
            </div>
        </div>

        <div class="signature-row">
            <div class="signature-box">
                <div class="sign-title">Mengetahui / Menyetujui</div>
                <div class="sign-text">{!! $proposal['sign_manager_nos'] ?? '<br><br><i style="color: #666; font-size: 9pt;">Belum Ditandatangani</i><div style="border-bottom: 1px solid #333; margin: 5px auto; padding-bottom: 5px; width: 80%;">&nbsp;</div><span style="font-size: 10pt; font-weight: bold; color: black;">Manager NOS Sulawesi</span>' !!}</div>
            </div>
            <div class="signature-box">
                <div class="sign-title">Acknowledge</div>
                <div class="sign-text">{!! $proposal['sign_gm'] ?? '<br><br><i style="color: #666; font-size: 9pt;">Belum Ditandatangani</i><div style="border-bottom: 1px solid #333; margin: 5px auto; padding-bottom: 5px; width: 80%;">&nbsp;</div><span style="font-size: 10pt; font-weight: bold; color: black;">GM RNOP Sulawesi</span>' !!}</div>
            </div>
        </div>
    </div>
</body>
</html>
