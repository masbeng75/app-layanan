<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Village;
use App\Models\WorkUnit;
use Illuminate\Database\Seeder;

class MasterWilayahAndWorkUnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Work Units (Unit Kerja / Bidang di Dinas Sosial Kab. Blitar)
        $workUnits = [
            ['name' => 'Sekretariat', 'is_active' => true],
            ['name' => 'Bidang Perlindungan dan Jaminan Sosial (Linjamsos)', 'is_active' => true],
            ['name' => 'Bidang Rehabilitasi Sosial (Rehsos)', 'is_active' => true],
            ['name' => 'Bidang Pemberdayaan Sosial dan Penanganan Fakir Miskin (Dayasos PFM)', 'is_active' => true],
        ];

        foreach ($workUnits as $unit) {
            WorkUnit::firstOrCreate(['name' => $unit['name']], $unit);
        }

        // 2. Districts (22 Kecamatan di Kabupaten Blitar)
        $districts = [
            ['code' => '35.05.01', 'name' => 'Wonotirto'],
            ['code' => '35.05.02', 'name' => 'Bakung'],
            ['code' => '35.05.03', 'name' => 'Panggungrejo'],
            ['code' => '35.05.04', 'name' => 'Wates'],
            ['code' => '35.05.05', 'name' => 'Binangun'],
            ['code' => '35.05.06', 'name' => 'Sutojayan'],
            ['code' => '35.05.07', 'name' => 'Kademangan'],
            ['code' => '35.05.08', 'name' => 'Kanigoro'],
            ['code' => '35.05.09', 'name' => 'Talun'],
            ['code' => '35.05.10', 'name' => 'Selopuro'],
            ['code' => '35.05.11', 'name' => 'Kesamben'],
            ['code' => '35.05.12', 'name' => 'Wlingi'],
            ['code' => '35.05.13', 'name' => 'Doko'],
            ['code' => '35.05.14', 'name' => 'Gandusari'],
            ['code' => '35.05.15', 'name' => 'Garum'],
            ['code' => '35.05.16', 'name' => 'Nglegok'],
            ['code' => '35.05.17', 'name' => 'Sanankulon'],
            ['code' => '35.05.18', 'name' => 'Ponggok'],
            ['code' => '35.05.19', 'name' => 'Srengat'],
            ['code' => '35.05.20', 'name' => 'Wonodadi'],
            ['code' => '35.05.21', 'name' => 'Udanawu'],
            ['code' => '35.05.22', 'name' => 'Selorejo'],
        ];

        $districtMap = [];
        foreach ($districts as $district) {
            $record = District::firstOrCreate(['code' => $district['code']], $district);
            $districtMap[$district['code']] = $record->id;
        }

        // 3. Villages (Desa / Kelurahan untuk representasi utama)
        $villages = [
            // Kanigoro (Ibukota Kabupaten Blitar)
            ['district_code' => '35.05.08', 'code' => '35.05.08.1001', 'name' => 'Kelurahan Kanigoro'],
            ['district_code' => '35.05.08', 'code' => '35.05.08.1002', 'name' => 'Kelurahan Satreyan'],
            ['district_code' => '35.05.08', 'code' => '35.05.08.2003', 'name' => 'Desa Sawentar'],
            ['district_code' => '35.05.08', 'code' => '35.05.08.2004', 'name' => 'Desa Tlogo'],
            ['district_code' => '35.05.08', 'code' => '35.05.08.2005', 'name' => 'Desa Gaprang'],
            ['district_code' => '35.05.08', 'code' => '35.05.08.2006', 'name' => 'Desa Minggirsari'],
            ['district_code' => '35.05.08', 'code' => '35.05.08.2007', 'name' => 'Desa Papungan'],
            ['district_code' => '35.05.08', 'code' => '35.05.08.2008', 'name' => 'Desa Kuningan'],
            ['district_code' => '35.05.08', 'code' => '35.05.08.2009', 'name' => 'Desa Gogodeso'],
            ['district_code' => '35.05.08', 'code' => '35.05.08.2010', 'name' => 'Desa Karangsono'],

            // Wlingi
            ['district_code' => '35.05.12', 'code' => '35.05.12.1001', 'name' => 'Kelurahan Wlingi'],
            ['district_code' => '35.05.12', 'code' => '35.05.12.1002', 'name' => 'Kelurahan Beru'],
            ['district_code' => '35.05.12', 'code' => '35.05.12.1003', 'name' => 'Kelurahan Babadan'],
            ['district_code' => '35.05.12', 'code' => '35.05.12.1004', 'name' => 'Kelurahan Klemunan'],
            ['district_code' => '35.05.12', 'code' => '35.05.12.1005', 'name' => 'Kelurahan Tangkil'],
            ['district_code' => '35.05.12', 'code' => '35.05.12.2006', 'name' => 'Desa Tegalasri'],
            ['district_code' => '35.05.12', 'code' => '35.05.12.2007', 'name' => 'Desa Tembalang'],
            ['district_code' => '35.05.12', 'code' => '35.05.12.2008', 'name' => 'Desa Ngadirenggo'],

            // Srengat
            ['district_code' => '35.05.19', 'code' => '35.05.19.1001', 'name' => 'Kelurahan Srengat'],
            ['district_code' => '35.05.19', 'code' => '35.05.19.1002', 'name' => 'Kelurahan Kauman'],
            ['district_code' => '35.05.19', 'code' => '35.05.19.1003', 'name' => 'Kelurahan Dandong'],
            ['district_code' => '35.05.19', 'code' => '35.05.19.1004', 'name' => 'Kelurahan Togogan'],
            ['district_code' => '35.05.19', 'code' => '35.05.19.2005', 'name' => 'Desa Karanggayam'],
            ['district_code' => '35.05.19', 'code' => '35.05.19.2006', 'name' => 'Desa Purwokerto'],

            // Sutojayan (Lodoyo)
            ['district_code' => '35.05.06', 'code' => '35.05.06.1001', 'name' => 'Kelurahan Sutojayan'],
            ['district_code' => '35.05.06', 'code' => '35.05.06.1002', 'name' => 'Kelurahan Kalipang'],
            ['district_code' => '35.05.06', 'code' => '35.05.06.1003', 'name' => 'Kelurahan Kedungbunder'],
            ['district_code' => '35.05.06', 'code' => '35.05.06.1004', 'name' => 'Kelurahan Kembangarum'],
            ['district_code' => '35.05.06', 'code' => '35.05.06.1005', 'name' => 'Kelurahan Sukorejo'],
            ['district_code' => '35.05.06', 'code' => '35.05.06.2006', 'name' => 'Desa Kaulon'],

            // Garum
            ['district_code' => '35.05.15', 'code' => '35.05.15.1001', 'name' => 'Kelurahan Garum'],
            ['district_code' => '35.05.15', 'code' => '35.05.15.1002', 'name' => 'Kelurahan Tawangsari'],
            ['district_code' => '35.05.15', 'code' => '35.05.15.1003', 'name' => 'Kelurahan Bence'],
            ['district_code' => '35.05.15', 'code' => '35.05.15.1004', 'name' => 'Kelurahan Slorok'],
            ['district_code' => '35.05.15', 'code' => '35.05.15.2005', 'name' => 'Desa Pojok'],

            // Talun
            ['district_code' => '35.05.09', 'code' => '35.05.09.1001', 'name' => 'Kelurahan Talun'],
            ['district_code' => '35.05.09', 'code' => '35.05.09.1002', 'name' => 'Kelurahan Kamulan'],
            ['district_code' => '35.05.09', 'code' => '35.05.09.1003', 'name' => 'Kelurahan Bajang'],
            ['district_code' => '35.05.09', 'code' => '35.05.09.2004', 'name' => 'Desa Pasirharjo'],

            // Nglegok
            ['district_code' => '35.05.16', 'code' => '35.05.16.1001', 'name' => 'Kelurahan Nglegok'],
            ['district_code' => '35.05.16', 'code' => '35.05.16.2002', 'name' => 'Desa Jiwut'],
            ['district_code' => '35.05.16', 'code' => '35.05.16.2003', 'name' => 'Desa Modangan'],
            ['district_code' => '35.05.16', 'code' => '35.05.16.2004', 'name' => 'Desa Penataran'],
        ];

        foreach ($villages as $village) {
            $districtId = $districtMap[$village['district_code']] ?? null;
            if ($districtId) {
                Village::firstOrCreate(
                    ['code' => $village['code']],
                    [
                        'district_id' => $districtId,
                        'name' => $village['name'],
                    ]
                );
            }
        }
    }
}
