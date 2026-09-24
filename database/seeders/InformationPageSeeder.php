<?php

namespace Database\Seeders;

use App\Enums\PublishStatus;
use App\Models\DownloadableForm;
use App\Models\Faq;
use App\Models\InformationPage;
use App\Models\PageVisit;
use App\Models\SearchLog;
use App\Models\ServiceType;
use App\Models\User;
use Illuminate\Database\Seeder;

class InformationPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@dinsos.blitarkab.go.id')->first() ?? User::first();
        $dtsenService = ServiceType::where('code', 'DTSEN')->first();
        $pbiService = ServiceType::where('code', 'PBI')->first();

        // 1. Informasi Layanan Surat Keterangan DTSEN
        $dtsenPage = InformationPage::firstOrCreate(
            ['slug' => 'sop-surat-keterangan-dtsen'],
            [
                'title' => 'SOP dan Prosedur Penerbitan Surat Keterangan DTSEN',
                'category' => 'program',
                'service_type_id' => $dtsenService?->id,
                'description' => 'Layanan penerbitan Surat Keterangan Data Tunggal Sosial Ekonomi Nasional (DTSEN) bagi warga Kabupaten Blitar untuk keperluan pendaftaran sekolah afirmasi (SPMB/PPDB), beasiswa PIP/KIP Kuliah, bantuan sosial, dan jaminan kesehatan.',
                'requirements' => "1. KTP Asli Pemohon dan Orang yang Diterangkan\n2. Kartu Keluarga (KK) Asli Kabupaten Blitar\n3. Surat Pengantar Desa/Kelurahan (jika melalui operator desa)\n4. Mengetahui tujuan peruntukan surat secara spesifik",
                'procedure' => "1. Pemohon mengajukan permohonan secara online melalui portal SAPA SOSIAL atau melalui kantor Desa/Kelurahan.\n2. Petugas Dinsos memverifikasi berkas dan melakukan pengecekan peringkat desil di database SIKS-NG Kemensos RI.\n3. Jika desil memenuhi ketentuan tujuan surat, draf surat diterbitkan otomatis dan diajukan ke Kepala Bidang serta Kepala Dinas.\n4. Surat resmi bertanda tangan elektronik/QR verifikasi keaslian diterbitkan dan dapat diunduh pemohon.",
                'service_hours' => 'Senin - Jumat: 08.00 - 15.00 WIB (Pengajuan online 24 jam)',
                'location' => 'Kantor Dinas Sosial Kab. Blitar, Jl. Sudanco Supriyadi No. 17 Blitar / Kantor Desa & Kecamatan Setempat',
                'contact' => 'WhatsApp Layanan: 0812-3456-7890 | Email: layanan@dinsos.blitarkab.go.id',
                'publish_status' => PublishStatus::PUBLISHED,
                'published_at' => now()->subDays(30),
                'manager_id' => $admin->id,
            ]
        );

        DownloadableForm::firstOrCreate(
            ['information_page_id' => $dtsenPage->id, 'name' => 'Formulir Permohonan SK DTSEN 2026.pdf'],
            [
                'file_path' => 'forms/formulir_sk_dtsen_2026.pdf',
                'version' => '1.2',
                'is_current' => true,
            ]
        );

        Faq::firstOrCreate(
            ['information_page_id' => $dtsenPage->id, 'question' => 'Apakah penerbitan Surat Keterangan DTSEN dipungut biaya?'],
            [
                'answer' => 'Tidak. Seluruh pelayanan di Dinas Sosial Kabupaten Blitar, termasuk penerbitan Surat Keterangan DTSEN, adalah 100% GRATIS dan bebas dari segala pungutan liar.',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        Faq::firstOrCreate(
            ['information_page_id' => $dtsenPage->id, 'question' => 'Berapa batas desil maksimal untuk pendaftaran SPMB/PPDB Jalur Afirmasi?'],
            [
                'answer' => 'Berdasarkan regulasi yang berlaku, batas desil maksimal untuk pendaftaran SPMB/PPDB jalur afirmasi adalah Desil 1 sampai dengan Desil 5.',
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        // 2. Informasi Layanan Reaktivasi KIS / PBI-JK
        $pbiPage = InformationPage::firstOrCreate(
            ['slug' => 'panduan-reaktivasi-pbi-jk'],
            [
                'title' => 'Panduan dan Prosedur Reaktivasi Kepesertaan KIS PBI-JK',
                'category' => 'program',
                'service_type_id' => $pbiService?->id,
                'description' => 'Fasilitasi rekomendasi pengaktifan kembali kartu BPJS Kesehatan Penerima Bantuan Iuran Jaminan Kesehatan (PBI-JK) yang dinonaktifkan oleh Kementerian Sosial RI.',
                'requirements' => "1. KTP dan Kartu Keluarga (KK) Peserta\n2. Kartu BPJS Kesehatan / KIS yang non-aktif\n3. Surat Keterangan Rawat Inap / Indikasi Medis Rutin dari Faskes (Puskesmas / Rumah Sakit)\n4. Terdaftar dalam Data Terpadu Kesejahteraan Sosial (DTKS/DTSEN)",
                'procedure' => "1. Pemohon mengisi formulir usulan reaktivasi online dan mengunggah berkas persyaratan medis.\n2. Verifikator mengecek kelayakan desil dan alasan medis darurat.\n3. Dinsos menerbitkan Surat Rekomendasi Reaktivasi.\n4. Dinsos menginput usulan ke SIKS-NG Kemensos RI.\n5. Kemensos dan BPJS Pusat memproses persetujuan reaktivasi.\n6. Status kepesertaan aktif kembali dapat dipantau langsung lewat tiket pengajuan.",
                'service_hours' => 'Senin - Jumat: 08.00 - 15.00 WIB (Kondisi gawat darurat medis diprioritaskan)',
                'location' => 'Bidang Linjamsos Dinsos Kab. Blitar / Loket Front Office Pelayanan',
                'contact' => 'Helpdesk PBI-JK: 0812-3456-7891',
                'publish_status' => PublishStatus::PUBLISHED,
                'published_at' => now()->subDays(25),
                'manager_id' => $admin->id,
            ]
        );

        DownloadableForm::firstOrCreate(
            ['information_page_id' => $pbiPage->id, 'name' => 'Formulir Usulan Reaktivasi KIS PBI-JK.pdf'],
            [
                'file_path' => 'forms/formulir_reaktivasi_pbi_2026.pdf',
                'version' => '1.0',
                'is_current' => true,
            ]
        );

        Faq::firstOrCreate(
            ['information_page_id' => $pbiPage->id, 'question' => 'Mengapa kartu BPJS PBI-JK saya tiba-tiba dinonaktifkan?'],
            [
                'answer' => 'Penonaktifan dilakukan berkala oleh Kementerian Sosial RI berdasarkan pemutakhiran data kependudukan (NIK tidak padan, perubahan desil ekonomi, atau penyesuaian kuota nasional).',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        // 3. Informasi Layanan Rehabilitasi Sosial
        $rehsosPage = InformationPage::firstOrCreate(
            ['slug' => 'standar-pelayanan-rehabilitasi-sosial'],
            [
                'title' => 'Standar Pelayanan Rehabilitasi Sosial bagi PPKS Terlantar',
                'category' => 'rehabilitation',
                'service_type_id' => null,
                'description' => 'Informasi komprehensif alur penerimaan, asesmen kondisi, penanganan langsung, serta mekanisme rujukan panti dan rumah sakit bagi Pemerlu Pelayanan Kesejahteraan Sosial (PPKS).',
                'requirements' => "1. Laporan keberadaan PPKS (melalui pengaduan publik atau penjangkauan petugas)\n2. Identitas diri (bila ada, jika tanpa identitas petugas melakukan penelusuran biometrik)\n3. Informasi lokasi keberadaan klien",
                'procedure' => "1. Penerimaan laporan atau klien terlantar.\n2. Asesmen awal oleh Pekerja Sosial Dinsos.\n3. Penentuan rencana intervensi (pelayanan langsung atau rujukan lembaga).\n4. Penempatan di panti/rumah sakit rujukan bila diperlukan.\n5. Monitoring perkembangan klien hingga mandiri atau terminasi kasus.",
                'service_hours' => 'Pelayanan Unit Respon Cepat (URC) Rehsos Siaga 24 Jam',
                'location' => 'Bidang Rehabilitasi Sosial Dinsos Kab. Blitar',
                'contact' => 'Hotline Respon Cepat Rehsos: 0856-4900-1122',
                'publish_status' => PublishStatus::PUBLISHED,
                'published_at' => now()->subDays(20),
                'manager_id' => $admin->id,
            ]
        );

        Faq::firstOrCreate(
            ['information_page_id' => $rehsosPage->id, 'question' => 'Lembaga mana saja yang menjadi mitra rujukan rehabilitasi Dinsos Blitar?'],
            [
                'answer' => 'Dinsos Kab. Blitar bermitra dengan UPT PSTW Blitar (lansia), RSUD Ngudi Waluyo Wlingi dan RSUD Srengat (medis/jiwa), Balai Rehabilitasi Bina Netra/Disabilitas Malang, dan Sentra Terpadu Prof. Dr. Soeharso Surakarta.',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        // 4. Informasi Layanan Pengaduan Masalah Sosial
        $complaintPage = InformationPage::firstOrCreate(
            ['slug' => 'mekanisme-pengaduan-sosial'],
            [
                'title' => 'Mekanisme dan Alur Pengaduan Masalah Sosial Kabupaten Blitar',
                'category' => 'complaint',
                'service_type_id' => null,
                'description' => 'Saluran resmi masyarakat untuk melaporkan permasalahan sosial di lingkungannya, seperti warga terlantar, ODGJ meresahkan, atau bansos tidak tepat sasaran.',
                'requirements' => "1. Nama pelapor dan nomor telepon aktif\n2. Lokasi kejadian jelas (minimal desa dan kecamatan)\n3. Uraian deskripsi permasalahan\n4. Foto/bukti pendukung kejadian",
                'procedure' => "1. Pelapor mengirimkan laporan lewat formulir pengaduan SAPA SOSIAL.\n2. Petugas memverifikasi kebenaran laporan dalam waktu 1x24 jam.\n3. Disposisi penanganan ke tim lapangan / TKSK / Satpol PP.\n4. Penanganan masalah dan input bukti tindak lanjut ke sistem.\n5. Pelapor menerima pemberitahuan penyelesaian laporan melalui nomor tiket.",
                'service_hours' => 'Pelaporan online 24 jam',
                'location' => 'Portal Resmi SAPA SOSIAL Kabupaten Blitar',
                'contact' => 'Call Center Pengaduan Dinsos: (0342) 801123',
                'publish_status' => PublishStatus::PUBLISHED,
                'published_at' => now()->subDays(15),
                'manager_id' => $admin->id,
            ]
        );

        // FAQ Umum tanpa halaman khusus
        Faq::firstOrCreate(
            ['question' => 'Bagaimana cara memeriksa keaslian Surat Keterangan DTSEN?'],
            [
                'information_page_id' => null,
                'answer' => 'Anda dapat memindai QR Code yang tertera pada bagian bawah surat menggunakan kamera HP, atau memasukkan kode verifikasi surat pada menu Cek Keaslian SK di portal SAPA SOSIAL.',
                'sort_order' => 10,
                'is_active' => true,
            ]
        );

        // Statistik Kunjungan (Page Visits)
        $pages = [$dtsenPage, $pbiPage, $rehsosPage, $complaintPage];
        foreach ($pages as $p) {
            for ($daysAgo = 7; $daysAgo >= 0; $daysAgo--) {
                PageVisit::firstOrCreate(
                    [
                        'information_page_id' => $p->id,
                        'visit_date' => now()->subDays($daysAgo)->toDateString(),
                    ],
                    [
                        'visit_count' => rand(15, 80),
                    ]
                );
            }
        }

        // Log Pencarian Populer
        $searchKeywords = [
            ['keyword' => 'syarat dtsen', 'result_count' => 5],
            ['keyword' => 'reaktivasi kis', 'result_count' => 4],
            ['keyword' => 'desil 1', 'result_count' => 6],
            ['keyword' => 'spmb afirmasi', 'result_count' => 3],
            ['keyword' => 'bantuan lansia', 'result_count' => 2],
            ['keyword' => 'odgj terlantar', 'result_count' => 4],
            ['keyword' => 'cek tiket', 'result_count' => 8],
        ];

        foreach ($searchKeywords as $log) {
            SearchLog::create([
                'keyword' => $log['keyword'],
                'result_count' => $log['result_count'],
                'searched_at' => now()->subHours(rand(1, 48)),
            ]);
        }
    }
}
