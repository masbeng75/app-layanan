<?php

namespace Database\Seeders;

use App\Models\DtsenPurpose;
use Illuminate\Database\Seeder;

class DtsenPurposeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $purposes = [
            [
                'code' => 'spmb',
                'name' => 'Pendaftaran Masuk Sekolah (SPMB / PPDB) Jalur Afirmasi',
                'max_decile' => 5,
                'validity_days' => 90,
                'is_active' => true,
            ],
            [
                'code' => 'pip',
                'name' => 'Pengusulan Program Indonesia Pintar (PIP)',
                'max_decile' => 4,
                'validity_days' => 180,
                'is_active' => true,
            ],
            [
                'code' => 'kip_kuliah',
                'name' => 'Pengajuan Beasiswa KIP Kuliah / Perguruan Tinggi',
                'max_decile' => 4,
                'validity_days' => 180,
                'is_active' => true,
            ],
            [
                'code' => 'bansos',
                'name' => 'Pengusulan Bantuan Sosial (PKH / Sembako / Bantuan Daerah)',
                'max_decile' => 3,
                'validity_days' => 90,
                'is_active' => true,
            ],
            [
                'code' => 'kesehatan',
                'name' => 'Jaminan Pelayanan Kesehatan Daerah (Biakes Maskin)',
                'max_decile' => 3,
                'validity_days' => 60,
                'is_active' => true,
            ],
            [
                'code' => 'lainnya',
                'name' => 'Keperluan Administrasi & Bantuan Sosial Lainnya',
                'max_decile' => 4,
                'validity_days' => 90,
                'is_active' => true,
            ],
        ];

        foreach ($purposes as $purpose) {
            DtsenPurpose::firstOrCreate(['code' => $purpose['code']], $purpose);
        }
    }
}
