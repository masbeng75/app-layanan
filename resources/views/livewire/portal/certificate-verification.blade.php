<div>
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-amber-950 via-slate-900 to-emerald-950 text-white py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <nav class="flex text-xs font-semibold text-amber-300 mb-2 space-x-2 justify-center">
                <a href="{{ route('portal.home') }}" class="hover:underline">Beranda</a>
                <span>/</span>
                <span class="text-white">Verifikasi Keaslian Surat</span>
            </nav>
            <h1 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight">Verifikasi Keaslian Dokumen Digital</h1>
            <p class="mt-2 text-amber-100 text-xs sm:text-sm max-w-xl mx-auto">
                Cek keabsahan Surat Keterangan DTSEN dan Rekomendasi Reaktivasi KIS PBI-JK yang diterbitkan resmi oleh Dinas Sosial Kabupaten Blitar.
            </p>
        </div>
    </div>

    <!-- Search Form Card -->
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6">
        <div class="bg-white rounded-3xl shadow-xl border border-slate-200 p-6 sm:p-8">
            <form wire:submit="verifyCode" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Kode Verifikasi / Nomor Dokumen <span class="text-rose-500">*</span>
                    </label>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <input 
                            type="text" 
                            wire:model="code" 
                            placeholder="Contoh: A1B2C3D4E5F6 atau DTSEN-202609-00001" 
                            class="flex-1 px-4 py-3 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-amber-500 font-mono font-bold text-slate-800 placeholder-slate-400 uppercase"
                        >
                        <button 
                            type="submit" 
                            wire:loading.attr="disabled"
                            class="px-8 py-3 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-extrabold text-sm shadow-lg shadow-amber-600/30 flex items-center justify-center gap-2 transition-all disabled:opacity-50"
                        >
                            <span wire:loading.remove>Cek Dokumen</span>
                            <span wire:loading class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                Memeriksa...
                            </span>
                        </button>
                    </div>
                    @error('code') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </form>
        </div>
    </div>

    <!-- Verification Result -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @if($hasSearched)
            @if($isValid)
                <!-- Valid Document Card -->
                <div class="bg-white rounded-3xl shadow-xl border border-emerald-200 overflow-hidden animate-fade-in">
                    <div class="bg-emerald-600 p-6 sm:p-8 text-white flex items-center space-x-4">
                        <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-emerald-100">Status Keabsahan Dokumen</span>
                            <h2 class="text-xl sm:text-2xl font-black">DOKUMEN RESMI & TERVERIFIKASI SAH</h2>
                            <p class="text-xs text-emerald-100 mt-0.5">Tercatat aktif dalam pangkalan data resmi Dinas Sosial Kabupaten Blitar.</p>
                        </div>
                    </div>

                    <div class="p-6 sm:p-8 space-y-6">
                        <div class="border-b border-slate-100 pb-4">
                            <span class="text-xs font-bold text-slate-400 uppercase">Jenis Surat / Dokumen</span>
                            <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 mt-1">{{ $documentTitle }}</h3>
                            <p class="text-sm font-semibold text-emerald-700 font-mono mt-0.5">No: {{ $documentNumber }}</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs sm:text-sm">
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="text-xs text-slate-500 block">Nama Subjek / Pemohon</span>
                                <span class="font-bold text-slate-900 text-base mt-1 block">{{ $subjectName }}</span>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="text-xs text-slate-500 block">Nomor Induk Kependudukan (NIK)</span>
                                <span class="font-mono font-bold text-slate-900 text-base mt-1 block">{{ $maskedNik }}</span>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 md:col-span-2">
                                <span class="text-xs text-slate-500 block">Peruntukan / Keperluan Surat</span>
                                <span class="font-semibold text-slate-900 mt-1 block">{{ $purpose }}</span>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="text-xs text-slate-500 block">Tanggal Diterbitkan</span>
                                <span class="font-semibold text-slate-900 mt-1 block">{{ $issuedAt }}</span>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="text-xs text-slate-500 block">Masa Berlaku Dokumen</span>
                                <span class="font-semibold text-slate-900 mt-1 block">{{ $validUntil }}</span>
                            </div>
                            @if($decile)
                                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 md:col-span-2">
                                    <span class="text-xs text-slate-500 block">Tingkat Kesejahteraan / Desil DTSEN</span>
                                    <span class="inline-block mt-1 font-bold text-emerald-800 bg-emerald-100 px-3 py-1 rounded-lg text-xs sm:text-sm">
                                        Desil {{ $decile }} (Terdaftar dalam Data Terpadu Sosial Ekonomi Nasional)
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-2xl flex items-center gap-3 text-xs text-emerald-950">
                            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <p>Ditandatangani secara elektronik oleh <strong>{{ $signerName }}</strong> menggunakan sertifikat elektronik tersertifikasi BSSN/BSrE.</p>
                        </div>
                    </div>
                </div>

            @elseif($isExpired)
                <!-- Expired Document Card -->
                <div class="bg-white rounded-3xl shadow-xl border border-amber-200 overflow-hidden animate-fade-in">
                    <div class="bg-amber-600 p-6 sm:p-8 text-white flex items-center space-x-4">
                        <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-amber-100">Status Keabsahan</span>
                            <h2 class="text-xl sm:text-2xl font-black">MASA BERLAKU DOKUMEN TELAH BERAKHIR</h2>
                            <p class="text-xs text-amber-100 mt-0.5">Surat resmi tercatat, namun masa berlakunya telah melewati batas waktu.</p>
                        </div>
                    </div>
                    <div class="p-6 sm:p-8 space-y-4 text-xs sm:text-sm">
                        <p class="text-slate-700">
                            Surat Keterangan dengan nomor <strong>{{ $documentNumber }}</strong> atas nama <strong>{{ $subjectName }}</strong> berakhir pada <strong>{{ $validUntil }}</strong>.
                        </p>
                        <div class="bg-amber-50 p-4 rounded-2xl text-xs text-amber-900 border border-amber-200">
                            Apabila masih membutuhkan surat aktif untuk persyaratan bansos, pendidikan, atau kesehatan, silakan mengajukan permohonan pembaruan kembali secara daring.
                        </div>
                    </div>
                </div>

            @else
                <!-- Invalid Card -->
                <div class="bg-white rounded-3xl shadow-xl border border-rose-200 overflow-hidden text-center p-8 sm:p-12 animate-fade-in max-w-lg mx-auto">
                    <div class="w-16 h-16 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-rose-600">DOKUMEN TIDAK VALID / TIDAK TERDAFTAR</h2>
                    <p class="text-xs sm:text-sm text-slate-600 mt-2">
                        Kode verifikasi <code class="font-mono bg-slate-100 px-2 py-0.5 rounded text-rose-700 font-bold">{{ $verificationCode }}</code> tidak ditemukan pada basis data resmi Dinas Sosial Kabupaten Blitar.
                    </p>
                    <div class="mt-6 bg-slate-50 border border-slate-200 p-4 rounded-2xl text-xs text-slate-600 text-left">
                        <strong class="font-bold text-slate-800 block mb-1">Peringatan Keamanan:</strong>
                        Pastikan Anda memindai kode QR asli yang tertera di dokumen fisik atau softcopy resmi. Waspadai indikasi pemalsuan tanda tangan digital atau manipulasi surat keterangan.
                    </div>
                </div>
            @endif
        @else
            <!-- Information / Guide Card -->
            <div class="bg-white rounded-3xl border border-slate-200 p-8 sm:p-10 shadow-sm max-w-2xl mx-auto text-center">
                <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Cara Memverifikasi Dokumen</h3>
                <p class="text-xs sm:text-sm text-slate-500 mt-2 leading-relaxed">
                    Setiap dokumen resmi yang diterbitkan oleh Dinas Sosial Kabupaten Blitar dilengkapi dengan <strong>QR Code Keamanan</strong> dan <strong>Kode Verifikasi Unik</strong> di bagian pojok bawah tanda tangan elektronik.
                </p>
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4 text-left text-xs">
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                        <strong class="font-bold text-slate-800 block mb-1">1. Pindai QR Code</strong>
                        <p class="text-slate-500">Gunakan kamera ponsel Anda untuk memindai QR code pada surat. Anda akan langsung diarahkan ke halaman verifikasi resmi.</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                        <strong class="font-bold text-slate-800 block mb-1">2. Ketik Manual</strong>
                        <p class="text-slate-500">Ketikkan 12 karakter kode verifikasi atau nomor surat lengkap pada kotak pencarian di atas lalu klik tombol "Cek Dokumen".</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
