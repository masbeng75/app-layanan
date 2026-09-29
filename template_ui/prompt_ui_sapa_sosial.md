# Prompt Desain UI/Frontend — SAPA SOSIAL

> Sumber: `PRD_SAPA_SOSIAL.md` (Dinas Sosial Kabupaten Blitar).
> Dokumen ini berisi dua prompt terpisah:
> 1. **Prompt A** — Portal Publik (Livewire v4 + Tailwind CSS v4). Utama.
> 2. **Prompt B** — Dashboard Panel Admin (Filament v5). Opsional.
>
> Salin isi blok kode pada masing-masing prompt ke tool AI pilihan Anda (Claude, Cursor, dll.).
> Untuk tool berbasis React seperti v0 atau Lovable, ganti bagian **STACK** dengan "React + Tailwind CSS" karena tool tersebut tidak menjalankan Laravel/Livewire.

---

## Prompt A — Portal Publik

```text
PERAN
Kamu adalah UI/UX designer & frontend engineer senior yang berpengalaman merancang layanan publik pemerintah daerah di Indonesia. Buat desain antarmuka (high-fidelity) dan implementasi frontend untuk PORTAL PUBLIK "SAPA SOSIAL — Satu Pintu Layanan Sosial Kabupaten Blitar" milik Dinas Sosial Kabupaten Blitar.

TUJUAN PRODUK
Mengubah layanan sosial yang tersebar dan sulit dilacak menjadi satu pintu layanan yang setiap tahapannya tercatat dan bisa ditelusuri warga dari awal sampai selesai lewat nomor tiket.

PENGGUNA UTAMA & KONTEKS PEMAKAIAN
- Warga Kabupaten Blitar dengan literasi digital beragam: lansia, orang tua siswa, penyandang disabilitas, dan warga desa.
- Sebagian besar mengakses dari HP Android kelas menengah dengan sinyal tidak stabil → desain mobile-first, ringan, dan tetap nyaman di layar 360px.
- Operator Kecamatan/Desa/Puskesos yang membantu warga mengisi formulir → alurnya harus cepat dan tidak membingungkan.
- Bahasa antarmuka: Bahasa Indonesia yang sederhana, ramah, dan tidak birokratis (hindari istilah teknis tanpa penjelasan singkat).

STACK (WAJIB)
Laravel + Livewire v4 (full-page components) + Tailwind CSS v4 + Alpine.js. Formulir boleh memakai Filament Schemas di dalam komponen Livewire. Jangan pakai sintaks Livewire v3 atau API Filament v3 (HasForms/InteractsWithForms). Tanpa framework JS berat, gunakan aset seringan mungkin.

ARAH VISUAL
- Kesan: resmi, terpercaya, hangat, dan mudah dipahami. Bukan kaku seperti situs birokrasi lama, bukan pula terlalu "startup".
- Warna: palet utama biru-hijau (teal/emerald) yang menenangkan sebagai warna primer, aksen hangat (amber) untuk penekanan dan status "perlu tindakan". Definisikan sebagai design token (CSS variables / Tailwind theme) supaya mudah diganti sesuai identitas Pemkab Blitar. Sediakan slot logo Pemkab & Dinsos.
- Tipografi: sans-serif yang sangat terbaca (mis. Plus Jakarta Sans / Inter), ukuran dasar minimal 16px, line-height lega, hierarki jelas.
- Komponen: sudut membulat sedang, kartu dengan bayangan halus, ikon konsisten (Heroicons), ilustrasi ringan/opsional.
- Aksesibilitas (WCAG 2.1 AA): kontras warna cukup, target sentuh minimal 44px, fokus keyboard terlihat jelas, label form eksplisit (bukan hanya placeholder), pesan error spesifik dan berbahasa Indonesia, tidak mengandalkan warna saja untuk status (selalu ikon + teks). Dukung dark mode secara opsional.
- Semua interaksi ada state loading, kosong, error, dan sukses.

HALAMAN & KOMPONEN YANG HARUS DIDESAIN

1) BERANDA (/)
- Header: logo, menu (Layanan, Pengaduan, Cek Status, Informasi, Verifikasi Surat), tombol Masuk/Daftar; di mobile berupa menu hamburger/bottom sheet.
- Hero dengan kalimat sambutan singkat + 2 aksi utama: "Ajukan Layanan" dan "Cek Status Tiket". Tambahkan kolom cepat "Cek status" (input nomor tiket).
- Kartu 3 layanan prioritas (besar, menonjol): Surat Keterangan DTSEN, Reaktivasi KIS/PBI-JK, Rehabilitasi Sosial. Masing-masing berisi ikon, deskripsi 1 kalimat, perkiraan syarat, dan tombol aksi.
- Kartu sekunder: Pengaduan Sosial, Layanan Sosial Lainnya, Informasi & Formulir Unduhan, Verifikasi Keaslian Surat.
- Bagian "Bagaimana cara kerjanya" (4 langkah: Pilih layanan → Isi & unggah berkas → Dapat nomor tiket → Pantau sampai selesai).
- Pencarian informasi (kata kunci), FAQ ringkas, kontak/lokasi/jam layanan Dinsos, footer.

2) DAFTAR & DETAIL LAYANAN (/layanan, /layanan/{slug})
- Daftar layanan dengan pencarian dan filter kategori.
- Halaman detail: deskripsi, persyaratan (checklist), alur pelayanan (stepper vertikal), waktu pelayanan, lokasi, kontak, formulir unduhan (dengan label versi), FAQ, dan tombol tetap "Ajukan Sekarang" (sticky di mobile).

3) FORMULIR PENGAJUAN (multi-step wizard, tersimpan otomatis per langkah)
Langkah umum: Pilih jenis layanan → Data pemohon → Data khusus layanan → Unggah dokumen → Tinjau & kirim.
- Data pemohon: nama, NIK (16 digit, validasi format, input numerik, jangan hilang angka nol di depan), No. KK, alamat, kecamatan → desa/kelurahan (dropdown bertingkat), No. HP.
- Persyaratan dokumen tampil dinamis sesuai jenis layanan dan ditandai wajib/opsional; tampilkan progres unggah, preview, batas ukuran dan format, serta tombol ganti/hapus. Tambahkan penjelasan bahwa dokumen disimpan aman.
- Khusus SK DTSEN: pilih tujuan penggunaan (SPMB, PIP, KIP Kuliah, bansos, kesehatan, lainnya) + keterangan; data "orang yang diterangkan" (nama, NIK, hubungan dengan pemohon); unggah KTP & KK. Beri catatan bahwa penerbitan tergantung hasil pengecekan data.
- Khusus Reaktivasi PBI-JK: nama & NIK peserta, nomor kartu BPJS/KIS, perkiraan tanggal nonaktif, alasan reaktivasi (penyakit kronis/katastropik, darurat medis, bayi baru lahir dari ibu peserta PBI, lainnya); unggah KTP, KK, kartu BPJS/KIS, dan surat keterangan faskes (wajib untuk alasan medis, tampil kondisional). Alasan "darurat medis" menampilkan lencana "Prioritas".
- Langkah "Tinjau": ringkasan seluruh isian, bisa kembali mengedit, checkbox pernyataan kebenaran data.
- Layar sukses: nomor tiket besar dan mudah disalin (mis. DTSEN-202610-00012), tombol Salin, Bagikan, dan Cetak bukti, plus penjelasan langkah berikutnya dan cara cek status.
- Mode operator: ringkas, memungkinkan mengisi cepat untuk beberapa warga berturut-turut, dengan indikator "Diajukan oleh operator" (tanpa memangkas validasi).

4) CEK STATUS TIKET (/cek-status)
- Input nomor tiket + 4 digit terakhir NIK/No. HP (verifikasi ringan tanpa login).
- Halaman hasil berisi: ringkasan tiket (jenis layanan, tanggal, status saat ini dengan lencana warna + ikon), TIMELINE/STEPPER PROGRES yang menampilkan seluruh tahap alur layanan tersebut (langkah selesai, langkah aktif, langkah berikutnya) beserta tanggal/jam tiap perubahan dan catatan yang boleh dilihat publik.
  • DTSEN: Diajukan → Cek berkas → Verifikasi data → Menunggu persetujuan → Surat terbit → Selesai
  • PBI-JK: Diajukan → Cek berkas → Verifikasi kelayakan → Menunggu persetujuan → Rekomendasi terbit → Diusulkan ke Kemensos → Disetujui Kemensos → Aktif kembali → Selesai
  • Pengajuan lain: Diajukan → Cek berkas → Verifikasi → (Assessment) → Diproses → Selesai
  • Pengaduan: Diterima → Verifikasi → Disposisi → Ditangani → Selesai
- Status khusus yang harus punya tampilan jelas: "Perlu perbaikan" (tampilkan alasan + tombol Perbaiki Berkas, lalu kembali ke antrean), "Ditolak" (alasan + informasi tindak lanjut, mis. pemutakhiran DTSEN lewat desa), "Ditolak Kemensos", "Duplikat", "Tidak valid".
- Bila surat sudah terbit: kartu unduhan surat (PDF), nomor surat, tanggal terbit, masa berlaku, dan QR kode verifikasi.
- Tidak boleh menampilkan data sensitif secara penuh (samarkan NIK/HP: ****1234).

5) PENGADUAN SOSIAL (/pengaduan)
- Formulir singkat: kategori masalah, kecamatan & desa/kelurahan (wajib), deskripsi, nama pelapor & No. HP (wajib), lampiran foto/dokumen (opsional, dengan kompresi/preview di sisi klien).
- Layar sukses dengan nomor laporan (ADU-YYYYMM-NNNNN) dan tautan cek status.
- Tampilkan ajakan privasi: identitas pelapor dijaga.

6) VERIFIKASI KEASLIAN SURAT (/verifikasi/{kode})
- Halaman tujuan hasil scan QR. Tiga keadaan yang sangat jelas secara visual: ASLI & BERLAKU (hijau, ikon centang), KEDALUWARSA (amber), TIDAK DITEMUKAN (merah/netral, saran cek ulang kode).
- Tampilkan nomor surat, jenis surat, tanggal terbit, masa berlaku, pejabat penandatangan; data pribadi disamarkan.
- Input manual kode untuk yang tidak memakai QR.

7) INFORMASI LAYANAN (/informasi, /informasi/{slug})
- Pencarian kata kunci dengan saran otomatis, filter kategori (program sosial, rehabilitasi, disabilitas, lansia, pengaduan), daftar hasil, halaman detail artikel dengan tabel isi, unduhan formulir berlabel "versi terbaru", FAQ akordeon, dan tombol lanjut ke Pengajuan/Pengaduan.

8) AKUN MASYARAKAT
- Daftar/Masuk (sederhana, dengan opsi lupa kata sandi), lalu "Pengajuan Saya": daftar tiket dengan lencana status, filter, dan detail yang sama dengan halaman cek status. Profil dasar.

9) HALAMAN PENDUKUNG
- 404/500 yang ramah, halaman "sedang pemeliharaan", kebijakan privasi & syarat penggunaan (ringkas), halaman kontak/lokasi.

DESIGN SYSTEM YANG HARUS DIHASILKAN
- Token warna, tipografi, spasi, radius, bayangan; skala breakpoint (mobile 360, tablet 768, desktop 1280).
- Komponen Blade/Livewire yang bisa dipakai ulang: Button (primer/sekunder/ghost/destruktif, loading), Input/Select/Textarea/FileUpload/Checkbox/Radio dengan state error, StatusBadge (semua status di atas, ikon + label Indonesia), TicketNumber (dengan tombol salin), Stepper/Timeline, Card, Alert (info/sukses/peringatan/error), Modal/BottomSheet, Accordion, Breadcrumb, Pagination, EmptyState, Skeleton, Toast, Navbar/Footer.
- Pemetaan warna status yang konsisten: netral (diajukan/diterima), biru (sedang diproses/verifikasi), amber (perlu perbaikan/menunggu), hijau (selesai/terbit/aktif), merah (ditolak), abu (arsip/duplikat).

KEAMANAN & PRIVASI DALAM UI
- Samarkan NIK/No. HP di tempat yang tidak perlu menampilkannya penuh.
- Unduhan dokumen memakai signed URL sementara; tampilkan status "tautan berlaku sementara".
- Proteksi anti-spam pada form publik (mis. captcha ringan/honeypot) tanpa merusak aksesibilitas.
- Pesan yang jelas tentang penggunaan data pribadi pada form.

BATASAN
- Jangan menambah fitur di luar PRD (mis. chat, pembayaran, integrasi API SIKS-NG/BPJS otomatis, notifikasi WhatsApp) kecuali sebagai penanda "Akan datang" bila memang diminta.
- Semua label UI, pesan validasi, dan dokumen cetak dalam Bahasa Indonesia; nama tabel/kolom/route internal dan nilai status di kode dalam Bahasa Inggris (PHP Enum, label() bahasa Indonesia).
- Nilai yang bisa berubah karena kebijakan (batas desil, batas lama nonaktif, persyaratan) tidak di-hardcode di tampilan; ambil dari data master.

KELUARAN YANG DIMINTA
1. Ringkasan arah desain (palet, tipografi, prinsip) dalam maksimal 10 poin.
2. Sitemap dan alur pengguna utama (pengajuan, cek status, pengaduan, verifikasi surat).
3. Wireframe/mockup high-fidelity untuk setiap halaman di atas, dalam versi mobile dan desktop.
4. Design system (token + katalog komponen).
5. Implementasi frontend: layout Blade + komponen Livewire v4 + Tailwind v4, dimulai dari Beranda, Cek Status Tiket, dan Formulir Pengajuan SK DTSEN sebagai contoh acuan; sisanya mengikuti pola yang sama.
6. Daftar catatan aksesibilitas dan performa yang diterapkan.
```

