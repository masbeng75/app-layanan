<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SAPA SOSIAL — Satu Pintu Layanan Sosial Kabupaten Blitar' }}</title>
    <meta name="description" content="Sistem Administrasi Pelayanan & Pengaduan Sosial Terpadu Dinas Sosial Pemerintah Kabupaten Blitar. Layanan SK DTSEN, Reaktivasi KIS PBI-JK, Rehabilitasi Sosial.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800|plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />

    <!-- Tailwind CSS (Vite + Fallback CDN for guaranteed rendering) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        emerald: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                            950: '#022c22',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Instrument Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans flex flex-col min-h-screen">

    <!-- Top Announcement Bar -->
    <div class="bg-emerald-950 text-emerald-200 text-xs py-2 px-4 border-b border-emerald-800/40">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-1">
            <div class="flex items-center space-x-2">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Portal Resmi Pelayanan Sosial Dinas Sosial Pemerintah Kabupaten Blitar</span>
            </div>
            <div class="flex items-center space-x-4">
                <span>Jam Pelayanan: Senin - Jumat (07.30 - 15.30 WIB)</span>
                <a href="/admin" class="text-white hover:text-emerald-300 font-semibold underline underline-offset-2 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    Masuk Petugas
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-sm transition-all" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo & Brand -->
                <a href="{{ route('portal.home') }}" class="flex items-center space-x-3 group">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-700 to-emerald-500 flex items-center justify-center text-white font-black text-2xl shadow-lg shadow-emerald-600/20 group-hover:scale-105 transition-transform">
                        S
                    </div>
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="font-extrabold text-xl sm:text-2xl tracking-tight text-slate-900 group-hover:text-emerald-700 transition-colors">SAPA SOSIAL</span>
                            <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Kab. Blitar</span>
                        </div>
                        <p class="text-xs text-slate-500 font-medium hidden sm:block">Satu Pintu Layanan Sosial Terpadu</p>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center space-x-1 lg:space-x-2">
                    <a href="{{ route('portal.home') }}" class="px-3 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('portal.home') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'text-slate-600 hover:text-emerald-700 hover:bg-slate-50' }} transition-colors">
                        Beranda
                    </a>
                    <a href="{{ route('portal.catalog') }}" class="px-3 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('portal.catalog*') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'text-slate-600 hover:text-emerald-700 hover:bg-slate-50' }} transition-colors">
                        Informasi Layanan
                    </a>
                    <a href="{{ route('portal.request') }}" class="px-3 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('portal.request') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'text-slate-600 hover:text-emerald-700 hover:bg-slate-50' }} transition-colors">
                        Ajukan Layanan
                    </a>
                    <a href="{{ route('portal.complaint') }}" class="px-3 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('portal.complaint') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'text-slate-600 hover:text-emerald-700 hover:bg-slate-50' }} transition-colors">
                        Pengaduan
                    </a>
                    <a href="{{ route('portal.track') }}" class="px-3 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('portal.track') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'text-slate-600 hover:text-emerald-700 hover:bg-slate-50' }} transition-colors">
                        Cek Status
                    </a>
                    <a href="{{ route('portal.verification') }}" class="px-3 py-2 rounded-xl text-sm font-semibold {{ request()->routeIs('portal.verification') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'text-slate-600 hover:text-emerald-700 hover:bg-slate-50' }} transition-colors">
                        Verifikasi Surat
                    </a>
                </nav>

                <!-- Action Button -->
                <div class="hidden md:flex items-center space-x-3">
                    <a href="{{ route('portal.request') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md shadow-emerald-600/20 hover:shadow-lg hover:shadow-emerald-600/30 transition-all">
                        <span>Ajukan Sekarang</span>
                        <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex items-center md:hidden">
                    <button @click="open = !open" type="button" class="p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="open" x-cloak class="md:hidden border-t border-slate-200 bg-white px-4 pt-2 pb-6 space-y-2 shadow-lg">
            <a href="{{ route('portal.home') }}" class="block px-3 py-2.5 rounded-lg text-base font-semibold text-slate-800 hover:bg-emerald-50 hover:text-emerald-800">Beranda</a>
            <a href="{{ route('portal.catalog') }}" class="block px-3 py-2.5 rounded-lg text-base font-semibold text-slate-800 hover:bg-emerald-50 hover:text-emerald-800">Informasi Layanan</a>
            <a href="{{ route('portal.request') }}" class="block px-3 py-2.5 rounded-lg text-base font-semibold text-slate-800 hover:bg-emerald-50 hover:text-emerald-800">Ajukan Layanan</a>
            <a href="{{ route('portal.complaint') }}" class="block px-3 py-2.5 rounded-lg text-base font-semibold text-slate-800 hover:bg-emerald-50 hover:text-emerald-800">Pengaduan Sosial</a>
            <a href="{{ route('portal.track') }}" class="block px-3 py-2.5 rounded-lg text-base font-semibold text-slate-800 hover:bg-emerald-50 hover:text-emerald-800">Cek Status Tiket</a>
            <a href="{{ route('portal.verification') }}" class="block px-3 py-2.5 rounded-lg text-base font-semibold text-slate-800 hover:bg-emerald-50 hover:text-emerald-800">Verifikasi Dokumen</a>
            <div class="pt-3 border-t border-slate-100 flex flex-col gap-2">
                <a href="{{ route('portal.request') }}" class="w-full text-center py-3 rounded-xl bg-emerald-600 text-white font-bold text-sm shadow">Ajukan Layanan Online</a>
                <a href="/admin" class="w-full text-center py-2.5 rounded-xl bg-slate-100 text-slate-700 font-bold text-sm">Masuk Portal Petugas</a>
            </div>
        </div>
    </header>

    <!-- Main Content Slot -->
    <main class="flex-1">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-300 pt-16 pb-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                <!-- Col 1: Instansi -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center text-white font-black text-xl">S</div>
                        <span class="text-xl font-bold text-white tracking-tight">SAPA SOSIAL</span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        Sistem Administrasi Pelayanan & Pengaduan Sosial Terpadu Pemerintah Kabupaten Blitar. Wujud transparansi dan akuntabilitas pelayanan publik menuju Blitar Sejahtera.
                    </p>
                    <div class="flex items-center space-x-2 text-xs text-emerald-400 font-semibold">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Pelayanan Terintegrasi & Digital</span>
                    </div>
                </div>

                <!-- Col 2: Layanan Utama -->
                <div>
                    <h3 class="text-white font-bold text-sm uppercase tracking-wider mb-4 border-l-2 border-emerald-500 pl-3">Layanan Prioritas</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('portal.catalog') }}" class="hover:text-emerald-400 transition-colors">Surat Keterangan DTSEN</a></li>
                        <li><a href="{{ route('portal.catalog') }}" class="hover:text-emerald-400 transition-colors">Reaktivasi KIS / PBI-JK</a></li>
                        <li><a href="{{ route('portal.catalog') }}" class="hover:text-emerald-400 transition-colors">Pelayanan Rehabilitasi Sosial</a></li>
                        <li><a href="{{ route('portal.complaint') }}" class="hover:text-emerald-400 transition-colors">Pengaduan Permasalahan Sosial</a></li>
                        <li><a href="{{ route('portal.track') }}" class="hover:text-emerald-400 transition-colors">Lacak Status Pengajuan</a></li>
                    </ul>
                </div>

                <!-- Col 3: Kontak & Alamat -->
                <div>
                    <h3 class="text-white font-bold text-sm uppercase tracking-wider mb-4 border-l-2 border-emerald-500 pl-3">Kontak & Lokasi</h3>
                    <address class="not-italic text-sm text-slate-400 space-y-2.5">
                        <p class="flex items-start">
                            <svg class="w-5 h-5 text-emerald-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Jalan Kota Baru No. 10, Kanigoro, Kabupaten Blitar, Jawa Timur 66171</span>
                        </p>
                        <p class="flex items-center">
                            <svg class="w-5 h-5 text-emerald-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <span>(0342) 801123 / WA: 0812-3456-7890</span>
                        </p>
                        <p class="flex items-center">
                            <svg class="w-5 h-5 text-emerald-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span>dinsos@blitarkab.go.id</span>
                        </p>
                    </address>
                </div>

                <!-- Col 4: Keamanan & Akreditasi -->
                <div>
                    <h3 class="text-white font-bold text-sm uppercase tracking-wider mb-4 border-l-2 border-emerald-500 pl-3">Keamanan & Layanan</h3>
                    <p class="text-xs text-slate-400 leading-relaxed mb-4">
                        Dokumen digital yang diterbitkan dilengkapi Tanda Tangan Elektronik (TTE) tersertifikasi BSrE / BSSN dan kode verifikasi QR Code terintegrasi.
                    </p>
                    <div class="bg-slate-800/80 p-3 rounded-xl border border-slate-700/80 text-xs text-emerald-400">
                        <span class="font-bold text-white block mb-0.5">Layanan Bebas Pungli</span>
                        Seluruh pelayanan sosial di Dinas Sosial Kabupaten Blitar tidak dipungut biaya (GRATIS).
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright -->
            <div class="mt-12 pt-8 border-t border-slate-800 flex flex-col sm:flex-row justify-between items-center text-xs text-slate-500 gap-4">
                <p>&copy; {{ date('Y') }} Dinas Sosial Pemerintah Kabupaten Blitar. Seluruh hak cipta dilindungi.</p>
                <div class="flex items-center space-x-6">
                    <a href="{{ route('portal.verification') }}" class="hover:text-slate-300">Verifikasi Dokumen</a>
                    <a href="{{ route('portal.track') }}" class="hover:text-slate-300">Cek Tiket</a>
                    <a href="/admin" class="hover:text-slate-300">Portal Petugas</a>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
