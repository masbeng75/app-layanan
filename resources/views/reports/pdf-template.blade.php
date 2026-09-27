<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Laporan Dinas Sosial Kabupaten Blitar' }}</title>
    <style>
        @page {
            margin: 20mm 15mm 20mm 15mm;
            size: A4 landscape;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10pt;
            color: #1e293b;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header h3 {
            margin: 0;
            font-size: 13pt;
            font-weight: normal;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 3px 0;
            font-size: 16pt;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0;
            font-size: 9pt;
            color: #475569;
        }
        .report-title {
            text-align: center;
            margin-bottom: 15px;
        }
        .report-title h1 {
            font-size: 13pt;
            font-weight: bold;
            margin: 0 0 5px 0;
            text-transform: uppercase;
            color: #0f172a;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 12px;
            font-size: 9pt;
        }
        .meta-table td {
            padding: 2px 4px;
        }
        .meta-table .label {
            width: 15%;
            font-weight: bold;
            color: #334155;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 8.5pt;
        }
        .data-table th, .data-table td {
            border: 1px solid #94a3b8;
            padding: 5px 6px;
            text-align: left;
        }
        .data-table th {
            background-color: #f1f5f9;
            font-weight: bold;
            color: #0f172a;
            text-align: center;
        }
        .data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .summary-box {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 8px 12px;
            margin-bottom: 20px;
            font-size: 9pt;
        }
        .summary-box strong {
            color: #0f172a;
        }
        .signature-table {
            width: 100%;
            margin-top: 25px;
            page-break-inside: avoid;
        }
        .signature-table td {
            vertical-align: top;
            width: 50%;
        }
        .signature-box {
            text-align: center;
            width: 250px;
            margin-left: auto;
        }
        .signature-space {
            height: 60px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h3>Pemerintah Kabupaten Blitar</h3>
        <h2>Dinas Sosial</h2>
        <p>Jl. Sudirman No. 12, Kepanjenkidul, Kec. Kepanjenkidul, Kabupaten Blitar, Jawa Timur 66117</p>
        <p>Email: dinsos@blitarkab.go.id | Portal Layanan Terpadu: SAPA SOSIAL</p>
    </div>

    <div class="report-title">
        <h1>{{ $title }}</h1>
        <p style="margin: 0; font-size: 9pt; color: #64748b;">Periode / Kriteria: {{ $subtitle ?? 'Semua Data' }}</p>
    </div>

    @if(!empty($meta))
    <table class="meta-table">
        @foreach($meta as $key => $val)
        <tr>
            <td class="label">{{ $key }}</td>
            <td style="width: 2%;">:</td>
            <td>{{ $val }}</td>
        </tr>
        @endforeach
    </table>
    @endif

    @if(!empty($summaryText))
    <div class="summary-box">
        <strong>Ringkasan Eksekutif:</strong> {{ $summaryText }}
    </div>
    @endif

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                @foreach($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    @foreach($row as $cell)
                        <td>{{ $cell }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($headers) + 1 }}" class="text-center" style="padding: 15px; color: #64748b;">
                        Tidak ada data yang sesuai dengan kriteria filter yang dipilih.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="signature-table">
        <tr>
            <td>
                <span style="font-size: 8pt; color: #64748b;">
                    Dicetak otomatis oleh Sistem SAPA SOSIAL<br>
                    Waktu Cetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB
                </span>
            </td>
            <td>
                <div class="signature-box">
                    <p style="margin-bottom: 2px;">Blitar, {{ now()->translatedFormat('d F Y') }}</p>
                    <p style="margin-top: 0; font-weight: bold;">Kepala Dinas Sosial<br>Kabupaten Blitar</p>
                    <div class="signature-space"></div>
                    <p style="margin-bottom: 0; font-weight: bold; text-decoration: underline;">Drs. H. BAMBANG SETIAWAN, M.Si</p>
                    <p style="margin-top: 2px; font-size: 8.5pt;">Pembina Utama Muda / IV c<br>NIP. 19740512 199803 1 004</p>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
