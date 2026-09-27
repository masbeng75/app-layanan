<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Keaslian Dokumen — SAPA SOSIAL Kab. Blitar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen text-slate-800 antialiased flex flex-col justify-between">

    <!-- Top Header -->
    <header class="bg-emerald-800 text-white shadow-md">
        <div class="max-w-4xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center font-black text-xl text-emerald-300">
                    S
                </div>
                <div>
                    <h1 class="font-bold text-base md:text-lg leading-tight">SAPA SOSIAL</h1>
                    <p class="text-xs text-emerald-200">Dinas Sosial Pemerintah Kabupaten Blitar</p>
                </div>
            </div>
            <span class="text-xs bg-emerald-700/60 text-emerald-100 px-3 py-1 rounded-full border border-emerald-500/30">
                Layanan Verifikasi Publik
            </span>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-3xl w-full mx-auto px-4 py-8 flex-1">
        @if($certificate && $isValid)
            <!-- Valid Document Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
                <div class="bg-emerald-600 px-6 py-5 text-white flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-emerald-100">Status Keabsahan</span>
                        <h2 class="text-lg md:text-xl font-extrabold text-white">DOKUMEN RESMI & TERVERIFIKASI SAH</h2>
                    </div>
                </div>

                <div class="p-6 md:p-8 space-y-6">
                    <div class="border-b border-slate-100 pb-4">
                        <span class="text-xs font-bold text-slate-400 uppercase">Jenis Dokumen</span>
                        <h3 class="text-base md:text-lg font-bold text-slate-900 mt-1">{{ $documentTitle }}</h3>
                        <p class="text-sm font-semibold text-emerald-700 font-mono mt-0.5">No. {{ $documentNumber }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <span class="text-xs text-slate-500 block">Nama Subjek / Pemohon</span>
                            <span class="font-bold text-slate-900 text-base mt-1 block">{{ $subjectName }}</span>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <span class="text-xs text-slate-500 block">Nomor Induk Kependudukan (NIK)</span>
                            <span class="font-mono font-bold text-slate-900 text-base mt-1 block">{{ $maskedNik }}</span>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 md:col-span-2">
                            <span class="text-xs text-slate-500 block">Peruntukan / Keperluan</span>
                            <span class="font-semibold text-slate-900 text-sm mt-1 block">{{ $purpose }}</span>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <span class="text-xs text-slate-500 block">Tanggal Diterbitkan</span>
                            <span class="font-semibold text-slate-900 text-sm mt-1 block">
                                {{ $issuedAt ? \Carbon\Carbon::parse($issuedAt)->translatedFormat('d F Y H:i') : '-' }} WIB
                            </span>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <span class="text-xs text-slate-500 block">Masa Berlaku</span>
                            <span class="font-semibold text-slate-900 text-sm mt-1 block">
                                {{ $validUntil ? \Carbon\Carbon::parse($validUntil)->translatedFormat('d F Y') : 'Sesuai Ketentuan yang Berlaku' }}
                            </span>
                        </div>
                        @if($decile)
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 md:col-span-2">
                            <span class="text-xs text-slate-500 block">Status Desil DTSEN</span>
                            <span class="inline-block mt-1 font-bold text-emerald-800 bg-emerald-100 px-3 py-1 rounded-lg text-sm">
                                Desil {{ $decile }} (Terdaftar dalam Data Terpadu Sosial Ekonomi Nasional)
                            </span>
                        </div>
                        @endif
                    </div>

                    <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-xl flex items-center space-x-3 text-xs text-emerald-900">
                        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p>Dokumen ini ditandatangani secara elektronik oleh <strong>{{ $signerName }}</strong> menggunakan sertifikat elektronik tersertifikasi dan tercatat resmi pada sistem SAPA SOSIAL Dinas Sosial Kabupaten Blitar.</p>
                    </div>
                </div>
            </div>

        @elseif($certificate && $isExpired)
            <!-- Expired Document Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-amber-200 overflow-hidden">
                <div class="bg-amber-600 px-6 py-5 text-white flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-amber-100">Status Keabsahan</span>
                        <h2 class="text-lg md:text-xl font-extrabold text-white">MASA BERLAKU DOKUMEN TELAH BERAKHIR</h2>
                    </div>
                </div>
                <div class="p-6 md:p-8 space-y-4">
                    <p class="text-sm text-slate-700">Surat Keterangan dengan nomor <strong>{{ $documentNumber }}</strong> atas nama <strong>{{ $subjectName }}</strong> pernah diterbitkan secara resmi, namun masa berlakunya telah berakhir pada tanggal <strong>{{ \Carbon\Carbon::parse($validUntil)->translatedFormat('d F Y') }}</strong>.</p>
                    <div class="bg-amber-50 p-4 rounded-xl text-xs text-amber-900 border border-amber-200">
                        Apabila masih memerlukan surat keterangan aktif, silakan mengajukan permohonan pembaruan kembali melalui portal SAPA SOSIAL atau kantor Dinas Sosial Kabupaten Blitar.
                    </div>
                </div>
            </div>

        @else
            <!-- Invalid / Not Found Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-rose-200 overflow-hidden text-center p-8 md:p-12">
                <div class="w-16 h-16 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <h2 class="text-xl md:text-2xl font-black text-rose-600">DOKUMEN TIDAK TERDAFTAR / TIDAK VALID</h2>
                <p class="text-sm text-slate-600 mt-2 max-w-md mx-auto">
                    Kode verifikasi <code class="font-mono bg-slate-100 px-2 py-0.5 rounded text-rose-700 font-bold">{{ $verificationCode ?? 'N/A' }}</code> tidak ditemukan di dalam pangkalan data arsip resmi Dinas Sosial Kabupaten Blitar.
                </p>
                <div class="mt-6 inline-block bg-slate-50 border border-slate-200 p-4 rounded-xl text-xs text-slate-600 max-w-md text-left">
                    <p class="font-bold text-slate-800 mb-1">Perhatian Keamanan:</p>
                    <p>Pastikan Anda memindai kode QR asli yang tercetak pada dokumen resmi Pemerintah Kabupaten Blitar. Waspadalah terhadap indikasi pemalsuan dokumen atau tanda tangan digital.</p>
                </div>
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-slate-100 border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        <p>&copy; {{ date('Y') }} Dinas Sosial Pemerintah Kabupaten Blitar. Seluruh hak cipta dilindungi.</p>
        <p class="mt-1">SAPA SOSIAL — Sistem Administrasi Pelayanan & Pengaduan Sosial Terpadu</p>
    </footer>

</body>
</html>
