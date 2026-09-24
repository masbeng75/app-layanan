<?php

namespace Database\Seeders;

use App\Models\ClientCategory;
use App\Models\ReferralInstitution;
use Illuminate\Database\Seeder;

class RehabilitationMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Kategori Klien Rehabilitasi Sosial
        $categories = [
            'Lanjut Usia Terlantar (LUT)',
            'Penyandang Disabilitas Fisik / Sensorik / Intelektual Terlantar',
            'Orang Dengan Gangguan Jiwa (ODGJ) Terlantar',
            'Anak yang Memerlukan Perlindungan Khusus (AMPK) / Anak Terlantar',
            'Gelandangan dan Pengemis (Gepeng)',
            'Korban Tindak Kekerasan / Korban Perdagangan Orang (TPPO)',
        ];

        foreach ($categories as $catName) {
            ClientCategory::firstOrCreate(['name' => $catName]);
        }

        // 2. Lembaga Rujukan Pelayanan Sosial
        $institutions = [
            [
                'name' => 'UPT Pelayanan Sosial Tresna Werdha (PSTW) Blitar',
                'type' => 'panti',
                'address' => 'Jl. Merdeka No. 12, Kepanjenkidul, Kota Blitar',
                'contact' => '(0342) 801234',
                'is_active' => true,
            ],
            [
                'name' => 'RSUD Ngudi Waluyo Wlingi - Pelayanan Jiwa & Kedaruratan',
                'type' => 'RS',
                'address' => 'Jl. Dr. Soetomo No. 01 Wlingi, Kab. Blitar',
                'contact' => '(0342) 691006',
                'is_active' => true,
            ],
            [
                'name' => 'RSUD Srengat - Ruang Rawat Jiwa & Penanganan Medis',
                'type' => 'RS',
                'address' => 'Jl. Raya Dandong, Srengat, Kab. Blitar',
                'contact' => '(0342) 567890',
                'is_active' => true,
            ],
            [
                'name' => 'UPT Balai Rehabilitasi Sosial Bina Netra & Disabilitas Malang',
                'type' => 'balai',
                'address' => 'Jl. Terusan Sulfat, Lowokwaru, Kota Malang',
                'contact' => '(0341) 491122',
                'is_active' => true,
            ],
            [
                'name' => 'Sentra Terpadu Prof. Dr. Soeharso Surakarta (Kemensos RI)',
                'type' => 'balai',
                'address' => 'Jl. Tentara Pelajar No. 1, Jebres, Kota Surakarta',
                'contact' => '(0271) 714418',
                'is_active' => true,
            ],
            [
                'name' => 'LKS Kasih Bunda Peduli ODGJ & Disabilitas Blitar',
                'type' => 'LKS',
                'address' => 'Dusun Plosorejo, Kec. Kanigoro, Kab. Blitar',
                'contact' => '0812-3456-7890',
                'is_active' => true,
            ],
        ];

        foreach ($institutions as $inst) {
            ReferralInstitution::firstOrCreate(['name' => $inst['name']], $inst);
        }
    }
}
