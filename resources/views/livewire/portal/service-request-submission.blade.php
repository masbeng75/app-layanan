<div>
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-emerald-900 to-slate-900 text-white py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left">
            <nav class="flex text-xs font-semibold text-emerald-300 mb-2 space-x-2 justify-center sm:justify-start">
                <a href="{{ route('portal.home') }}" class="hover:underline">Beranda</a>
                <span>/</span>
                <span class="text-white">Form Pengajuan Layanan Online</span>
            </nav>
            <h1 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight">Formulir Pendaftaran Pelayanan Sosial</h1>
            <p class="mt-2 text-emerald-100 text-xs sm:text-sm">
                Isi data permohonan dengan benar dan unggah dokumen pendukung untuk mendapatkan pelayanan dari Dinas Sosial Kabupaten Blitar.
            </p>
        </div>
    </div>

    <!-- Main Container -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 -mt-6">
        @if($submittedTicket)
            <!-- Success / Confirmation Receipt Screen -->
            <div class="bg-white rounded-3xl shadow-2xl border border-emerald-100 p-8 sm:p-12 text-center animate-fade-in">
                <div class="w-20 h-20 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-6 shadow-inner">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>

                <span class="text-xs uppercase font-extrabold tracking-widest text-emerald-600 block mb-1">Permohonan Berhasil Dikirim</span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900">Tanda Bukti Pendaftaran Online</h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-2 max-w-lg mx-auto leading-relaxed">
                    Permohonan Anda telah tersimpan dalam sistem resmi SAPA SOSIAL Dinas Sosial Kabupaten Blitar. Simpan nomor tiket di bawah ini untuk memantau status secara berkala.
                </p>

                <!-- Ticket Box -->
                <div class="mt-8 bg-slate-50 border-2 border-dashed border-emerald-400 rounded-2xl p-6 max-w-md mx-auto relative group">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Nomor Tiket Anda</span>
                    <div class="flex items-center justify-center gap-3">
                        <span class="text-2xl sm:text-3xl font-mono font-black text-emerald-700 tracking-wider">
                            {{ $submittedTicket }}
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-2">Gunakan nomor ini beserta 4 digit terakhir NIK Anda untuk melacak status tiket.</p>
                </div>

                <!-- Summary Details -->
                <div class="mt-8 bg-slate-50 rounded-2xl p-6 text-left border border-slate-200 text-xs sm:text-sm max-w-lg mx-auto space-y-3">
                    <div class="flex justify-between border-b border-slate-200/80 pb-2">
                        <span class="text-slate-500">Jenis Layanan</span>
                        <span class="font-bold text-slate-800">{{ $createdRequest?->serviceType?->name ?? 'Layanan Sosial' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-200/80 pb-2">
                        <span class="text-slate-500">Nama Pemohon</span>
                        <span class="font-bold text-slate-800">{{ $createdRequest?->applicant_name }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-200/80 pb-2">
                        <span class="text-slate-500">Wilayah Domisili</span>
                        <span class="font-bold text-slate-800">{{ $createdRequest?->village?->name }}, Kec. {{ $createdRequest?->village?->district?->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Estimasi SLA Pelayanan</span>
                        <span class="font-bold text-emerald-700">{{ $createdRequest?->serviceType?->sla_days ?? 3 }} Hari Kerja</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-10 flex flex-wrap justify-center gap-4">
                    <a href="{{ route('portal.track', ['ticket' => $submittedTicket]) }}" class="px-6 py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-lg shadow-emerald-600/30 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Lacak Status Tiket Ini
                    </a>
                    <button wire:click="resetForm" class="px-6 py-3.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm">
                        Ajukan Permohonan Baru
                    </button>
                </div>
            </div>

        @else
            <!-- Multi-step Wizard Card -->
            <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
                <!-- Step Navigation Bar -->
                <div class="bg-slate-50 border-b border-slate-200 p-4 sm:p-6">
                    <div class="grid grid-cols-4 gap-2 sm:gap-4 text-center">
                        <!-- Step 1 -->
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center font-bold text-xs sm:text-sm {{ $currentStep >= 1 ? 'bg-emerald-600 text-white shadow' : 'bg-slate-200 text-slate-500' }}">
                                1
                            </div>
                            <span class="text-[10px] sm:text-xs font-bold mt-1.5 {{ $currentStep >= 1 ? 'text-emerald-700' : 'text-slate-400' }}">
                                Layanan
                            </span>
                        </div>

                        <!-- Step 2 -->
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center font-bold text-xs sm:text-sm {{ $currentStep >= 2 ? 'bg-emerald-600 text-white shadow' : 'bg-slate-200 text-slate-500' }}">
                                2
                            </div>
                            <span class="text-[10px] sm:text-xs font-bold mt-1.5 {{ $currentStep >= 2 ? 'text-emerald-700' : 'text-slate-400' }}">
                                Data Warga
                            </span>
                        </div>

                        <!-- Step 3 -->
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center font-bold text-xs sm:text-sm {{ $currentStep >= 3 ? 'bg-emerald-600 text-white shadow' : 'bg-slate-200 text-slate-500' }}">
                                3
                            </div>
                            <span class="text-[10px] sm:text-xs font-bold mt-1.5 {{ $currentStep >= 3 ? 'text-emerald-700' : 'text-slate-400' }}">
                                Unggah Berkas
                            </span>
                        </div>

                        <!-- Step 4 -->
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center font-bold text-xs sm:text-sm {{ $currentStep >= 4 ? 'bg-emerald-600 text-white shadow' : 'bg-slate-200 text-slate-500' }}">
                                4
                            </div>
                            <span class="text-[10px] sm:text-xs font-bold mt-1.5 {{ $currentStep >= 4 ? 'text-emerald-700' : 'text-slate-400' }}">
                                Konfirmasi
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-6 sm:p-10">
                    <!-- ============================================== -->
                    <!-- STEP 1: PILIH LAYANAN & DETAIL KHUSUS           -->
                    <!-- ============================================== -->
                    @if($currentStep === 1)
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">Langkah 1: Pilih Layanan & Keperluan</h3>
                                <p class="text-xs text-slate-500 mt-1">Pilih jenis layanan sosial yang ingin diajukan beserta rincian subjek pemohon.</p>
                            </div>

                            <!-- Pilih Jenis Layanan -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                    Jenis Pelayanan Sosial <span class="text-rose-500">*</span>
                                </label>
                                <select wire:model.live="service_type_id" class="w-full px-4 py-3 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-slate-800 font-semibold bg-white">
                                    @foreach($serviceTypes as $st)
                                        <option value="{{ $st->id }}">{{ $st->name }} (SLA: {{ $st->sla_days }} Hari)</option>
                                    @endforeach
                                </select>
                                @error('service_type_id') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            @if($selectedType && ($selectedType->handler === 'dtsen' || $selectedType->code === 'DTSEN'))
                                <!-- DTSEN Specific Fields -->
                                <div class="bg-emerald-50/60 border border-emerald-200 rounded-2xl p-5 space-y-4">
                                    <div class="flex items-center gap-2 border-b border-emerald-200/80 pb-3">
                                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                                        <h4 class="font-bold text-sm text-emerald-900">Rincian Surat Keterangan DTSEN</h4>
                                    </div>

                                    @if($duplicateWarning)
                                        <div class="p-4 bg-amber-50 border border-amber-300 rounded-xl text-xs text-amber-900 flex items-start gap-3">
                                            <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                            <div>
                                                <strong class="font-bold block mb-0.5">Peringatan Duplikasi Surat:</strong>
                                                {{ $duplicateWarning }}
                                            </div>
                                        </div>
                                    @endif

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                                Peruntukan / Keperluan Surat <span class="text-rose-500">*</span>
                                            </label>
                                            <select wire:model.live="dtsen_purpose_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 text-slate-800 bg-white">
                                                <option value="">-- Pilih Peruntukan --</option>
                                                @foreach($dtsenPurposes as $purpose)
                                                    <option value="{{ $purpose->id }}">{{ $purpose->name }} (Maks. Desil: {{ $purpose->max_decile ?? '-' }})</option>
                                                @endforeach
                                            </select>
                                            @error('dtsen_purpose_id') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                                Hubungan dengan Pemohon <span class="text-rose-500">*</span>
                                            </label>
                                            <select wire:model="relationship_to_applicant" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 text-slate-800 bg-white">
                                                <option value="Diri Sendiri">Diri Sendiri</option>
                                                <option value="Anak Kandung">Anak Kandung</option>
                                                <option value="Orang Tua">Orang Tua</option>
                                                <option value="Suami / Istri">Suami / Istri</option>
                                                <option value="Famili Lain">Famili Lain</option>
                                            </select>
                                            @error('relationship_to_applicant') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                                NIK Orang yang Diterangkan <span class="text-rose-500">*</span>
                                            </label>
                                            <input type="text" maxlength="16" wire:model.live.debounce.500ms="subject_nik" placeholder="16 digit NIK sesuai KTP/KK" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 font-mono text-slate-800">
                                            @error('subject_nik') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                                Nama Lengkap yang Diterangkan <span class="text-rose-500">*</span>
                                            </label>
                                            <input type="text" wire:model="subject_name" placeholder="Nama lengkap sesuai KTP/KK" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 text-slate-800">
                                            @error('subject_name') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            @elseif($selectedType && ($selectedType->handler === 'pbi' || $selectedType->code === 'PBI'))
                                <!-- PBI Specific Fields -->
                                <div class="bg-blue-50/60 border border-blue-200 rounded-2xl p-5 space-y-4">
                                    <div class="flex items-center gap-2 border-b border-blue-200/80 pb-3">
                                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                                        <h4 class="font-bold text-sm text-blue-900">Rincian Kepesertaan KIS / PBI-JK</h4>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                                Alasan Reaktivasi Medis <span class="text-rose-500">*</span>
                                            </label>
                                            <select wire:model="pbi_reason" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 text-slate-800 bg-white">
                                                @foreach($pbiReasons as $reason)
                                                    <option value="{{ $reason->value }}">{{ $reason->label() }}</option>
                                                @endforeach
                                            </select>
                                            @error('pbi_reason') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                                Nomor Kartu BPJS / KIS <span class="text-rose-500">*</span>
                                            </label>
                                            <input type="text" wire:model="bpjs_card_number" placeholder="Nomor KIS 13 digit" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 font-mono text-slate-800">
                                            @error('bpjs_card_number') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                                NIK Peserta BPJS <span class="text-rose-500">*</span>
                                            </label>
                                            <input type="text" maxlength="16" wire:model="participant_nik" placeholder="16 digit NIK peserta" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 font-mono text-slate-800">
                                            @error('participant_nik') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                                Nama Lengkap Peserta <span class="text-rose-500">*</span>
                                            </label>
                                            <input type="text" wire:model="participant_name" placeholder="Nama lengkap peserta KIS" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 text-slate-800">
                                            @error('participant_name') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="sm:col-span-2">
                                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                                Nama Rumah Sakit / Puskesmas Rujukan
                                            </label>
                                            <input type="text" wire:model="health_facility_name" placeholder="Contoh: RSUD Ngudi Waluyo Wlingi" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 text-slate-800">
                                            @error('health_facility_name') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- ============================================== -->
                    <!-- STEP 2: DATA PEMOHON & ALAMAT DOMISILI        -->
                    <!-- ============================================== -->
                    @if($currentStep === 2)
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">Langkah 2: Data Pemohon & Alamat Domisili</h3>
                                <p class="text-xs text-slate-500 mt-1">Lengkapi data diri pemohon yang mengajukan permohonan ke Dinas Sosial.</p>
                            </div>

                            @if($selectedType && ($selectedType->handler === 'dtsen' || $selectedType->handler === 'pbi'))
                                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 flex items-center gap-3">
                                    <input type="checkbox" id="sameAsSubject" wire:model.live="is_same_as_subject" class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                                    <label for="sameAsSubject" class="text-xs font-bold text-slate-700 cursor-pointer select-none">
                                        Data Pemohon sama dengan Data Subjek / Peserta pada langkah sebelumnya
                                    </label>
                                </div>
                            @endif

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        NIK Pemohon (16 digit) <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" maxlength="16" wire:model="applicant_nik" placeholder="3505xxxxxxxxxxxx" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 font-mono text-slate-800">
                                    @error('applicant_nik') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        Nama Lengkap Pemohon <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" wire:model="applicant_name" placeholder="Nama lengkap sesuai KTP" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 text-slate-800">
                                    @error('applicant_name') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        Nomor Kartu Keluarga (KK) <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" maxlength="16" wire:model="family_card_number" placeholder="16 digit nomor KK" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 font-mono text-slate-800">
                                    @error('family_card_number') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        Nomor WhatsApp / Telepon Aktif <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" wire:model="phone" placeholder="Contoh: 081234567890" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 font-mono text-slate-800">
                                    @error('phone') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        Kecamatan Domisili <span class="text-rose-500">*</span>
                                    </label>
                                    <select wire:model.live="district_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 text-slate-800 bg-white">
                                        <option value="">-- Pilih Kecamatan --</option>
                                        @foreach($districts as $d)
                                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('district_id') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        Desa / Kelurahan <span class="text-rose-500">*</span>
                                    </label>
                                    <select wire:model="village_id" wire:loading.attr="disabled" wire:target="district_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 text-slate-800 bg-white" {{ !$district_id ? 'disabled' : '' }}>
                                        <option value="" wire:loading.remove wire:target="district_id">-- Pilih Desa/Kelurahan --</option>
                                        <option value="" wire:loading wire:target="district_id">Memuat data desa...</option>
                                        @foreach($villages as $v)
                                            <option value="{{ $v->id }}">{{ $v->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('village_id') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        Alamat Lengkap (Dusun / Jalan / RT / RW) <span class="text-rose-500">*</span>
                                    </label>
                                    <textarea wire:model="address" rows="3" placeholder="Tuliskan nama jalan, RT/RW, dan patokan rumah..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 text-slate-800"></textarea>
                                    @error('address') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- ============================================== -->
                    <!-- STEP 3: UNGGAH BERKAS PERSYARATAN              -->
                    <!-- ============================================== -->
                    @if($currentStep === 3)
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">Langkah 3: Unggah Berkas Persyaratan</h3>
                                <p class="text-xs text-slate-500 mt-1">Unggah scan atau foto jelas dokumen persyaratan resmi. Format yang didukung: PDF, JPG, PNG (maks. 5MB per file).</p>
                            </div>

                            @if($selectedType && $selectedType->requirements->count() > 0)
                                <div class="space-y-4">
                                    @foreach($selectedType->requirements as $req)
                                        <div class="p-4 sm:p-5 rounded-2xl border border-slate-200 bg-slate-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                            <div class="space-y-1">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-sm font-bold text-slate-900">{{ $req->name }}</span>
                                                    @if($req->is_mandatory)
                                                        <span class="text-[10px] bg-rose-100 text-rose-700 font-bold px-2 py-0.5 rounded">Wajib</span>
                                                    @else
                                                        <span class="text-[10px] bg-slate-200 text-slate-600 font-medium px-2 py-0.5 rounded">Opsional</span>
                                                    @endif
                                                </div>
                                                <p class="text-xs text-slate-500">Maks. 5MB &bull; PDF / JPG / PNG</p>
                                                @error("uploads.{$req->id}") <span class="text-xs text-rose-600 font-semibold block">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="flex-shrink-0">
                                                @if(isset($uploads[$req->id]) && $uploads[$req->id])
                                                    <div class="flex items-center gap-2 bg-emerald-100/80 text-emerald-800 px-3 py-2 rounded-xl text-xs font-bold">
                                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                        <span>Berkas Siap</span>
                                                    </div>
                                                @else
                                                    <input 
                                                        type="file" 
                                                        wire:model="uploads.{{ $req->id }}" 
                                                        accept=".pdf,.jpg,.jpeg,.png"
                                                        class="text-xs file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer"
                                                    >
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-6 bg-slate-50 rounded-2xl border border-slate-200 text-center text-xs text-slate-500">
                                    Layanan ini tidak memerlukan unggahan berkas khusus saat pendaftaran. Petugas akan menghubungi Anda jika ada data tambahan yang diperlukan.
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- ============================================== -->
                    <!-- STEP 4: REVIEW & KONFIRMASI                    -->
                    <!-- ============================================== -->
                    @if($currentStep === 4)
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">Langkah 4: Konfirmasi & Kirim Permohonan</h3>
                                <p class="text-xs text-slate-500 mt-1">Periksa kembali ringkasan permohonan Anda sebelum mengirimkan berkas ke sistem.</p>
                            </div>

                            <!-- Review Box -->
                            <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200 space-y-3 text-xs sm:text-sm">
                                <div class="flex justify-between border-b border-slate-200 pb-2">
                                    <span class="text-slate-500">Layanan Dipilih</span>
                                    <span class="font-bold text-slate-900">{{ $selectedType?->name }}</span>
                                </div>
                                <div class="flex justify-between border-b border-slate-200 pb-2">
                                    <span class="text-slate-500">Nama Pemohon</span>
                                    <span class="font-bold text-slate-900">{{ $applicant_name }}</span>
                                </div>
                                <div class="flex justify-between border-b border-slate-200 pb-2">
                                    <span class="text-slate-500">NIK Pemohon</span>
                                    <span class="font-mono font-bold text-slate-900">{{ $applicant_nik }}</span>
                                </div>
                                <div class="flex justify-between border-b border-slate-200 pb-2">
                                    <span class="text-slate-500">No. WhatsApp / HP</span>
                                    <span class="font-mono font-bold text-slate-900">{{ $phone }}</span>
                                </div>
                                <div class="flex justify-between border-b border-slate-200 pb-2">
                                    <span class="text-slate-500">Domisili</span>
                                    <span class="font-bold text-slate-900">{{ $villages->firstWhere('id', $village_id)?->name ?? 'Desa/Kel. ID: '.$village_id }}, Kec. {{ $districts->firstWhere('id', $district_id)?->name ?? 'Kec. ID: '.$district_id }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Total Berkas Diunggah</span>
                                    <span class="font-bold text-emerald-700">{{ count(array_filter($uploads)) }} Berkas</span>
                                </div>
                            </div>

                            <!-- Agreement Disclaimer -->
                            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl">
                                <label class="flex items-start gap-3 cursor-pointer">
                                    <input type="checkbox" wire:model="agreement" class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500 mt-0.5">
                                    <span class="text-xs text-emerald-950 leading-relaxed">
                                        Saya menyatakan dengan sebenarnya bahwa seluruh data dan berkas yang saya lampirkan adalah benar, sah, dan dapat dipertanggungjawabkan menurut hukum. Apabila di kemudian hari ditemukan pemalsuan data, saya bersedia menerima sanksi sesuai peraturan perundang-undangan.
                                    </span>
                                </label>
                                @error('agreement') <span class="text-xs text-rose-600 mt-2 block font-semibold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endif

                    <!-- Wizard Buttons -->
                    <div class="mt-10 pt-6 border-t border-slate-200 flex items-center justify-between">
                        @if($currentStep > 1)
                            <button 
                                type="button" 
                                wire:click="previousStep" 
                                class="px-5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 text-xs sm:text-sm font-bold flex items-center gap-1.5"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                Sebelumnya
                            </button>
                        @else
                            <div></div>
                        @endif

                        @if($currentStep < 4)
                            <button 
                                type="button" 
                                wire:click="nextStep" 
                                class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-bold shadow-md shadow-emerald-600/20 flex items-center gap-1.5"
                            >
                                Lanjutkan
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        @else
                            <button 
                                type="button" 
                                wire:click="submit" 
                                wire:loading.attr="disabled"
                                class="px-7 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-extrabold shadow-lg shadow-emerald-600/30 flex items-center gap-2 disabled:opacity-50"
                            >
                                <span wire:loading.remove>Kirim Permohonan Sekarang</span>
                                <span wire:loading class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                    Memproses Pendaftaran...
                                </span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
