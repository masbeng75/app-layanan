<div>
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-b from-emerald-900 via-emerald-800 to-slate-900 text-white overflow-hidden pt-12 pb-24 md:pt-20 md:pb-32">
        <!-- Subtle Background Pattern -->
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#a7f3d0_1px,transparent_1px)] [background-size:16px_16px]"></div>
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-emerald-500/20 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 rounded-full bg-teal-500/20 blur-3xl pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-200 text-xs sm:text-sm font-semibold mb-6 backdrop-blur-sm animate-fade-in">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                <span>Pusat Layanan Kesejahteraan Sosial Terintegrasi</span>
            </div>

            <!-- Main Heading -->
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white max-w-4xl mx-auto leading-tight sm:leading-none">
                Satu Pintu Layanan Sosial <br class="hidden sm:inline">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 via-teal-200 to-emerald-400">
                    Kabupaten Blitar
                </span>
            </h1>

            <p class="mt-6 text-base sm:text-lg text-emerald-100/90 max-w-2xl mx-auto font-normal leading-relaxed">
                Pelayanan cepat, transparan, dan tanpa pungli untuk Surat Keterangan DTSEN, Rekomendasi Reaktivasi KIS PBI-JK, serta penanganan kedaruratan sosial.
            </p>

            <!-- Search / Quick Jump CTA -->
            <div class="mt-10 flex flex-wrap justify-center gap-4">
                <a href="{{ route('portal.request') }}" class="inline-flex items-center px-6 py-3.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-sm sm:text-base shadow-lg shadow-emerald-500/30 hover:shadow-emerald-400/40 transform hover:-translate-y-0.5 transition-all">
                    <svg class="w-5 h-5 mr-2 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Ajukan Layanan Online
                </a>
                <a href="{{ route('portal.complaint') }}" class="inline-flex items-center px-6 py-3.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-sm sm:text-base border border-white/20 backdrop-blur-sm transform hover:-translate-y-0.5 transition-all">
                    <svg class="w-5 h-5 mr-2 text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Lapor Pengaduan Sosial
                </a>
                <a href="{{ route('portal.track') }}" class="inline-flex items-center px-6 py-3.5 rounded-xl bg-slate-800/80 hover:bg-slate-800 text-emerald-200 font-bold text-sm sm:text-base border border-emerald-500/30 backdrop-blur-sm transform hover:-translate-y-0.5 transition-all">
                    <svg class="w-5 h-5 mr-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Lacak Status Tiket
                </a>
            </div>
        </div>
    </section>

    <!-- 4 Main Quick Action Cards (Overlap Hero) -->
    <section class="relative -mt-16 z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Card 1: Ajukan Layanan -->
            <a href="{{ route('portal.request') }}" class="group bg-white rounded-2xl p-6 shadow-xl border border-slate-100 hover:border-emerald-300 hover:shadow-2xl hover:shadow-emerald-600/10 transition-all transform hover:-translate-y-1">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 group-hover:text-emerald-700 transition-colors">Ajukan Layanan</h3>
                <p class="text-sm text-slate-500 mt-2">Daftar permohonan SK DTSEN, reaktivasi KIS, dan layanan sosial lainnya secara daring.</p>
                <div class="mt-4 flex items-center text-xs font-bold text-emerald-600 group-hover:translate-x-1 transition-transform">
                    <span>Mulai Permohonan</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>

            <!-- Card 2: Pengaduan Sosial -->
            <a href="{{ route('portal.complaint') }}" class="group bg-white rounded-2xl p-6 shadow-xl border border-slate-100 hover:border-rose-300 hover:shadow-2xl hover:shadow-rose-600/10 transition-all transform hover:-translate-y-1">
                <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center mb-4 group-hover:bg-rose-600 group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 group-hover:text-rose-700 transition-colors">Pengaduan Sosial</h3>
                <p class="text-sm text-slate-500 mt-2">Laporkan lansia/disabilitas terlantar, penyimpangan bansos, atau kasus kedaruratan PPKS.</p>
                <div class="mt-4 flex items-center text-xs font-bold text-rose-600 group-hover:translate-x-1 transition-transform">
                    <span>Kirim Laporan</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>

            <!-- Card 3: Cek Status Tiket -->
            <a href="{{ route('portal.track') }}" class="group bg-white rounded-2xl p-6 shadow-xl border border-slate-100 hover:border-blue-300 hover:shadow-2xl hover:shadow-blue-600/10 transition-all transform hover:-translate-y-1">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 group-hover:text-blue-700 transition-colors">Lacak Status</h3>
                <p class="text-sm text-slate-500 mt-2">Pantau progres pengajuan atau pengaduan secara transparan menggunakan nomor tiket Anda.</p>
                <div class="mt-4 flex items-center text-xs font-bold text-blue-600 group-hover:translate-x-1 transition-transform">
                    <span>Cek Tiket</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>

            <!-- Card 4: Verifikasi Surat -->
            <a href="{{ route('portal.verification') }}" class="group bg-white rounded-2xl p-6 shadow-xl border border-slate-100 hover:border-amber-300 hover:shadow-2xl hover:shadow-amber-600/10 transition-all transform hover:-translate-y-1">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-4 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 group-hover:text-amber-700 transition-colors">Verifikasi Dokumen</h3>
                <p class="text-sm text-slate-500 mt-2">Cek keaslian SK DTSEN atau Rekomendasi PBI yang diterbitkan dengan kode verifikasi atau QR Code.</p>
                <div class="mt-4 flex items-center text-xs font-bold text-amber-600 group-hover:translate-x-1 transition-transform">
                    <span>Validasi Keaslian</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-emerald-800 to-teal-900 rounded-3xl p-8 sm:p-12 text-white shadow-xl">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center divide-y sm:divide-y-0 sm:divide-x divide-emerald-700/50">
                <div class="p-2">
                    <span class="text-3xl sm:text-5xl font-black text-white block tracking-tight">{{ number_format($stats['service_requests']) }}</span>
                    <span class="text-xs sm:text-sm text-emerald-200 uppercase font-semibold tracking-wider mt-2 block">Permohonan Diproses</span>
                </div>
                <div class="p-2 pt-6 sm:pt-2">
                    <span class="text-3xl sm:text-5xl font-black text-emerald-300 block tracking-tight">{{ number_format($stats['complaints']) }}</span>
                    <span class="text-xs sm:text-sm text-emerald-200 uppercase font-semibold tracking-wider mt-2 block">Pengaduan Ditangani</span>
                </div>
                <div class="p-2 pt-6 sm:pt-2">
                    <span class="text-3xl sm:text-5xl font-black text-teal-300 block tracking-tight">{{ number_format($stats['active_services']) }}</span>
                    <span class="text-xs sm:text-sm text-emerald-200 uppercase font-semibold tracking-wider mt-2 block">Layanan Terintegrasi</span>
                </div>
                <div class="p-2 pt-6 sm:pt-2">
                    <span class="text-3xl sm:text-5xl font-black text-amber-300 block tracking-tight">{{ number_format($stats['villages']) }}</span>
                    <span class="text-xs sm:text-sm text-emerald-200 uppercase font-semibold tracking-wider mt-2 block">Desa & Kelurahan</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Services / SOP Section -->
    <section class="py-12 bg-white border-y border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-10">
                <div>
                    <span class="text-emerald-700 font-bold text-xs uppercase tracking-widest block">Informasi & Standar Pelayanan</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">Panduan Layanan Terpopuler</h2>
                </div>
                <a href="{{ route('portal.catalog') }}" class="mt-4 md:mt-0 inline-flex items-center text-sm font-bold text-emerald-700 hover:text-emerald-800">
                    <span>Lihat Semua Informasi Layanan</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredPages as $page)
                    <div class="bg-slate-50 rounded-2xl border border-slate-200 p-6 flex flex-col justify-between hover:border-emerald-400 hover:shadow-lg transition-all group">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="bg-emerald-100 text-emerald-800 text-[11px] font-bold px-2.5 py-0.5 rounded-full">
                                    {{ $page->category ?? 'Layanan Sosial' }}
                                </span>
                                @if($page->serviceType && $page->serviceType->sla_days)
                                    <span class="text-xs text-slate-500 font-medium">SLA: {{ $page->serviceType->sla_days }} Hari</span>
                                @endif
                            </div>
                            <h3 class="text-base font-bold text-slate-900 group-hover:text-emerald-700 transition-colors line-clamp-2">
                                {{ $page->title }}
                            </h3>
                            <p class="text-xs text-slate-600 mt-2 line-clamp-3 leading-relaxed">
                                {{ Str::limit(strip_tags($page->description), 110) }}
                            </p>
                        </div>
                        <div class="pt-6 mt-6 border-t border-slate-200/80 flex items-center justify-between">
                            <a href="{{ route('portal.detail', $page->slug) }}" class="text-xs font-bold text-slate-700 hover:text-emerald-700 flex items-center gap-1">
                                Pelajari SOP
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            <a href="{{ route('portal.request') }}" class="text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg shadow-sm">
                                Ajukan
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Alur Pelayanan (Visual 4-Step Process) -->
    <section class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-emerald-700 font-bold text-xs uppercase tracking-widest block">Proses Mudah & Transparan</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">4 Langkah Mudah Pengajuan Layanan</h2>
            <p class="text-sm text-slate-600 mt-2">Dapatkan dokumen pelayanan sosial resmi tanpa harus mengantre lama di kantor dinas.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative">
            <!-- Step 1 -->
            <div class="relative bg-white p-6 rounded-2xl border border-slate-200 text-center shadow-sm hover:shadow-md transition-shadow">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-emerald-100 text-emerald-800 font-black text-xl flex items-center justify-center mb-4">
                    1
                </div>
                <h4 class="font-bold text-slate-900 text-base">Isi Formulir Online</h4>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    Pilih jenis layanan, lengkapi data NIK, Kartu Keluarga, dan upload foto berkas persyaratan.
                </p>
            </div>

            <!-- Step 2 -->
            <div class="relative bg-white p-6 rounded-2xl border border-slate-200 text-center shadow-sm hover:shadow-md transition-shadow">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-emerald-100 text-emerald-800 font-black text-xl flex items-center justify-center mb-4">
                    2
                </div>
                <h4 class="font-bold text-slate-900 text-base">Terima Nomor Tiket</h4>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    Sistem secara instan menerbitkan kode tiket resmi untuk memantau status secara berkala.
                </p>
            </div>

            <!-- Step 3 -->
            <div class="relative bg-white p-6 rounded-2xl border border-slate-200 text-center shadow-sm hover:shadow-md transition-shadow">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-emerald-100 text-emerald-800 font-black text-xl flex items-center justify-center mb-4">
                    3
                </div>
                <h4 class="font-bold text-slate-900 text-base">Verifikasi & Telaah</h4>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    Petugas Dinsos memvalidasi kelayakan berkas, kesesuaian data DTKS/DTSEN, dan menandatangani digital.
                </p>
            </div>

            <!-- Step 4 -->
            <div class="relative bg-white p-6 rounded-2xl border border-slate-200 text-center shadow-sm hover:shadow-md transition-shadow">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-emerald-600 text-white font-black text-xl flex items-center justify-center mb-4 shadow-lg shadow-emerald-600/30">
                    4
                </div>
                <h4 class="font-bold text-slate-900 text-base">Unduh Dokumen Sah</h4>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    Unduh langsung Surat Keterangan / Rekomendasi ber-QR Code resmi tanpa biaya sepeserpun.
                </p>
            </div>
        </div>
    </section>

    <!-- Emergency Hotline Banner -->
    <section class="bg-gradient-to-r from-rose-900 via-rose-800 to-slate-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-4 text-center md:text-left">
                    <div class="w-16 h-16 rounded-2xl bg-rose-500/20 border border-rose-400/30 flex items-center justify-center flex-shrink-0 mx-auto md:mx-0">
                        <svg class="w-8 h-8 text-rose-300 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl sm:text-2xl font-bold">Tanggap Darurat Sosial 24 Jam Blitar</h3>
                        <p class="text-rose-200 text-sm mt-1">Menemukan lansia terlantar, anak terancam, atau ODGJ meresahkan? Laporkan segera ke tim siaga.</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="tel:0342801123" class="px-5 py-3 rounded-xl bg-white text-rose-900 hover:bg-rose-50 font-bold text-sm shadow-lg flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        (0342) 801123
                    </a>
                    <a href="{{ route('portal.complaint') }}" class="px-5 py-3 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-sm shadow-lg border border-rose-400/40">
                        Buat Pengaduan
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
