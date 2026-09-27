<div>
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-rose-900 to-slate-900 text-white py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex text-xs font-semibold text-rose-300 mb-2 space-x-2">
                <a href="{{ route('portal.home') }}" class="hover:underline">Beranda</a>
                <span>/</span>
                <span class="text-white">Layanan Pengaduan Masalah Sosial</span>
            </nav>
            <h1 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight">Kanal Pengaduan Masalah Kesejahteraan Sosial</h1>
            <p class="mt-2 text-rose-100 text-xs sm:text-sm">
                Laporkan kasus kedaruratan sosial, lansia terlantar, penanganan ODGJ, atau dugaan penyimpangan bantuan sosial di wilayah Kabupaten Blitar.
            </p>
        </div>
    </div>

    <!-- Main Container -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 -mt-6">
        @if($submittedTicket)
            <!-- Success Screen -->
            <div class="bg-white rounded-3xl shadow-2xl border border-rose-100 p-8 sm:p-12 text-center animate-fade-in">
                <div class="w-20 h-20 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-6 shadow-inner">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>

                <span class="text-xs uppercase font-extrabold tracking-widest text-rose-600 block mb-1">Laporan Diterima</span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900">Pengaduan Berhasil Terkirim</h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-2 max-w-lg mx-auto leading-relaxed">
                    Terima kasih atas kepedulian Anda. Laporan Anda telah tercatat dan tim respon cepat Dinas Sosial Kabupaten Blitar akan segera memverifikasi laporan ini.
                </p>

                <!-- Ticket Box -->
                <div class="mt-8 bg-slate-50 border-2 border-dashed border-rose-400 rounded-2xl p-6 max-w-md mx-auto">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Nomor Tiket Pengaduan</span>
                    <span class="text-2xl sm:text-3xl font-mono font-black text-rose-700 tracking-wider">
                        {{ $submittedTicket }}
                    </span>
                    <p class="text-[11px] text-slate-500 mt-2">Simpan nomor tiket ini untuk memantau tindakan penanganan petugas.</p>
                </div>

                <!-- Summary Details -->
                <div class="mt-8 bg-slate-50 rounded-2xl p-6 text-left border border-slate-200 text-xs sm:text-sm max-w-lg mx-auto space-y-3">
                    <div class="flex justify-between border-b border-slate-200/80 pb-2">
                        <span class="text-slate-500">Kategori Masalah</span>
                        <span class="font-bold text-slate-800">{{ $createdComplaint?->category?->name }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-200/80 pb-2">
                        <span class="text-slate-500">Lokasi Kejadian</span>
                        <span class="font-bold text-slate-800">{{ $createdComplaint?->village?->name }}, Kec. {{ $createdComplaint?->village?->district?->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Pelapor</span>
                        <span class="font-bold text-slate-800">{{ $createdComplaint?->reporter_name }}</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-10 flex flex-wrap justify-center gap-4">
                    <a href="{{ route('portal.track', ['ticket' => $submittedTicket]) }}" class="px-6 py-3.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm shadow-lg shadow-rose-600/30 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Lacak Tindak Lanjut
                    </a>
                    <button wire:click="resetForm" class="px-6 py-3.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm">
                        Kirim Laporan Lain
                    </button>
                </div>
            </div>

        @else
            <!-- Form Card -->
            <div class="bg-white rounded-3xl shadow-xl border border-slate-200 p-6 sm:p-10">
                <form wire:submit="submit" class="space-y-6">
                    <!-- Kategori Pengaduan -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Kategori Masalah Sosial <span class="text-rose-500">*</span>
                        </label>
                        <select wire:model="complaint_category_id" class="w-full px-4 py-3 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500 text-slate-800 font-semibold bg-white">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('complaint_category_id') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Privacy / Anonymous Toggle -->
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 sm:p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-sm font-bold text-slate-900 block">Kirim Sebagai Anonim (Rahasiakan Identitas)</span>
                                <p class="text-xs text-slate-500 mt-0.5">Identitas nama dan nomor telepon Anda tidak akan dipublikasikan atau diperlihatkan ke pihak luar.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer flex-shrink-0 ml-4">
                                <input type="checkbox" wire:model.live="is_anonymous" class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-600"></div>
                            </label>
                        </div>

                        @if(! $is_anonymous)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4 pt-4 border-t border-slate-200">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        Nama Lengkap Pelapor <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" wire:model="reporter_name" placeholder="Nama Anda" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-rose-500 text-slate-800">
                                    @error('reporter_name') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        Nomor Telepon / WhatsApp <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" wire:model="reporter_phone" placeholder="08xxxxxxxxxx" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-rose-500 font-mono text-slate-800">
                                    @error('reporter_phone') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        @else
                            <div class="mt-3 text-xs font-semibold text-rose-700 bg-rose-50 p-2.5 rounded-lg border border-rose-200 flex items-center gap-2">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd"/></svg>
                                <span>Laporan akan dikirim dengan status identitas terlindungi. Petugas tidak dapat menghubungi Anda untuk klarifikasi.</span>
                            </div>
                        @endif
                    </div>

                    <!-- Lokasi Kejadian (Cascading) -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-bold text-slate-900 border-l-4 border-rose-600 pl-3">
                            Lokasi Kejadian Masalah Sosial
                        </h4>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Kecamatan <span class="text-rose-500">*</span>
                                </label>
                                <select wire:model.live="district_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-rose-500 text-slate-800 bg-white">
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
                                <select wire:model="village_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-rose-500 text-slate-800 bg-white" {{ !$district_id ? 'disabled' : '' }}>
                                    <option value="">-- Pilih Desa/Kelurahan --</option>
                                    @foreach($villages as $v)
                                        <option value="{{ $v->id }}">{{ $v->name }}</option>
                                    @endforeach
                                </select>
                                @error('village_id') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Alamat Lengkap / Patokan Lokasi Spesifik <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" wire:model="location_detail" placeholder="Contoh: Depan Pasar Kanigoro, RT 02 RW 01 dekat pos kamling" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-rose-500 text-slate-800">
                                @error('location_detail') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Uraian Masalah / Deskripsi -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Uraian Kejadian / Kronologi Masalah Sosial <span class="text-rose-500">*</span>
                        </label>
                        <textarea wire:model="description" rows="4" placeholder="Ceritakan kondisi yang terjadi, siapa yang menjadi korban/pemerlu bantuan, kapan kejadian dimulai, dan bantuan darurat apa yang paling dibutuhkan..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-rose-500 text-slate-800 leading-relaxed"></textarea>
                        @error('description') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Unggah Bukti / Foto Lampiran -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Foto Bukti / Dokumen Pendukung (Opsional, Maks. 3 File)
                        </label>
                        <p class="text-xs text-slate-500 mb-2">Lampirkan foto kondisi lokasi, kondisi korban, atau bukti dokumen pendukung (JPG, PNG, PDF maks. 10MB per file).</p>
                        
                        <input 
                            type="file" 
                            wire:model="attachments" 
                            multiple 
                            accept=".jpg,.jpeg,.png,.pdf"
                            class="text-xs file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-rose-600 file:text-white hover:file:bg-rose-700 cursor-pointer w-full"
                        >
                        @error('attachments.*') <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span> @enderror

                        <div wire:loading wire:target="attachments" class="text-xs text-rose-600 font-semibold mt-1">
                            Mengunggah berkas lampiran...
                        </div>
                    </div>

                    <!-- Disclaimer Checkbox -->
                    <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" wire:model="agreement" class="w-4 h-4 text-rose-600 rounded border-slate-300 focus:ring-rose-500 mt-0.5">
                            <span class="text-xs text-rose-950 leading-relaxed">
                                Saya menyatakan bahwa pengaduan ini dibuat dengan sebenar-benarnya tanpa maksud mencemarkan nama baik atau menyebarkan laporan palsu. Saya memahami bahwa laporan palsu dapat ditindaklanjuti secara hukum.
                            </span>
                        </label>
                        @error('agreement') <span class="text-xs text-rose-600 mt-2 block font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4 flex justify-end">
                        <button 
                            type="submit" 
                            wire:loading.attr="disabled"
                            class="px-8 py-3.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-sm font-extrabold shadow-lg shadow-rose-600/30 flex items-center gap-2 disabled:opacity-50 transition-all"
                        >
                            <span wire:loading.remove>Kirim Pengaduan Sekarang</span>
                            <span wire:loading class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                Mengirim Laporan...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
