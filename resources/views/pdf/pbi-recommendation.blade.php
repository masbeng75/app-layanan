<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Rekomendasi PBI-JK - {{ $pbi->recommendation_number }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 20mm 20mm 20mm 20mm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }
        .header h3 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 2px 0;
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0 0 0;
            font-size: 9.5pt;
            font-style: italic;
        }
        .title-box {
            text-align: center;
            margin-bottom: 20px;
        }
        .title-box h4 {
            margin: 0;
            font-size: 13pt;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .title-box p {
            margin: 2px 0 0 0;
            font-size: 11pt;
        }
        .content {
            text-align: justify;
        }
        .data-table {
            width: 100%;
            margin: 10px 0 15px 25px;
            border-collapse: collapse;
        }
        .data-table td {
            padding: 3px 4px;
            vertical-align: top;
            font-size: 11.5pt;
        }
        .data-table td.label {
            width: 220px;
        }
        .data-table td.colon {
            width: 15px;
        }
        .footer-table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }
        .footer-table td {
            vertical-align: top;
        }
        .qr-section {
            width: 45%;
            font-size: 8pt;
            color: #334155;
            padding-right: 15px;
        }
        .qr-img {
            width: 90px;
            height: 90px;
            margin-bottom: 5px;
        }
        .sign-section {
            width: 55%;
            text-align: center;
            font-size: 11pt;
        }
        .sign-section p {
            margin: 2px 0;
        }
    </style>
</head>
<body>

    <!-- Kop Surat Resmi -->
    <div class="header">
        <h3>Pemerintah Kabupaten Blitar</h3>
        <h2>Dinas Sosial</h2>
        <p>Jalan Kota Baru No. 10 Kanigoro, Blitar, Jawa Timur 66171 &bull; Telp. (0342) 801123 &bull; Email: dinsos@blitarkab.go.id</p>
    </div>

    <!-- Judul Surat -->
    <div class="title-box">
        <h4>Surat Rekomendasi Reaktivasi Kepesertaan KIS PBI-JK</h4>
        <p>Nomor: {{ $pbi->recommendation_number }}</p>
    </div>

    <!-- Isi Surat -->
    <div class="content">
        <p>Berdasarkan permohonan reaktivasi kepesertaan Program Jaminan Kesehatan Nasional bagi Penerima Bantuan Iuran Jaminan Kesehatan (PBI-JK) yang dinonaktifkan, Kepala Dinas Sosial Kabupaten Blitar dengan ini menerangkan data peserta sebagai berikut:</p>

        <table class="data-table">
            <tr>
                <td class="label">Nama Peserta</td>
                <td class="colon">:</td>
                <td><strong>{{ strtoupper($pbi->participant_name) }}</strong></td>
            </tr>
            <tr>
                <td class="label">Nomor Induk Kependudukan (NIK)</td>
                <td class="colon">:</td>
                <td><strong>{{ $pbi->participant_nik }}</strong></td>
            </tr>
            <tr>
                <td class="label">Nomor Kartu BPJS / KIS</td>
                <td class="colon">:</td>
                <td><strong>{{ $pbi->bpjs_card_number }}</strong></td>
            </tr>
            <tr>
                <td class="label">Alamat Domisili</td>
                <td class="colon">:</td>
                <td>
                    {{ $pbi->serviceRequest?->address ?? '-' }},
                    Desa/Kel. {{ $pbi->serviceRequest?->village?->name ?? '-' }},
                    Kec. {{ $pbi->serviceRequest?->village?->district?->name ?? '-' }},
                    Kabupaten Blitar
                </td>
            </tr>
            <tr>
                <td class="label">Alasan Kebutuhan Medis</td>
                <td class="colon">:</td>
                <td><strong>{{ $pbi->reason?->label() ?? $pbi->reason }}</strong></td>
            </tr>
            @if($pbi->health_facility_name)
            <tr>
                <td class="label">Fasilitas Kesehatan Perujuk</td>
                <td class="colon">:</td>
                <td>{{ $pbi->health_facility_name }}</td>
            </tr>
            @endif
            @if($pbi->health_letter_number)
            <tr>
                <td class="label">Nomor Surat Medis / Opname</td>
                <td class="colon">:</td>
                <td>{{ $pbi->health_letter_number }}</td>
            </tr>
            @endif
            <tr>
                <td class="label">Desil Kemiskinan DTSEN</td>
                <td class="colon">:</td>
                <td>Desil {{ $pbi->decile ?? '-' }}</td>
            </tr>
        </table>

        <p>Setelah dilakukan verifikasi dokumen pendukung medis dan pemadanan sosial ekonomi, yang bersangkutan dinyatakan <strong>MEMENUHI SYARAT DAN KELAYAKAN</strong> untuk direkomendasikan pengaktifan kembali status kepesertaannya pada segmen Penerima Bantuan Iuran Jaminan Kesehatan (PBI-JK) APBN melalui Kementerian Sosial Republik Indonesia.</p>

        <p>Demikian surat rekomendasi ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>

    <!-- Tanda Tangan & QR Code Verifikasi -->
    <table class="footer-table">
        <tr>
            <td class="qr-section">
                @if(!empty($qrCodeBase64))
                    <img class="qr-img" src="{{ $qrCodeBase64 }}" alt="QR Verifikasi">
                @endif
                <p style="margin: 0;"><strong>NO. REKOMENDASI:</strong><br>{{ $pbi->recommendation_number }}</p>
                <p style="margin-top: 3px;">Pindai kode QR untuk memverifikasi keabsahan surat rekomendasi ini secara online pada sistem SAPA SOSIAL Dinas Sosial Kab. Blitar.</p>
            </td>
            <td class="sign-section">
                <p>Kanigoro, {{ $pbi->recommendation_issued_at ? \Carbon\Carbon::parse($pbi->recommendation_issued_at)->translatedFormat('d F Y') : \Carbon\Carbon::today()->translatedFormat('d F Y') }}</p>
                <p><strong>KEPALA DINAS SOSIAL<br>KABUPATEN BLITAR</strong></p>
                
                <div style="margin: 25px 0 10px 0;">
                    <span style="font-size: 9pt; color: #047857; border: 1px solid #10b981; padding: 4px 8px; border-radius: 4px; display: inline-block;">
                        &check; Ditandatangani secara elektronik
                    </span>
                </div>

                <p><strong><u>{{ $signerName ?? $pbi->signer?->name ?? 'Drs. BAMBANG HERMANTO, M.Si.' }}</u></strong></p>
                <p>Pembina Utama Muda</p>
                <p>NIP. {{ $signerNip ?? '196804151993031005' }}</p>
            </td>
        </tr>
    </table>

</body>
</html>
