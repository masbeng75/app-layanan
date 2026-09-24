<?php

namespace Database\Seeders;

use App\Models\ServiceRequirement;
use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            // Layanan 1 (Prioritas)
            [
                'code' => 'DTSEN',
                'name' => 'Surat Keterangan DTSEN',
                'category' => 'Perlindungan & Jaminan Sosial',
                'description' => 'Penerbitan surat keterangan status pemohon/keluarga dalam Data Tunggal Sosial Ekonomi Nasional (DTSEN) dan peringkat desil, sebagai syarat SPMB Afirmasi, PIP, KIP Kuliah, bansos, dan layanan kesehatan.',
                'handler' => 'dtsen',
                'needs_assessment' => false,
                'sla_days' => 2,
                'is_active' => true,
                'requirements' => [
                    [
                        'name' => 'KTP Pemohon & Orang yang Diterangkan',
                        'is_mandatory' => true,
                        'allowed_mimes' => 'pdf,jpg,png',
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'Kartu Keluarga (KK)',
                        'is_mandatory' => true,
                        'allowed_mimes' => 'pdf,jpg,png',
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'Surat Pengantar dari Desa / Kelurahan',
                        'is_mandatory' => false,
                        'allowed_mimes' => 'pdf,jpg,png',
                        'sort_order' => 3,
                    ],
                ],
            ],

            // Layanan 2 (Prioritas)
            [
                'code' => 'PBI',
                'name' => 'Reaktivasi KIS / PBI-JK',
                'category' => 'Jaminan Sosial Kesehatan',
                'description' => 'Fasilitasi rekomendasi dan pengusulan reaktivasi kepesertaan JKN-KIS PBI-JK yang dinonaktifkan ke Kementerian Sosial RI untuk peserta dengan kondisi khusus/medis.',
                'handler' => 'pbi',
                'needs_assessment' => false,
                'sla_days' => 7,
                'is_active' => true,
                'requirements' => [
                    [
                        'name' => 'Kartu Tanda Penduduk (KTP) Peserta',
                        'is_mandatory' => true,
                        'allowed_mimes' => 'pdf,jpg,png',
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'Kartu Keluarga (KK)',
                        'is_mandatory' => true,
                        'allowed_mimes' => 'pdf,jpg,png',
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'Kartu BPJS Kesehatan / KIS yang Non-Aktif',
                        'is_mandatory' => true,
                        'allowed_mimes' => 'pdf,jpg,png',
                        'sort_order' => 3,
                    ],
                    [
                        'name' => 'Surat Keterangan Rawat / Rekomendasi Medis Faskes',
                        'is_mandatory' => true,
                        'allowed_mimes' => 'pdf,jpg,png',
                        'sort_order' => 4,
                    ],
                ],
            ],

            // Layanan 4 (Pengajuan Layanan Sosial Lainnya)
            [
                'code' => 'REHSOS_REQ',
                'name' => 'Permohonan Pelayanan Rehabilitasi Sosial',
                'category' => 'Rehabilitasi Sosial',
                'description' => 'Permohonan penanganan rehabilitasi sosial bagi lansia terlantar, penyandang disabilitas, anak terlantar, atau ODGJ oleh keluarga/masyarakat untuk mendapatkan asesmen dan rujukan.',
                'handler' => 'generic',
                'needs_assessment' => true,
                'sla_days' => 5,
                'is_active' => true,
                'requirements' => [
                    [
                        'name' => 'KTP Pemohon / Identitas Klien',
                        'is_mandatory' => false,
                        'allowed_mimes' => 'pdf,jpg,png',
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'Kartu Keluarga (KK)',
                        'is_mandatory' => false,
                        'allowed_mimes' => 'pdf,jpg,png',
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'Surat Keterangan / Pengantar dari Desa / Kelurahan',
                        'is_mandatory' => true,
                        'allowed_mimes' => 'pdf,jpg,png',
                        'sort_order' => 3,
                    ],
                    [
                        'name' => 'Foto Dokumentasi Kondisi Klien',
                        'is_mandatory' => true,
                        'allowed_mimes' => 'jpg,png',
                        'sort_order' => 4,
                    ],
                ],
            ],
            [
                'code' => 'REK_BANSOS',
                'name' => 'Rekomendasi Bantuan Sosial Terencana',
                'category' => 'Pemberdayaan Sosial & PFM',
                'description' => 'Surat rekomendasi usulan bantuan sosial terencana bagi keluarga miskin/rentan miskin yang belum terakomodasi program reguler.',
                'handler' => 'generic',
                'needs_assessment' => true,
                'sla_days' => 3,
                'is_active' => true,
                'requirements' => [
                    [
                        'name' => 'KTP Pemohon',
                        'is_mandatory' => true,
                        'allowed_mimes' => 'pdf,jpg,png',
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'Kartu Keluarga (KK)',
                        'is_mandatory' => true,
                        'allowed_mimes' => 'pdf,jpg,png',
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'Surat Keterangan Tidak Mampu (SKTM) Desa/Kelurahan',
                        'is_mandatory' => true,
                        'allowed_mimes' => 'pdf,jpg,png',
                        'sort_order' => 3,
                    ],
                    [
                        'name' => 'Foto Kondisi Rumah / Tempat Tinggal',
                        'is_mandatory' => true,
                        'allowed_mimes' => 'jpg,png',
                        'sort_order' => 4,
                    ],
                ],
            ],
            [
                'code' => 'REK_LKS',
                'name' => 'Rekomendasi Tanda Daftar Lembaga Kesejahteraan Sosial (LKS)',
                'category' => 'Pemberdayaan Sosial',
                'description' => 'Surat rekomendasi perpanjangan atau penerbitan Tanda Daftar LKS / Yayasan Sosial yang beroperasi di wilayah Kabupaten Blitar.',
                'handler' => 'generic',
                'needs_assessment' => false,
                'sla_days' => 7,
                'is_active' => true,
                'requirements' => [
                    [
                        'name' => 'Akta Notaris & Pengesahan Kemenkumham Pendirian Yayasan',
                        'is_mandatory' => true,
                        'allowed_mimes' => 'pdf',
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'Anggaran Dasar & Anggaran Rumah Tangga (AD/ART)',
                        'is_mandatory' => true,
                        'allowed_mimes' => 'pdf',
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'Susunan Pengurus & Surat Keterangan Domisili Lembaga',
                        'is_mandatory' => true,
                        'allowed_mimes' => 'pdf',
                        'sort_order' => 3,
                    ],
                    [
                        'name' => 'Profil Singkat & Laporan Kegiatan Pelayanan Sosial',
                        'is_mandatory' => true,
                        'allowed_mimes' => 'pdf',
                        'sort_order' => 4,
                    ],
                ],
            ],
        ];

        foreach ($services as $serviceData) {
            $requirements = $serviceData['requirements'];
            unset($serviceData['requirements']);

            $service = ServiceType::firstOrCreate(
                ['code' => $serviceData['code']],
                $serviceData
            );

            foreach ($requirements as $req) {
                ServiceRequirement::firstOrCreate(
                    [
                        'service_type_id' => $service->id,
                        'name' => $req['name'],
                    ],
                    [
                        'is_mandatory' => $req['is_mandatory'],
                        'allowed_mimes' => $req['allowed_mimes'],
                        'sort_order' => $req['sort_order'],
                    ]
                );
            }
        }
    }
}
