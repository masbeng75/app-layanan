<div>
    <!-- Page Header Banner -->
    <div class="bg-gradient-to-r from-emerald-900 to-slate-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <nav class="flex text-xs font-semibold text-emerald-300 mb-3 space-x-2">
                    <a href="{{ route('portal.home') }}" class="hover:underline">Beranda</a>
                    <span>/</span>
                    <span class="text-white">Informasi Layanan</span>
                </nav>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">Katalog Informasi & SOP Pelayanan</h1>
                <p class="mt-3 text-emerald-100 text-sm sm:text-base leading-relaxed">
                    Temukan panduan resmi persyaratan berkas, alur prosedur, dasar hukum, dan template formulir pengajuan pelayanan sosial Dinas Sosial Kabupaten Blitar.
                </p>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6">
        <div class="bg-white rounded-2xl shadow-xl p-4 sm:p-6 border border-slate-200">
            <div class="flex flex-col md:flex-row gap-4 justify-between items-center">
                <!-- Search Input -->
                <div class="relative w-full md:w-96">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Cari layanan (cth: DTSEN, KIS, Bansos)..." 
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all text-slate-800 placeholder-slate-400"
                    >
                    @if($search)
                        <button wire:click="$set('search', '')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    @endif
                </div>

                <!-- Category Filters (Horizontal Pills) -->
                <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-1 md:pb-0">
                    <button 
                        type="button" 
                        wire:click="setCategory('')" 
                        class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all {{ empty($category) ? 'bg-emerald-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                    >
                        Semua Kategori
                    </button>
                    @foreach($categories as $cat)
                        <button 
                            type="button" 
                            wire:click="setCategory('{{ $cat }}')" 
                            class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all {{ $category === $cat ? 'bg-emerald-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                        >
                            {{ $cat }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Catalog Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Active Filter Indicator -->
        @if($search || $category)
            <div class="mb-6 flex items-center justify-between bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-2.5 text-xs text-emerald-900">
                <div class="flex items-center gap-2">
                    <span class="font-bold">Filter aktif:</span>
                    @if($category)
                        <span class="bg-emerald-200/70 text-emerald-800 px-2 py-0.5 rounded-md font-semibold">Kategori: {{ $category }}</span>
                    @endif
                    @if($search)
                        <span class="bg-emerald-200/70 text-emerald-800 px-2 py-0.5 rounded-md font-semibold">Kata kunci: "{{ $search }}"</span>
                    @endif
                </div>
                <button wire:click="resetFilters" class="font-bold text-emerald-700 hover:text-emerald-900 underline">
                    Reset Filter
                </button>
            </div>
        @endif

        @if($pages->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($pages as $page)
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl hover:border-emerald-300 transition-all flex flex-col justify-between overflow-hidden group">
                        <div class="p-6">
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="bg-emerald-100 text-emerald-800 text-[11px] font-bold px-2.5 py-1 rounded-lg">
                                    {{ $page->category ?? 'Layanan Sosial' }}
                                </span>
                                @if($page->serviceType && $page->serviceType->sla_days)
                                    <span class="inline-flex items-center text-xs font-semibold text-slate-500 bg-slate-100 px-2.5 py-0.5 rounded-md">
                                        <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $page->serviceType->sla_days }} Hari
                                    </span>
                                @endif
                            </div>

                            <h2 class="text-lg font-bold text-slate-900 group-hover:text-emerald-700 transition-colors">
                                <a href="{{ route('portal.detail', $page->slug) }}">
                                    {{ $page->title }}
                                </a>
                            </h2>

                            <p class="text-xs text-slate-600 mt-2.5 line-clamp-3 leading-relaxed">
                                {{ Str::limit(strip_tags($page->description), 130) }}
                            </p>

                            @if($page->serviceType && $page->serviceType->requirements->count() > 0)
                                <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] text-slate-500">
                                    <span class="font-semibold text-slate-700">{{ $page->serviceType->requirements->count() }} Persyaratan Berkas</span> 
                                    diperlukan saat pengajuan.
                                </div>
                            @endif
                        </div>

                        <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex items-center justify-between">
                            <a href="{{ route('portal.detail', $page->slug) }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 flex items-center gap-1 group-hover:underline">
                                <span>Lihat SOP & Detail</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                            <a href="{{ route('portal.request') }}" class="px-3.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm">
                                Ajukan Online
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $pages->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center max-w-lg mx-auto">
                <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Layanan Tidak Ditemukan</h3>
                <p class="text-xs text-slate-500 mt-2">Tidak ditemukan panduan layanan dengan kata kunci atau kategori yang Anda pilih.</p>
                <button wire:click="resetFilters" class="mt-5 px-4 py-2 rounded-xl bg-emerald-600 text-white text-xs font-bold shadow hover:bg-emerald-700">
                    Tampilkan Semua Layanan
                </button>
            </div>
        @endif
    </div>
</div>