---

## Prompt B — Dashboard Panel Admin (Opsional)

```text
Rancang tema dan halaman dashboard untuk panel admin Filament v5 SAPA SOSIAL (/admin). Gunakan API Filament v5 (Schemas, Tables, Widgets, Actions, enum Heroicon), bukan API v3. Terapkan tema kustom yang senada dengan portal publik (warna primer teal, tipografi sama).

Dashboard utama berisi widget dengan filter global periode, jenis layanan, status, kecamatan, dan desa/kelurahan:
- SK DTSEN diterbitkan (per tujuan penggunaan & desil) dan SK DTSEN menunggu tanda tangan
- Reaktivasi PBI-JK per tahap (verifikasi, menunggu Kemensos, aktif kembali, ditolak) dengan penanda "tertahan melebihi batas hari"
- Antrean reaktivasi darurat medis (prioritas, tampil paling atas)
- Kasus rehabilitasi sosial aktif (assessment, pelayanan, monitoring) dan rujukan per lembaga tujuan
- Pengajuan & pengaduan masuk, dalam proses vs selesai, sebaran per kecamatan/desa
- Informasi paling sering diakses (opsional)

Kebutuhan tambahan: menu dan data mengikuti role (Operator hanya melihat wilayahnya, Pimpinan hanya baca), tabel antrean dengan lencana status berwarna, tombol aksi transisi status, tampilan detail tiket dengan timeline riwayat status, dan ekspor Excel/PDF pada halaman laporan.
```

---

## Catatan Penyesuaian

- **Warna dan tipografi:** PRD tidak menyebut identitas visual, jadi prompt mengusulkan teal dengan aksen amber. Jika Pemkab Blitar punya pedoman warna atau logo, ganti pada bagian **ARAH VISUAL**.
- **Tool target:** ubah bagian **STACK** sesuai tool yang dipakai (lihat catatan di atas).
- **Cakupan:** prompt hanya memuat fitur yang ada di PRD. Notifikasi WhatsApp/SMS, TTE tersertifikasi, dan integrasi API SIKS-NG/BPJS masih berstatus "perlu dikonfirmasi" di PRD (Bagian 5), sehingga sengaja tidak dimasukkan.
