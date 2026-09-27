<div>
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-blue-950 via-slate-900 to-emerald-950 text-white py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <nav class="flex text-xs font-semibold text-blue-300 mb-2 space-x-2 justify-center">
                <a href="{{ route('portal.home') }}" class="hover:underline">Beranda</a>
                <span>/</span>
                <span class="text-white">Lacak Status Pelayanan</span>
            </nav>
            <h1 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight">Cek & Lacak Status Tiket</h1>
            <p class="mt-2 text-blue-100 text-xs sm:text-sm max-w-xl mx-auto">
                Ketahui perkembangan permohonan atau pengaduan sosial Anda secara transparan dan real-time.
            </p>
        </div>
    </div>

    <!-- Search Form Card -->
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6">
        <div class="bg-white rounded-3xl shadow-xl border border-slate-200 p-6 sm:p-8">
            <form wire:submit="trackTicket" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nomor Tiket <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="text" 
                                wire:model="ticketNumber" 
                                placeholder="Contoh: SR-202609-00001 atau ADU-202609-00001" 
                                class="w-full px-4 py-3 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 font-mono font-bold text-slate-800 placeholder-slate-400 uppercase"
                            >
                        </div>
                        @error('ticketNumber') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            4 Digit Terakhir NIK / HP
                        </label>
                        <input 
                            type="text" 
                            maxlength="4" 
                            wire:model="securityDigits" 
                            placeholder="Contoh: 1234" 
                            class="w-full px-4 py-3 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 font-mono text-center text-slate-800"
                        >
                        @error('securityDigits') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <span class="text-[11px] text-slate-500 hidden sm:inline">
                        Verifikasi keamanan privasi data pemohon.
                    </span>
                    <button 
                        type="submit" 
                        wire:loading.attr="disabled"
                        class="w-full sm:w-auto px-8 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-sm shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2 transition-all disabled:opacity-50"
                    >
                        <span wire:loading.remove>Lacak Tiket</span>
                        <span wire:loading class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            Mencari...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tracking Results Section -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @if($errorMessage)
            <!-- Error Alert -->
            <div class="bg-rose-50 border border-rose-200 rounded-2xl p-6 text-center text-rose-900 animate-fade-in max-w-lg mx-auto">
                <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h3 class="font-bold text-base">Tiket Tidak Dapat Ditampilkan</h3>
                <p class="text-xs text-rose-700 mt-1 leading-relaxed">{{ $errorMessage }}</p>
            </div>

        @elseif($trackingType === 'service_request' && $serviceRequest)
            <!-- SERVICE REQUEST TRACKING VIEW -->
            <div class="space-y-6 animate-fade-in">
                <!-- Overview Card -->
                <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-800 to-teal-900 p-6 sm:p-8 text-white flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                        <div>
                            <span class="text-xs uppercase font-extrabold tracking-widest text-emerald-300 block mb-1">Status Permohonan Layanan</span>
                            <h2 class="text-xl sm:text-2xl font-black font-mono tracking-wider">{{ $serviceRequest->request_number }}</h2>
                            <p class="text-xs text-emerald-100 mt-1">{{ $serviceRequest->serviceType?->name }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="inline-block px-4 py-2 rounded-xl text-xs font-extrabold uppercase tracking-wider bg-white text-emerald-800 shadow">
                                {{ $serviceRequest->status?->label() ?? $serviceRequest->status }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6 sm:p-8">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="text-slate-500 block">Nama Pemohon</span>
                                <span class="font-bold text-slate-800 text-sm mt-0.5 block">
                                    {{ Str::mask($serviceRequest->applicant_name, '*', 3, -2) }}
                                </span>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="text-slate-500 block">Tanggal Diajukan</span>
                                <span class="font-bold text-slate-800 text-sm mt-0.5 block">
                                    {{ $serviceRequest->submitted_at ? $serviceRequest->submitted_at->translatedFormat('d M Y H:i') : '-' }} WIB
                                </span>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="text-slate-500 block">Wilayah Domisili</span>
                                <span class="font-bold text-slate-800 text-sm mt-0.5 block">
                                    {{ $serviceRequest->village?->name }}, Kec. {{ $serviceRequest->village?->district?->name }}
                                </span>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="text-slate-500 block">SLA Penyelesaian</span>
                                <span class="font-bold text-emerald-700 text-sm mt-0.5 block">
                                    {{ $serviceRequest->serviceType?->sla_days ?? 3 }} Hari Kerja
                                </span>
                            </div>
                        </div>

                        <!-- Progress Steps Pipeline -->
                        <div class="mt-8 pt-8 border-t border-slate-200">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-6">Tahapan Proses Permohonan</h4>

                            @php
                                $statusVal = $serviceRequest->status?->value ?? (string)$serviceRequest->status;
                                $isCompleted = in_array($statusVal, ['issued', 'completed', 'reactivated']);
                                $isRejected = in_array($statusVal, ['rejected', 'ministry_rejected']);
                                $step = match($statusVal) {
                                    'submitted' => 1,
                                    'document_check', 'data_verification', 'eligibility_verification', 'verification' => 2,
                                    'assessment', 'awaiting_approval', 'in_process', 'proposed_to_ministry', 'recommendation_issued' => 3,
                                    'issued', 'completed', 'reactivated', 'ministry_approved' => 4,
                                    default => 1,
                                };
                            @endphp

                            <div class="grid grid-cols-4 gap-2 text-center relative">
                                <div class="space-y-2">
                                    <div class="w-8 h-8 mx-auto rounded-full flex items-center justify-center font-bold text-xs {{ $step >= 1 ? 'bg-emerald-600 text-white ring-4 ring-emerald-100' : 'bg-slate-200 text-slate-500' }}">
                                        &check;
                                    </div>
                                    <span class="text-[11px] font-bold block text-slate-800">1. Diajukan</span>
                                </div>

                                <div class="space-y-2">
                                    <div class="w-8 h-8 mx-auto rounded-full flex items-center justify-center font-bold text-xs {{ $step >= 2 ? 'bg-emerald-600 text-white ring-4 ring-emerald-100' : 'bg-slate-200 text-slate-500' }}">
                                        {{ $step > 2 ? '✓' : '2' }}
                                    </div>
                                    <span class="text-[11px] font-bold block text-slate-800">2. Verifikasi Berkas</span>
                                </div>

                                <div class="space-y-2">
                                    <div class="w-8 h-8 mx-auto rounded-full flex items-center justify-center font-bold text-xs {{ $step >= 3 ? 'bg-emerald-600 text-white ring-4 ring-emerald-100' : 'bg-slate-200 text-slate-500' }}">
                                        {{ $step > 3 ? '✓' : '3' }}
                                    </div>
                                    <span class="text-[11px] font-bold block text-slate-800">3. Telaah & TTD</span>
                                </div>

                                <div class="space-y-2">
                                    <div class="w-8 h-8 mx-auto rounded-full flex items-center justify-center font-bold text-xs {{ $isCompleted ? 'bg-emerald-600 text-white ring-4 ring-emerald-100' : ($isRejected ? 'bg-rose-600 text-white' : 'bg-slate-200 text-slate-500') }}">
                                        {{ $isCompleted ? '✓' : ($isRejected ? '✕' : '4') }}
                                    </div>
                                    <span class="text-[11px] font-bold block text-slate-800">4. Dokumen Terbit</span>
                                </div>
                            </div>
                        </div>

                        <!-- Issued Certificate Output (If Available) -->
                        @if($serviceRequest->dtsenCertificate && $serviceRequest->dtsenCertificate->issued_at)
                            <div class="mt-8 p-6 bg-emerald-50 border border-emerald-300 rounded-2xl flex flex-col sm:flex-row justify-between items-center gap-4">
                                <div class="space-y-1 text-center sm:text-left">
                                    <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider block">Dokumen Resmi Telah Diterbitkan</span>
                                    <h4 class="text-base font-extrabold text-emerald-950">Surat Keterangan DTSEN: {{ $serviceRequest->dtsenCertificate->certificate_number }}</h4>
                                    <p class="text-xs text-emerald-800">Berlaku sampai: {{ $serviceRequest->dtsenCertificate->valid_until ? $serviceRequest->dtsenCertificate->valid_until->translatedFormat('d F Y') : 'Sesuai Ketentuan' }}</p>
                                </div>
                                <a href="{{ route('verification.show', $serviceRequest->dtsenCertificate->verification_code) }}" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow flex items-center gap-2 whitespace-nowrap">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Verifikasi & Lihat Dokumen
                                </a>
                            </div>
                        @endif

                        @if($serviceRequest->pbiReactivation && $serviceRequest->pbiReactivation->recommendation_issued_at)
                            <div class="mt-8 p-6 bg-blue-50 border border-blue-300 rounded-2xl flex flex-col sm:flex-row justify-between items-center gap-4">
                                <div class="space-y-1 text-center sm:text-left">
                                    <span class="text-xs font-bold text-blue-800 uppercase tracking-wider block">Surat Rekomendasi Resmi Telah Diterbitkan</span>
                                    <h4 class="text-base font-extrabold text-blue-950">No. Rekomendasi: {{ $serviceRequest->pbiReactivation->recommendation_number }}</h4>
                                    <p class="text-xs text-blue-800">Diterbitkan pada: {{ $serviceRequest->pbiReactivation->recommendation_issued_at->translatedFormat('d F Y') }}</p>
                                </div>
                                <a href="{{ route('verification.show', $serviceRequest->pbiReactivation->recommendation_number) }}" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow flex items-center gap-2 whitespace-nowrap">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Verifikasi & Lihat Dokumen
                                </a>
                            </div>
                        @endif

                        <!-- Status History Log -->
                        @if($serviceRequest->statusHistories->count() > 0)
                            <div class="mt-8 pt-8 border-t border-slate-200">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Catatan Perkembangan Terakhir</h4>
                                <div class="space-y-3">
                                    @foreach($serviceRequest->statusHistories->take(5) as $history)
                                        <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50 flex items-start justify-between text-xs">
                                            <div>
                                                <span class="font-bold text-slate-800">{{ $history->to_status ?? $history->status }}</span>
                                                <p class="text-slate-600 mt-0.5">{{ $history->notes ?? 'Pembaruan status oleh sistem.' }}</p>
                                            </div>
                                            <span class="text-slate-400 whitespace-nowrap ml-4">
                                                {{ $history->created_at ? $history->created_at->translatedFormat('d M Y H:i') : '-' }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        @elseif($trackingType === 'complaint' && $complaint)
            <!-- COMPLAINT TRACKING VIEW -->
            <div class="space-y-6 animate-fade-in">
                <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-rose-900 to-slate-900 p-6 sm:p-8 text-white flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                        <div>
                            <span class="text-xs uppercase font-extrabold tracking-widest text-rose-300 block mb-1">Status Pengaduan Sosial</span>
                            <h2 class="text-xl sm:text-2xl font-black font-mono tracking-wider">{{ $complaint->complaint_number }}</h2>
                            <p class="text-xs text-rose-100 mt-1">{{ $complaint->category?->name }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="inline-block px-4 py-2 rounded-xl text-xs font-extrabold uppercase tracking-wider bg-white text-rose-900 shadow">
                                {{ $complaint->status?->label() ?? $complaint->status }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6 sm:p-8">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="text-slate-500 block">Pelapor</span>
                                <span class="font-bold text-slate-800 text-sm mt-0.5 block">{{ $complaint->reporter_name }}</span>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="text-slate-500 block">Waktu Dilaporkan</span>
                                <span class="font-bold text-slate-800 text-sm mt-0.5 block">
                                    {{ $complaint->reported_at ? $complaint->reported_at->translatedFormat('d M Y H:i') : '-' }} WIB
                                </span>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="text-slate-500 block">Lokasi Kasus</span>
                                <span class="font-bold text-slate-800 text-sm mt-0.5 block">
                                    {{ $complaint->village?->name }}, Kec. {{ $complaint->village?->district?->name }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-6 p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-700">
                            <strong class="font-bold text-slate-800 block mb-1">Uraian Laporan:</strong>
                            <p class="leading-relaxed">{{ $complaint->description }}</p>
                        </div>

                        @if($complaint->action_taken)
                            <div class="mt-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-950">
                                <strong class="font-bold text-emerald-800 block mb-1">Tindakan Penanganan Petugas:</strong>
                                <p class="leading-relaxed">{{ $complaint->action_taken }}</p>
                            </div>
                        @endif

                        <!-- Status History -->
                        @if($complaint->statusHistories->count() > 0)
                            <div class="mt-8 pt-8 border-t border-slate-200">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Kronologi Penanganan</h4>
                                <div class="space-y-3">
                                    @foreach($complaint->statusHistories->take(5) as $history)
                                        <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50 flex items-start justify-between text-xs">
                                            <div>
                                                <span class="font-bold text-slate-800">{{ $history->to_status ?? $history->status }}</span>
                                                <p class="text-slate-600 mt-0.5">{{ $history->notes ?? 'Pembaruan status pengaduan.' }}</p>
                                            </div>
                                            <span class="text-slate-400 whitespace-nowrap ml-4">
                                                {{ $history->created_at ? $history->created_at->translatedFormat('d M Y H:i') : '-' }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @elseif($hasSearched)
            <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center max-w-md mx-auto">
                <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <h3 class="font-bold text-base text-slate-800">Data Tidak Ditemukan</h3>
                <p class="text-xs text-slate-500 mt-1">Periksa kembali nomor tiket yang Anda masukkan dan pastikan tidak ada kesalahan ketik.</p>
            </div>
        @endif
    </div>
</div>
