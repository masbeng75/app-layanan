<div>
    <!-- Detail Header -->
    <div class="bg-gradient-to-r from-emerald-900 to-slate-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex text-xs font-semibold text-emerald-300 mb-3 space-x-2">
                <a href="{{ route('portal.home') }}" class="hover:underline">Beranda</a>
                <span>/</span>
                <a href="{{ route('portal.catalog') }}" class="hover:underline">Informasi Layanan</a>
                <span>/</span>
                <span class="text-white truncate max-w-xs">{{ $page->title }}</span>
            </nav>

            <div class="flex flex-wrap items-center gap-2 mb-3">
                <span class="bg-emerald-500/20 border border-emerald-400/30 text-emerald-200 text-xs font-bold px-3 py-1 rounded-full">
                    {{ $page->category ?? 'Layanan Sosial' }}
                </span>
                @if($page->serviceType && $page->serviceType->sla_days)
                    <span class="bg-white/10 border border-white/20 text-white text-xs font-semibold px-3 py-1 rounded-full flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Estimasi SLA: {{ $page->serviceType->sla_days }} Hari Kerja
                    </span>
                @endif
                <span class="text-xs text-emerald-200/80">
                    Diperbarui: {{ $page->updated_at ? $page->updated_at->translatedFormat('d F Y') : '-' }}
                </span>
            </div>

            <h1 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight max-w-4xl">
                {{ $page->title }}
            </h1>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left / Main Body (2 Columns) -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Deskripsi Layanan -->
                <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                    <h2 class="text-xl font-bold text-slate-900 border-l-4 border-emerald-600 pl-3 mb-4">
                        Deskripsi & Ikhtisar Layanan
                    </h2>
                    <div class="prose prose-emerald text-sm text-slate-700 leading-relaxed max-w-none">
                        {!! nl2br(e($page->description)) !!}
                    </div>
                </div>

                <!-- Persyaratan Berkas -->
                <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-slate-900 border-l-4 border-emerald-600 pl-3">
                            Persyaratan Berkas & Dokumen
                        </h2>
                        <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md">Wajib Lengkap</span>
                    </div>

                    @if($page->serviceType && $page->serviceType->requirements->count() > 0)
                        <div class="space-y-3 mt-4">
                            @foreach($page->serviceType->requirements as $index => $req)
                                <div class="flex items-start gap-3 p-3.5 rounded-xl border border-slate-100 bg-slate-50/70">
                                    <div class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-bold text-slate-800">{{ $req->name }}</span>
                                            @if($req->is_mandatory)
                                                <span class="text-[10px] bg-rose-100 text-rose-700 font-bold px-1.5 py-0.5 rounded">Wajib</span>
                                            @else
                                                <span class="text-[10px] bg-slate-200 text-slate-600 font-medium px-1.5 py-0.5 rounded">Opsional</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-slate-500 mt-0.5">Format file yang diterima: <code class="font-mono text-emerald-700 font-semibold">{{ strtoupper($req->allowed_mimes ?? 'PDF, JPG, PNG') }}</code></p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @elseif($page->requirements)
                        <div class="prose prose-emerald text-sm text-slate-700 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100">
                            {!! nl2br(e($page->requirements)) !!}
                        </div>
                    @else
                        <p class="text-xs text-slate-500 italic">Persyaratan berkas umum meliputi KTP, Kartu Keluarga, dan surat keterangan domisili.</p>
                    @endif
                </div>

                <!-- Prosedur & Alur Layanan -->
                <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                    <h2 class="text-xl font-bold text-slate-900 border-l-4 border-emerald-600 pl-3 mb-4">
                        Alur & Prosedur Pelayanan
                    </h2>

                    @if($page->procedure)
                        <div class="prose prose-emerald text-sm text-slate-700 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100">
                            {!! nl2br(e($page->procedure)) !!}
                        </div>
                    @else
                        <div class="space-y-4">
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full bg-emerald-600 text-white font-bold flex items-center justify-center text-sm shadow">1</div>
                                    <div class="w-0.5 h-12 bg-emerald-200 my-1"></div>
                                </div>
                                <div class="pt-1">
                                    <h4 class="font-bold text-slate-900 text-sm">Pengajuan Berkas Online</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">Pemohon mengisi formulir online dan mengunggah pindaian (scan) berkas persyaratan lengkap.</p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full bg-emerald-600 text-white font-bold flex items-center justify-center text-sm shadow">2</div>
                                    <div class="w-0.5 h-12 bg-emerald-200 my-1"></div>
                                </div>
                                <div class="pt-1">
                                    <h4 class="font-bold text-slate-900 text-sm">Verifikasi Administrasi & Data</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">Petugas Dinsos memvalidasi kelengkapan dokumen dan mengecek basis data DTSEN/DTKS.</p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full bg-emerald-600 text-white font-bold flex items-center justify-center text-sm shadow">3</div>
                                    <div class="w-0.5 h-12 bg-emerald-200 my-1"></div>
                                </div>
                                <div class="pt-1">
                                    <h4 class="font-bold text-slate-900 text-sm">Penerbitan Surat & Tanda Tangan Elektronik</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">Dokumen resmi disahkan secara elektronik dengan QR Code verifikasi keamanan.</p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full bg-emerald-600 text-white font-bold flex items-center justify-center text-sm shadow">4</div>
                                </div>
                                <div class="pt-1">
                                    <h4 class="font-bold text-slate-900 text-sm">Penyampaian Hasil ke Pemohon</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">Pemohon menerima notifikasi dan dapat mengunduh dokumen secara langsung melalui portal.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Downloadable Forms -->
                @if($page->downloadableForms->count() > 0)
                    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                        <h2 class="text-xl font-bold text-slate-900 border-l-4 border-emerald-600 pl-3 mb-4">
                            Unduh Formulir & Template Dokumen
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($page->downloadableForms as $form)
                                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 hover:bg-emerald-50/50 hover:border-emerald-300 transition-colors flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <div>
                                            <h4 class="text-xs sm:text-sm font-bold text-slate-800">{{ $form->title }}</h4>
                                            <span class="text-[11px] text-slate-500">{{ strtoupper($form->file_extension ?? 'PDF') }} &bull; {{ $form->file_size_formatted ?? 'Unduhan' }}</span>
                                        </div>
                                    </div>
                                    <a href="{{ asset('storage/' . $form->file_path) }}" target="_blank" class="p-2 rounded-lg bg-white border border-slate-200 text-emerald-700 hover:bg-emerald-600 hover:text-white transition-colors" title="Unduh File">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- FAQ Accordion -->
                @if($page->faqs->count() > 0)
                    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-sm" x-data="{ activeFaq: null }">
                        <h2 class="text-xl font-bold text-slate-900 border-l-4 border-emerald-600 pl-3 mb-4">
                            Pertanyaan Umum (FAQ)
                        </h2>
                        <div class="space-y-3">
                            @foreach($page->faqs as $index => $faq)
                                <div class="border border-slate-200 rounded-xl overflow-hidden transition-all">
                                    <button 
                                        @click="activeFaq = (activeFaq === {{ $index }} ? null : {{ $index }})" 
                                        class="w-full px-5 py-4 text-left flex items-center justify-between bg-slate-50 hover:bg-slate-100 transition-colors"
                                    >
                                        <span class="text-sm font-bold text-slate-800">{{ $faq->question }}</span>
                                        <svg 
                                            class="w-4 h-4 text-slate-500 transform transition-transform" 
                                            :class="activeFaq === {{ $index }} ? 'rotate-180 text-emerald-600' : ''"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <div x-show="activeFaq === {{ $index }}" x-cloak class="p-5 bg-white text-xs sm:text-sm text-slate-600 leading-relaxed border-t border-slate-200">
                                        {{ $faq->answer }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Sidebar (1 Column) -->
            <div class="space-y-6">
                <!-- Apply Action Box (Sticky) -->
                <div class="bg-gradient-to-br from-emerald-800 to-teal-900 text-white rounded-2xl p-6 shadow-xl sticky top-28">
                    <span class="text-xs uppercase font-bold tracking-wider text-emerald-300 block mb-1">Pengajuan Mandiri</span>
                    <h3 class="text-xl font-extrabold text-white">Siap Mengajukan Layanan Ini?</h3>
                    <p class="text-xs text-emerald-100/90 mt-2 leading-relaxed">
                        Siapkan foto KTP, Kartu Keluarga, dan dokumen pendukung lainnya sebelum memulai pendaftaran online.
                    </p>

                    <div class="mt-6">
                        <a 
                            href="{{ route('portal.request', ['service_type_id' => $page->service_type_id]) }}" 
                            class="w-full inline-flex items-center justify-center px-5 py-3 rounded-xl bg-emerald-400 hover:bg-emerald-300 text-slate-950 font-bold text-sm shadow-lg shadow-emerald-950/20 transform hover:-translate-y-0.5 transition-all"
                        >
                            <span>Mulai Isi Formulir</span>
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>

                    <div class="mt-4 pt-4 border-t border-emerald-700/50 flex items-center justify-between text-[11px] text-emerald-200">
                        <span>Biaya: <strong class="text-white">GRATIS (Rp 0)</strong></span>
                        <span>Resmi Dinsos Kab. Blitar</span>
                    </div>
                </div>

                <!-- Info Kontak & Lokasi Pelayanan -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
                    <h4 class="font-bold text-sm text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2">
                        Lokasi & Waktu Layanan
                    </h4>
                    <div class="text-xs text-slate-600 space-y-3">
                        <div>
                            <span class="font-bold text-slate-800 block">Jam Operasional:</span>
                            <p>{{ $page->service_hours ?? 'Senin - Jumat, Pukul 07.30 - 15.30 WIB' }}</p>
                        </div>
                        <div>
                            <span class="font-bold text-slate-800 block">Lokasi Pelayanan Tatap Muka:</span>
                            <p>{{ $page->location ?? 'Kantor Dinas Sosial Kab. Blitar, Jl. Kota Baru No. 10 Kanigoro' }}</p>
                        </div>
                        <div>
                            <span class="font-bold text-slate-800 block">Kontak Bantuan Teknis:</span>
                            <p>{{ $page->contact ?? '(0342) 801123 / WhatsApp: 0812-3456-7890' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Jaminan Bebas Pungli -->
                <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 text-amber-900 text-xs">
                    <div class="flex items-center gap-2 font-bold mb-1 text-amber-800">
                        <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd"/></svg>
                        <span>Maklumat Pelayanan</span>
                    </div>
                    <p class="leading-relaxed">
                        Dinas Sosial Kabupaten Blitar berkomitmen menyelenggarakan pelayanan publik tanpa pungutan liar dan gratifikasi. Jika menemukan indikasi pungli, silakan laporkan melalui menu Pengaduan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
