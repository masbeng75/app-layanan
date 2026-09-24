<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\User;
use App\Models\Village;
use App\Models\WorkUnit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('password');

        // Ambil ID Unit Kerja
        $sekretariat = WorkUnit::where('name', 'like', '%Sekretariat%')->first();
        $linjamsos = WorkUnit::where('name', 'like', '%Linjamsos%')->first();
        $rehsos = WorkUnit::where('name', 'like', '%Rehsos%')->first();
        $dayasos = WorkUnit::where('name', 'like', '%Dayasos%')->first();

        // Ambil Data Wilayah untuk Operator & Warga
        $kanigoroDistrict = District::where('code', '35.05.08')->first();
        $wlingiDistrict = District::where('code', '35.05.12')->first();
        $srengatDistrict = District::where('code', '35.05.19')->first();

        $satreyanVillage = Village::where('code', '35.05.08.1002')->first();
        $sawentarVillage = Village::where('code', '35.05.08.2003')->first();
        $beruVillage = Village::where('code', '35.05.12.1002')->first();
        $dandongVillage = Village::where('code', '35.05.19.1003')->first();

        $users = [
            // 1. Administrator Sistem
            [
                'email' => 'admin@dinsos.blitarkab.go.id',
                'name' => 'Ahmad Mu\'amar Muzakki, S.Kom.',
                'phone' => '081234567001',
                'nik' => '3505081001890001',
                'work_unit_id' => $sekretariat?->id,
                'district_id' => null,
                'village_id' => null,
                'is_active' => true,
            ],

            // 2. Pimpinan & Pejabat Penandatangan Utama (Kepala Dinas)
            [
                'email' => 'kadis@dinsos.blitarkab.go.id',
                'name' => 'Drs. Bambang Hermanto, M.Si.',
                'phone' => '081234567002',
                'nik' => '3505081504680001',
                'work_unit_id' => $sekretariat?->id,
                'district_id' => null,
                'village_id' => null,
                'is_active' => true,
            ],

            // 3. Pejabat Penandatangan / Kabid (Paraf Tahap 1)
            [
                'email' => 'kabid.linjamsos@dinsos.blitarkab.go.id',
                'name' => 'Dr. Hj. Siti Rahmawati, S.Sos., M.AP.',
                'phone' => '081234567003',
                'nik' => '3505085208750001',
                'work_unit_id' => $linjamsos?->id,
                'district_id' => null,
                'village_id' => null,
                'is_active' => true,
            ],
            [
                'email' => 'kabid.rehsos@dinsos.blitarkab.go.id',
                'name' => 'Agus Suhartono, S.ST.',
                'phone' => '081234567004',
                'nik' => '3505082003730002',
                'work_unit_id' => $rehsos?->id,
                'district_id' => null,
                'village_id' => null,
                'is_active' => true,
            ],
            [
                'email' => 'kabid.dayasos@dinsos.blitarkab.go.id',
                'name' => 'Endang Sri Wahyuni, SE.',
                'phone' => '081234567005',
                'nik' => '3505086011770001',
                'work_unit_id' => $dayasos?->id,
                'district_id' => null,
                'village_id' => null,
                'is_active' => true,
            ],

            // 4. Petugas Teknis Dinsos (Verifikator & Pelaksana)
            [
                'email' => 'petugas.dtsen@dinsos.blitarkab.go.id',
                'name' => 'Rina Novita, S.Sos.',
                'phone' => '081234567010',
                'nik' => '3505086405920002',
                'work_unit_id' => $linjamsos?->id,
                'district_id' => null,
                'village_id' => null,
                'is_active' => true,
            ],
            [
                'email' => 'petugas.pbi@dinsos.blitarkab.go.id',
                'name' => 'Budi Setiawan, A.Md.',
                'phone' => '081234567011',
                'nik' => '3505081109900003',
                'work_unit_id' => $linjamsos?->id,
                'district_id' => null,
                'village_id' => null,
                'is_active' => true,
            ],
            [
                'email' => 'petugas.rehsos@dinsos.blitarkab.go.id',
                'name' => 'Dian Purnamasari, S.Tr.Sos.',
                'phone' => '081234567012',
                'nik' => '3505085507940001',
                'work_unit_id' => $rehsos?->id,
                'district_id' => null,
                'village_id' => null,
                'is_active' => true,
            ],
            [
                'email' => 'petugas.layanan@dinsos.blitarkab.go.id',
                'name' => 'M. Rizal Fachri',
                'phone' => '081234567013',
                'nik' => '3505080512960002',
                'work_unit_id' => $sekretariat?->id,
                'district_id' => null,
                'village_id' => null,
                'is_active' => true,
            ],

            // 5. Operator Kecamatan
            [
                'email' => 'operator.kanigoro@blitarkab.go.id',
                'name' => 'Hendra Pratama',
                'phone' => '085649000101',
                'nik' => '3505081402880004',
                'work_unit_id' => null,
                'district_id' => $kanigoroDistrict?->id,
                'village_id' => null,
                'is_active' => true,
            ],
            [
                'email' => 'operator.wlingi@blitarkab.go.id',
                'name' => 'Nurul Hidayati',
                'phone' => '085649000102',
                'nik' => '3505125206910003',
                'work_unit_id' => null,
                'district_id' => $wlingiDistrict?->id,
                'village_id' => null,
                'is_active' => true,
            ],
            [
                'email' => 'operator.srengat@blitarkab.go.id',
                'name' => 'Arif Budiman',
                'phone' => '085649000103',
                'nik' => '3505191807870002',
                'work_unit_id' => null,
                'district_id' => $srengatDistrict?->id,
                'village_id' => null,
                'is_active' => true,
            ],

            // 6. Operator Desa / Kelurahan
            [
                'email' => 'operator.satreyan@blitarkab.go.id',
                'name' => 'Dewi Astuti',
                'phone' => '087756000201',
                'nik' => '3505086810930002',
                'work_unit_id' => null,
                'district_id' => $kanigoroDistrict?->id,
                'village_id' => $satreyanVillage?->id,
                'is_active' => true,
            ],
            [
                'email' => 'operator.sawentar@blitarkab.go.id',
                'name' => 'Eko Prasetyo',
                'phone' => '087756000202',
                'nik' => '3505082103900005',
                'work_unit_id' => null,
                'district_id' => $kanigoroDistrict?->id,
                'village_id' => $sawentarVillage?->id,
                'is_active' => true,
            ],

            // 7. Masyarakat (Warga Terdaftar)
            [
                'email' => 'siti.aminah@gmail.com',
                'name' => 'Siti Aminah',
                'phone' => '081298765001',
                'nik' => '3505084501900001',
                'work_unit_id' => null,
                'district_id' => $kanigoroDistrict?->id,
                'village_id' => $satreyanVillage?->id,
                'is_active' => true,
            ],
            [
                'email' => 'bambang.wijaya@gmail.com',
                'name' => 'Bambang Wijaya',
                'phone' => '081298765002',
                'nik' => '3505121508820003',
                'work_unit_id' => null,
                'district_id' => $wlingiDistrict?->id,
                'village_id' => $beruVillage?->id,
                'is_active' => true,
            ],
            [
                'email' => 'agus.santoso@gmail.com',
                'name' => 'Agus Santoso',
                'phone' => '081298765003',
                'nik' => '3505191206780004',
                'work_unit_id' => null,
                'district_id' => $srengatDistrict?->id,
                'village_id' => $dandongVillage?->id,
                'is_active' => true,
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                array_merge($userData, [
                    'password' => $defaultPassword,
                    'email_verified_at' => now(),
                ])
            );
        }
    }
}
