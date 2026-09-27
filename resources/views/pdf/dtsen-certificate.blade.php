<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Keterangan DTSEN - {{ $certificate->certificate_number }}</title>
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
            letter-spacing: 0.5px;
        }
        .header h2 {
            margin: 2px 0;
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
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
            width: 200px;
        }
        .data-table td.colon {
            width: 15px;
        }
        .statement-box {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 10px 15px;
            margin: 15px 0;
            border-radius: 4px;
            font-size: 11pt;
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
        <h4>Surat Keterangan Terdaftar DTSEN</h4>
        <p>Nomor: {{ $certificate->certificate_number }}</p>
    </div>

    <!-- Isi Surat -->
    <div class="content">
        <p>Yang bertanda tangan di bawah ini, Kepala Dinas Sosial Kabupaten Blitar menerangkan dengan sebenarnya bahwa:</p>

        <table class="data-table">
            <tr>
                <td class="label">Nama Lengkap</td>
                <td class="colon">:</td>
                <td><strong>{{ strtoupper($certificate->subject_name) }}</strong></td>
            </tr>
            <tr>
                <td class="label">NIK</td>
                <td class="colon">:</td>
                <td><strong>{{ $certificate->subject_nik }}</strong></td>
            </tr>
            <tr>
                <td class="label">Nama Pemohon (Wali)</td>
                <td class="colon">:</td>
                <td>{{ $certificate->serviceRequest?->applicant_name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Hubungan dengan Pemohon</td>
                <td class="colon">:</td>
                <td>{{ $certificate->relationship_to_applicant ?? 'Diri Sendiri' }}</td>
            </tr>
            <tr>
                <td class="label">Alamat Domisili</td>
                <td class="colon">:</td>
                <td>
                    {{ $certificate->serviceRequest?->address ?? '-' }},
                    Desa/Kel. {{ $certificate->serviceRequest?->village?->name ?? '-' }},
                    Kec. {{ $certificate->serviceRequest?->village?->district?->name ?? '-' }},
                    Kabupaten Blitar
                </td>
            </tr>
        </table>

        <p>Berdasarkan hasil pemadanan data pada Sistem Administrasi Pelayanan & Pengaduan Sosial Terpadu (SAPA SOSIAL) dan Data Terpadu Sosial Ekonomi Nasional (DTSEN) Kementerian Sosial RI, bahwa yang bersangkutan:</p>

        <div class="statement-box">
            <p style="margin: 0; text-align: center;">
                <strong>BENAR-BENAR TERDAFTAR</strong> dalam Data Terpadu Sosial Ekonomi Nasional (DTSEN)<br>
                pada posisi tingkat kesejahteraan: <strong style="font-size: 13pt; text-decoration: underline;">DESIL {{ $certificate->decile ?? '-' }}</strong>
            </p>
        </div>

        <p>Surat keterangan ini diterbitkan secara sah dan dipergunakan sebagai kelengkapan persyaratan administratif: <strong>{{ $certificate->purpose?->name ?? $certificate->purpose_description ?? '-' }}</strong>.</p>

        <p>Surat keterangan ini berlaku sejak tanggal diterbitkan sampai dengan tanggal <strong>{{ $certificate->valid_until ? \Carbon\Carbon::parse($certificate->valid_until)->translatedFormat('d F Y') : 'sesuai batas waktu persyaratan' }}</strong>. Apabila di kemudian hari terdapat kekeliruan dalam surat keterangan ini, akan dilakukan perbaikan sebagaimana mestinya.</p>
    </div>

    <!-- Tanda Tangan & QR Code Verifikasi -->
    <table class="footer-table">
        <tr>
            <td class="qr-section">
                @if(!empty($qrCodeBase64))
                    <img class="qr-img" src="{{ $qrCodeBase64 }}" alt="QR Verifikasi">
                @endif
                <p style="margin: 0;"><strong>KODE VERIFIKASI:</strong><br>{{ $certificate->verification_code }}</p>
                <p style="margin-top: 3px;">Pindai kode QR untuk memverifikasi keaslian dan masa berlaku dokumen ini secara online pada sistem SAPA SOSIAL.</p>
            </td>
            <td class="sign-section">
                <p>Kanigoro, {{ $certificate->issued_at ? \Carbon\Carbon::parse($certificate->issued_at)->translatedFormat('d F Y') : \Carbon\Carbon::today()->translatedFormat('d F Y') }}</p>
                <p><strong>KEPALA DINAS SOSIAL<br>KABUPATEN BLITAR</strong></p>
                
                <div style="margin: 25px 0 10px 0;">
                    <span style="font-size: 9pt; color: #047857; border: 1px solid #10b981; padding: 4px 8px; border-radius: 4px; display: inline-block;">
                        &check; Ditandatangani secara elektronik
                    </span>
                </div>

                <p><strong><u>{{ $signerName ?? $certificate->signer?->name ?? 'Drs. BAMBANG HERMANTO, M.Si.' }}</u></strong></p>
                <p>Pembina Utama Muda</p>
                <p>NIP. {{ $signerNip ?? '196804151993031005' }}</p>
            </td>
        </tr>
    </table>

</body>
</html>
