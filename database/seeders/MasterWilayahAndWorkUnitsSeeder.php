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

        // 3. Villages (248 Desa & Kelurahan di Seluruh 22 Kecamatan Kabupaten Blitar)
        $villages = [
            // Wonotirto (35.05.01) - 8 Desa
            ['district_code' => '35.05.01', 'code' => '35.05.01.2001', 'name' => 'Desa Gununggede'],
            ['district_code' => '35.05.01', 'code' => '35.05.01.2002', 'name' => 'Desa Kaligrenjeng'],
            ['district_code' => '35.05.01', 'code' => '35.05.01.2003', 'name' => 'Desa Ngadipuro'],
            ['district_code' => '35.05.01', 'code' => '35.05.01.2004', 'name' => 'Desa Ngeni'],
            ['district_code' => '35.05.01', 'code' => '35.05.01.2005', 'name' => 'Desa Pasiraman'],
            ['district_code' => '35.05.01', 'code' => '35.05.01.2006', 'name' => 'Desa Sumberboto'],
            ['district_code' => '35.05.01', 'code' => '35.05.01.2007', 'name' => 'Desa Tambakrejo'],
            ['district_code' => '35.05.01', 'code' => '35.05.01.2008', 'name' => 'Desa Wonotirto'],

            // Bakung (35.05.02) - 11 Desa
            ['district_code' => '35.05.02', 'code' => '35.05.02.2001', 'name' => 'Desa Bakung'],
            ['district_code' => '35.05.02', 'code' => '35.05.02.2002', 'name' => 'Desa Bululawang'],
            ['district_code' => '35.05.02', 'code' => '35.05.02.2003', 'name' => 'Desa Kedungbanteng'],
            ['district_code' => '35.05.02', 'code' => '35.05.02.2004', 'name' => 'Desa Lorejo'],
            ['district_code' => '35.05.02', 'code' => '35.05.02.2005', 'name' => 'Desa Ngrejo'],
            ['district_code' => '35.05.02', 'code' => '35.05.02.2006', 'name' => 'Desa Plandirejo'],
            ['district_code' => '35.05.02', 'code' => '35.05.02.2007', 'name' => 'Desa Pulerejo'],
            ['district_code' => '35.05.02', 'code' => '35.05.02.2008', 'name' => 'Desa Sidomulyo'],
            ['district_code' => '35.05.02', 'code' => '35.05.02.2009', 'name' => 'Desa Sumberdadi'],
            ['district_code' => '35.05.02', 'code' => '35.05.02.2010', 'name' => 'Desa Tumpakkepuh'],
            ['district_code' => '35.05.02', 'code' => '35.05.02.2011', 'name' => 'Desa Tumpakoyot'],

            // Panggungrejo (35.05.03) - 10 Desa
            ['district_code' => '35.05.03', 'code' => '35.05.03.2001', 'name' => 'Desa Balerejo'],
            ['district_code' => '35.05.03', 'code' => '35.05.03.2002', 'name' => 'Desa Bumiayu'],
            ['district_code' => '35.05.03', 'code' => '35.05.03.2003', 'name' => 'Desa Kaligambir'],
            ['district_code' => '35.05.03', 'code' => '35.05.03.2004', 'name' => 'Desa Kalitengah'],
            ['district_code' => '35.05.03', 'code' => '35.05.03.2005', 'name' => 'Desa Margomulyo'],
            ['district_code' => '35.05.03', 'code' => '35.05.03.2006', 'name' => 'Desa Panggungasri'],
            ['district_code' => '35.05.03', 'code' => '35.05.03.2007', 'name' => 'Desa Panggungrejo'],
            ['district_code' => '35.05.03', 'code' => '35.05.03.2008', 'name' => 'Desa Serang'],
            ['district_code' => '35.05.03', 'code' => '35.05.03.2009', 'name' => 'Desa Sumberagung'],
            ['district_code' => '35.05.03', 'code' => '35.05.03.2010', 'name' => 'Desa Sumbersih'],

            // Wates (35.05.04) - 8 Desa
            ['district_code' => '35.05.04', 'code' => '35.05.04.2001', 'name' => 'Desa Mojorejo'],
            ['district_code' => '35.05.04', 'code' => '35.05.04.2002', 'name' => 'Desa Purworejo'],
            ['district_code' => '35.05.04', 'code' => '35.05.04.2003', 'name' => 'Desa Ringinrejo'],
            ['district_code' => '35.05.04', 'code' => '35.05.04.2004', 'name' => 'Desa Sukorejo'],
            ['district_code' => '35.05.04', 'code' => '35.05.04.2005', 'name' => 'Desa Sumberarum'],
            ['district_code' => '35.05.04', 'code' => '35.05.04.2006', 'name' => 'Desa Tugurejo'],
            ['district_code' => '35.05.04', 'code' => '35.05.04.2007', 'name' => 'Desa Tulungrejo'],
            ['district_code' => '35.05.04', 'code' => '35.05.04.2008', 'name' => 'Desa Wates'],

            // Binangun (35.05.05) - 12 Desa
            ['district_code' => '35.05.05', 'code' => '35.05.05.2001', 'name' => 'Desa Binangun'],
            ['district_code' => '35.05.05', 'code' => '35.05.05.2002', 'name' => 'Desa Birowo'],
            ['district_code' => '35.05.05', 'code' => '35.05.05.2003', 'name' => 'Desa Kedungwungu'],
            ['district_code' => '35.05.05', 'code' => '35.05.05.2004', 'name' => 'Desa Ngadri'],
            ['district_code' => '35.05.05', 'code' => '35.05.05.2005', 'name' => 'Desa Ngembul'],
            ['district_code' => '35.05.05', 'code' => '35.05.05.2006', 'name' => 'Desa Rejoso'],
            ['district_code' => '35.05.05', 'code' => '35.05.05.2007', 'name' => 'Desa Salamrejo'],
            ['district_code' => '35.05.05', 'code' => '35.05.05.2008', 'name' => 'Desa Sambigede'],
            ['district_code' => '35.05.05', 'code' => '35.05.05.2009', 'name' => 'Desa Sukorame'],
            ['district_code' => '35.05.05', 'code' => '35.05.05.2010', 'name' => 'Desa Sumberkembar'],
            ['district_code' => '35.05.05', 'code' => '35.05.05.2011', 'name' => 'Desa Tawangrejo'],
            ['district_code' => '35.05.05', 'code' => '35.05.05.2012', 'name' => 'Desa Umbuldamar'],

            // Sutojayan (35.05.06) - 7 Kelurahan, 4 Desa
            ['district_code' => '35.05.06', 'code' => '35.05.06.1001', 'name' => 'Kelurahan Sutojayan'],
            ['district_code' => '35.05.06', 'code' => '35.05.06.1002', 'name' => 'Kelurahan Kalipang'],
            ['district_code' => '35.05.06', 'code' => '35.05.06.1003', 'name' => 'Kelurahan Kedungbunder'],
            ['district_code' => '35.05.06', 'code' => '35.05.06.1004', 'name' => 'Kelurahan Kembangarum'],
            ['district_code' => '35.05.06', 'code' => '35.05.06.1005', 'name' => 'Kelurahan Sukorejo'],
            ['district_code' => '35.05.06', 'code' => '35.05.06.2006', 'name' => 'Desa Kaulon'],
            ['district_code' => '35.05.06', 'code' => '35.05.06.1007', 'name' => 'Kelurahan Jegu'],
            ['district_code' => '35.05.06', 'code' => '35.05.06.1008', 'name' => 'Kelurahan Jingglong'],
            ['district_code' => '35.05.06', 'code' => '35.05.06.2009', 'name' => 'Desa Bacem'],
            ['district_code' => '35.05.06', 'code' => '35.05.06.2010', 'name' => 'Desa Pandanarum'],
            ['district_code' => '35.05.06', 'code' => '35.05.06.2011', 'name' => 'Desa Sumberjo'],

            // Kademangan (35.05.07) - 1 Kelurahan, 14 Desa
            ['district_code' => '35.05.07', 'code' => '35.05.07.1001', 'name' => 'Kelurahan Kademangan'],
            ['district_code' => '35.05.07', 'code' => '35.05.07.2002', 'name' => 'Desa Bendosari'],
            ['district_code' => '35.05.07', 'code' => '35.05.07.2003', 'name' => 'Desa Darungan'],
            ['district_code' => '35.05.07', 'code' => '35.05.07.2004', 'name' => 'Desa Dawuhan'],
            ['district_code' => '35.05.07', 'code' => '35.05.07.2005', 'name' => 'Desa Jimbe'],
            ['district_code' => '35.05.07', 'code' => '35.05.07.2006', 'name' => 'Desa Kebonsari'],
            ['district_code' => '35.05.07', 'code' => '35.05.07.2007', 'name' => 'Desa Maron'],
            ['district_code' => '35.05.07', 'code' => '35.05.07.2008', 'name' => 'Desa Pakisaji'],
            ['district_code' => '35.05.07', 'code' => '35.05.07.2009', 'name' => 'Desa Panggungduwet'],
            ['district_code' => '35.05.07', 'code' => '35.05.07.2010', 'name' => 'Desa Plosorejo'],
            ['district_code' => '35.05.07', 'code' => '35.05.07.2011', 'name' => 'Desa Plumpungrejo'],
            ['district_code' => '35.05.07', 'code' => '35.05.07.2012', 'name' => 'Desa Rejowinangun'],
            ['district_code' => '35.05.07', 'code' => '35.05.07.2013', 'name' => 'Desa Sumberjati'],
            ['district_code' => '35.05.07', 'code' => '35.05.07.2014', 'name' => 'Desa Sumberjo'],
            ['district_code' => '35.05.07', 'code' => '35.05.07.2015', 'name' => 'Desa Suruhwadang'],

            // Kanigoro (35.05.08) - 2 Kelurahan, 10 Desa
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
            ['district_code' => '35.05.08', 'code' => '35.05.08.2011', 'name' => 'Desa Banggle'],
            ['district_code' => '35.05.08', 'code' => '35.05.08.2012', 'name' => 'Desa Jatinom'],

            // Talun (35.05.09) - 4 Kelurahan, 10 Desa
            ['district_code' => '35.05.09', 'code' => '35.05.09.1001', 'name' => 'Kelurahan Talun'],
            ['district_code' => '35.05.09', 'code' => '35.05.09.1002', 'name' => 'Kelurahan Kamulan'],
            ['district_code' => '35.05.09', 'code' => '35.05.09.1003', 'name' => 'Kelurahan Bajang'],
            ['district_code' => '35.05.09', 'code' => '35.05.09.2004', 'name' => 'Desa Pasirharjo'],
            ['district_code' => '35.05.09', 'code' => '35.05.09.1005', 'name' => 'Kelurahan Kaweron'],
            ['district_code' => '35.05.09', 'code' => '35.05.09.2006', 'name' => 'Desa Bendosewu'],
            ['district_code' => '35.05.09', 'code' => '35.05.09.2007', 'name' => 'Desa Duren'],
            ['district_code' => '35.05.09', 'code' => '35.05.09.2008', 'name' => 'Desa Jabung'],
            ['district_code' => '35.05.09', 'code' => '35.05.09.2009', 'name' => 'Desa Jajar'],
            ['district_code' => '35.05.09', 'code' => '35.05.09.2010', 'name' => 'Desa Jeblog'],
            ['district_code' => '35.05.09', 'code' => '35.05.09.2011', 'name' => 'Desa Kendalrejo'],
            ['district_code' => '35.05.09', 'code' => '35.05.09.2012', 'name' => 'Desa Sragi'],
            ['district_code' => '35.05.09', 'code' => '35.05.09.2013', 'name' => 'Desa Tumpang'],
            ['district_code' => '35.05.09', 'code' => '35.05.09.2014', 'name' => 'Desa Wonorejo'],

            // Selopuro (35.05.10) - 8 Desa
            ['district_code' => '35.05.10', 'code' => '35.05.10.2001', 'name' => 'Desa Jambewangi'],
            ['district_code' => '35.05.10', 'code' => '35.05.10.2002', 'name' => 'Desa Jatitengah'],
            ['district_code' => '35.05.10', 'code' => '35.05.10.2003', 'name' => 'Desa Mandesan'],
            ['district_code' => '35.05.10', 'code' => '35.05.10.2004', 'name' => 'Desa Mronjo'],
            ['district_code' => '35.05.10', 'code' => '35.05.10.2005', 'name' => 'Desa Ploso'],
            ['district_code' => '35.05.10', 'code' => '35.05.10.2006', 'name' => 'Desa Popoh'],
            ['district_code' => '35.05.10', 'code' => '35.05.10.2007', 'name' => 'Desa Selopuro'],
            ['district_code' => '35.05.10', 'code' => '35.05.10.2008', 'name' => 'Desa Tegalrejo'],

            // Kesamben (35.05.11) - 10 Desa
            ['district_code' => '35.05.11', 'code' => '35.05.11.2001', 'name' => 'Desa Bumirejo'],
            ['district_code' => '35.05.11', 'code' => '35.05.11.2002', 'name' => 'Desa Jugo'],
            ['district_code' => '35.05.11', 'code' => '35.05.11.2003', 'name' => 'Desa Kemirigede'],
            ['district_code' => '35.05.11', 'code' => '35.05.11.2004', 'name' => 'Desa Kesamben'],
            ['district_code' => '35.05.11', 'code' => '35.05.11.2005', 'name' => 'Desa Pagergunung'],
            ['district_code' => '35.05.11', 'code' => '35.05.11.2006', 'name' => 'Desa Pagerwojo'],
            ['district_code' => '35.05.11', 'code' => '35.05.11.2007', 'name' => 'Desa Siraman'],
            ['district_code' => '35.05.11', 'code' => '35.05.11.2008', 'name' => 'Desa Sukoanyar'],
            ['district_code' => '35.05.11', 'code' => '35.05.11.2009', 'name' => 'Desa Tapakrejo'],
            ['district_code' => '35.05.11', 'code' => '35.05.11.2010', 'name' => 'Desa Tepas'],

            // Wlingi (35.05.12) - 5 Kelurahan, 4 Desa
            ['district_code' => '35.05.12', 'code' => '35.05.12.1001', 'name' => 'Kelurahan Wlingi'],
            ['district_code' => '35.05.12', 'code' => '35.05.12.1002', 'name' => 'Kelurahan Beru'],
            ['district_code' => '35.05.12', 'code' => '35.05.12.1003', 'name' => 'Kelurahan Babadan'],
            ['district_code' => '35.05.12', 'code' => '35.05.12.1004', 'name' => 'Kelurahan Klemunan'],
            ['district_code' => '35.05.12', 'code' => '35.05.12.1005', 'name' => 'Kelurahan Tangkil'],
            ['district_code' => '35.05.12', 'code' => '35.05.12.2006', 'name' => 'Desa Tegalasri'],
            ['district_code' => '35.05.12', 'code' => '35.05.12.2007', 'name' => 'Desa Tembalang'],
            ['district_code' => '35.05.12', 'code' => '35.05.12.2008', 'name' => 'Desa Ngadirenggo'],
            ['district_code' => '35.05.12', 'code' => '35.05.12.2009', 'name' => 'Desa Balerejo'],

            // Doko (35.05.13) - 10 Desa
            ['district_code' => '35.05.13', 'code' => '35.05.13.2001', 'name' => 'Desa Doko'],
            ['district_code' => '35.05.13', 'code' => '35.05.13.2002', 'name' => 'Desa Genengan'],
            ['district_code' => '35.05.13', 'code' => '35.05.13.2003', 'name' => 'Desa Jambepawon'],
            ['district_code' => '35.05.13', 'code' => '35.05.13.2004', 'name' => 'Desa Kalimanis'],
            ['district_code' => '35.05.13', 'code' => '35.05.13.2005', 'name' => 'Desa Plumbangan'],
            ['district_code' => '35.05.13', 'code' => '35.05.13.2006', 'name' => 'Desa Resapombo'],
            ['district_code' => '35.05.13', 'code' => '35.05.13.2007', 'name' => 'Desa Sidorejo'],
            ['district_code' => '35.05.13', 'code' => '35.05.13.2008', 'name' => 'Desa Slorok'],
            ['district_code' => '35.05.13', 'code' => '35.05.13.2009', 'name' => 'Desa Sumberurip'],
            ['district_code' => '35.05.13', 'code' => '35.05.13.2010', 'name' => 'Desa Suru'],

            // Gandusari (35.05.14) - 14 Desa
            ['district_code' => '35.05.14', 'code' => '35.05.14.2001', 'name' => 'Desa Butun'],
            ['district_code' => '35.05.14', 'code' => '35.05.14.2002', 'name' => 'Desa Gadungan'],
            ['district_code' => '35.05.14', 'code' => '35.05.14.2003', 'name' => 'Desa Gandusari'],
            ['district_code' => '35.05.14', 'code' => '35.05.14.2004', 'name' => 'Desa Gondang'],
            ['district_code' => '35.05.14', 'code' => '35.05.14.2005', 'name' => 'Desa Kotes'],
            ['district_code' => '35.05.14', 'code' => '35.05.14.2006', 'name' => 'Desa Krisik'],
            ['district_code' => '35.05.14', 'code' => '35.05.14.2007', 'name' => 'Desa Ngaringan'],
            ['district_code' => '35.05.14', 'code' => '35.05.14.2008', 'name' => 'Desa Semen'],
            ['district_code' => '35.05.14', 'code' => '35.05.14.2009', 'name' => 'Desa Slumbung'],
            ['district_code' => '35.05.14', 'code' => '35.05.14.2010', 'name' => 'Desa Soso'],
            ['district_code' => '35.05.14', 'code' => '35.05.14.2011', 'name' => 'Desa Sukosewu'],
            ['district_code' => '35.05.14', 'code' => '35.05.14.2012', 'name' => 'Desa Sumberagung'],
            ['district_code' => '35.05.14', 'code' => '35.05.14.2013', 'name' => 'Desa Tambakan'],
            ['district_code' => '35.05.14', 'code' => '35.05.14.2014', 'name' => 'Desa Tulungrejo'],

            // Garum (35.05.15) - 4 Kelurahan, 5 Desa
            ['district_code' => '35.05.15', 'code' => '35.05.15.1001', 'name' => 'Kelurahan Garum'],
            ['district_code' => '35.05.15', 'code' => '35.05.15.1002', 'name' => 'Kelurahan Tawangsari'],
            ['district_code' => '35.05.15', 'code' => '35.05.15.1003', 'name' => 'Kelurahan Bence'],
            ['district_code' => '35.05.15', 'code' => '35.05.15.1004', 'name' => 'Kelurahan Slorok'],
            ['district_code' => '35.05.15', 'code' => '35.05.15.2005', 'name' => 'Desa Pojok'],
            ['district_code' => '35.05.15', 'code' => '35.05.15.1006', 'name' => 'Kelurahan Sumberdiren'],
            ['district_code' => '35.05.15', 'code' => '35.05.15.2007', 'name' => 'Desa Karangrejo'],
            ['district_code' => '35.05.15', 'code' => '35.05.15.2008', 'name' => 'Desa Sidodadi'],
            ['district_code' => '35.05.15', 'code' => '35.05.15.2009', 'name' => 'Desa Tingal'],

            // Nglegok (35.05.16) - 1 Kelurahan, 10 Desa
            ['district_code' => '35.05.16', 'code' => '35.05.16.1001', 'name' => 'Kelurahan Nglegok'],
            ['district_code' => '35.05.16', 'code' => '35.05.16.2002', 'name' => 'Desa Jiwut'],
            ['district_code' => '35.05.16', 'code' => '35.05.16.2003', 'name' => 'Desa Modangan'],
            ['district_code' => '35.05.16', 'code' => '35.05.16.2004', 'name' => 'Desa Penataran'],
            ['district_code' => '35.05.16', 'code' => '35.05.16.2005', 'name' => 'Desa Bangsri'],
            ['district_code' => '35.05.16', 'code' => '35.05.16.2006', 'name' => 'Desa Dayu'],
            ['district_code' => '35.05.16', 'code' => '35.05.16.2007', 'name' => 'Desa Kedawung'],
            ['district_code' => '35.05.16', 'code' => '35.05.16.2008', 'name' => 'Desa Kemloko'],
            ['district_code' => '35.05.16', 'code' => '35.05.16.2009', 'name' => 'Desa Krenceng'],
            ['district_code' => '35.05.16', 'code' => '35.05.16.2010', 'name' => 'Desa Ngoran'],
            ['district_code' => '35.05.16', 'code' => '35.05.16.2011', 'name' => 'Desa Sumberasri'],

            // Sanankulon (35.05.17) - 12 Desa
            ['district_code' => '35.05.17', 'code' => '35.05.17.2001', 'name' => 'Desa Bendosari'],
            ['district_code' => '35.05.17', 'code' => '35.05.17.2002', 'name' => 'Desa Bendowulung'],
            ['district_code' => '35.05.17', 'code' => '35.05.17.2003', 'name' => 'Desa Gledug'],
            ['district_code' => '35.05.17', 'code' => '35.05.17.2004', 'name' => 'Desa Jeding'],
            ['district_code' => '35.05.17', 'code' => '35.05.17.2005', 'name' => 'Desa Kalipucung'],
            ['district_code' => '35.05.17', 'code' => '35.05.17.2006', 'name' => 'Desa Plosoarang'],
            ['district_code' => '35.05.17', 'code' => '35.05.17.2007', 'name' => 'Desa Purworejo'],
            ['district_code' => '35.05.17', 'code' => '35.05.17.2008', 'name' => 'Desa Sanankulon'],
            ['district_code' => '35.05.17', 'code' => '35.05.17.2009', 'name' => 'Desa Sumber'],
            ['district_code' => '35.05.17', 'code' => '35.05.17.2010', 'name' => 'Desa Sumberingin'],
            ['district_code' => '35.05.17', 'code' => '35.05.17.2011', 'name' => 'Desa Sumberjo'],
            ['district_code' => '35.05.17', 'code' => '35.05.17.2012', 'name' => 'Desa Tuliskriyo'],

            // Ponggok (35.05.18) - 15 Desa
            ['district_code' => '35.05.18', 'code' => '35.05.18.2001', 'name' => 'Desa Bacem'],
            ['district_code' => '35.05.18', 'code' => '35.05.18.2002', 'name' => 'Desa Bendo'],
            ['district_code' => '35.05.18', 'code' => '35.05.18.2003', 'name' => 'Desa Candirejo'],
            ['district_code' => '35.05.18', 'code' => '35.05.18.2004', 'name' => 'Desa Dadaplangu'],
            ['district_code' => '35.05.18', 'code' => '35.05.18.2005', 'name' => 'Desa Gembongan'],
            ['district_code' => '35.05.18', 'code' => '35.05.18.2006', 'name' => 'Desa Jatilengger'],
            ['district_code' => '35.05.18', 'code' => '35.05.18.2007', 'name' => 'Desa Karangbendo'],
            ['district_code' => '35.05.18', 'code' => '35.05.18.2008', 'name' => 'Desa Kawedusan'],
            ['district_code' => '35.05.18', 'code' => '35.05.18.2009', 'name' => 'Desa Kebonduren'],
            ['district_code' => '35.05.18', 'code' => '35.05.18.2010', 'name' => 'Desa Langon'],
            ['district_code' => '35.05.18', 'code' => '35.05.18.2011', 'name' => 'Desa Maliran'],
            ['district_code' => '35.05.18', 'code' => '35.05.18.2012', 'name' => 'Desa Pojok'],
            ['district_code' => '35.05.18', 'code' => '35.05.18.2013', 'name' => 'Desa Ponggok'],
            ['district_code' => '35.05.18', 'code' => '35.05.18.2014', 'name' => 'Desa Ringinanyar'],
            ['district_code' => '35.05.18', 'code' => '35.05.18.2015', 'name' => 'Desa Sidorejo'],

            // Srengat (35.05.19) - 4 Kelurahan, 12 Desa
            ['district_code' => '35.05.19', 'code' => '35.05.19.1001', 'name' => 'Kelurahan Srengat'],
            ['district_code' => '35.05.19', 'code' => '35.05.19.1002', 'name' => 'Kelurahan Kauman'],
            ['district_code' => '35.05.19', 'code' => '35.05.19.1003', 'name' => 'Kelurahan Dandong'],
            ['district_code' => '35.05.19', 'code' => '35.05.19.1004', 'name' => 'Kelurahan Togogan'],
            ['district_code' => '35.05.19', 'code' => '35.05.19.2005', 'name' => 'Desa Karanggayam'],
            ['district_code' => '35.05.19', 'code' => '35.05.19.2006', 'name' => 'Desa Purwokerto'],
            ['district_code' => '35.05.19', 'code' => '35.05.19.2007', 'name' => 'Desa Bagelenan'],
            ['district_code' => '35.05.19', 'code' => '35.05.19.2008', 'name' => 'Desa Dermojayan'],
            ['district_code' => '35.05.19', 'code' => '35.05.19.2009', 'name' => 'Desa Kandangan'],
            ['district_code' => '35.05.19', 'code' => '35.05.19.2010', 'name' => 'Desa Kendalrejo'],
            ['district_code' => '35.05.19', 'code' => '35.05.19.2011', 'name' => 'Desa Kerjen'],
            ['district_code' => '35.05.19', 'code' => '35.05.19.2012', 'name' => 'Desa Maron'],
            ['district_code' => '35.05.19', 'code' => '35.05.19.2013', 'name' => 'Desa Ngaglik'],
            ['district_code' => '35.05.19', 'code' => '35.05.19.2014', 'name' => 'Desa Pakisrejo'],
            ['district_code' => '35.05.19', 'code' => '35.05.19.2015', 'name' => 'Desa Selokajang'],
            ['district_code' => '35.05.19', 'code' => '35.05.19.2016', 'name' => 'Desa Wonorejo'],

            // Wonodadi (35.05.20) - 11 Desa
            ['district_code' => '35.05.20', 'code' => '35.05.20.2001', 'name' => 'Desa Gandekan'],
            ['district_code' => '35.05.20', 'code' => '35.05.20.2002', 'name' => 'Desa Jaten'],
            ['district_code' => '35.05.20', 'code' => '35.05.20.2003', 'name' => 'Desa Kaliboto'],
            ['district_code' => '35.05.20', 'code' => '35.05.20.2004', 'name' => 'Desa Kebonagung'],
            ['district_code' => '35.05.20', 'code' => '35.05.20.2005', 'name' => 'Desa Kolomayan'],
            ['district_code' => '35.05.20', 'code' => '35.05.20.2006', 'name' => 'Desa Kunir'],
            ['district_code' => '35.05.20', 'code' => '35.05.20.2007', 'name' => 'Desa Pikatan'],
            ['district_code' => '35.05.20', 'code' => '35.05.20.2008', 'name' => 'Desa Rejosari'],
            ['district_code' => '35.05.20', 'code' => '35.05.20.2009', 'name' => 'Desa Salam'],
            ['district_code' => '35.05.20', 'code' => '35.05.20.2010', 'name' => 'Desa Tawangrejo'],
            ['district_code' => '35.05.20', 'code' => '35.05.20.2011', 'name' => 'Desa Wonodadi'],

            // Udanawu (35.05.21) - 12 Desa
            ['district_code' => '35.05.21', 'code' => '35.05.21.2001', 'name' => 'Desa Bakung'],
            ['district_code' => '35.05.21', 'code' => '35.05.21.2002', 'name' => 'Desa Bendorejo'],
            ['district_code' => '35.05.21', 'code' => '35.05.21.2003', 'name' => 'Desa Besuki'],
            ['district_code' => '35.05.21', 'code' => '35.05.21.2004', 'name' => 'Desa Jati'],
            ['district_code' => '35.05.21', 'code' => '35.05.21.2005', 'name' => 'Desa Karanggondang'],
            ['district_code' => '35.05.21', 'code' => '35.05.21.2006', 'name' => 'Desa Mangunan'],
            ['district_code' => '35.05.21', 'code' => '35.05.21.2007', 'name' => 'Desa Ringinanom'],
            ['district_code' => '35.05.21', 'code' => '35.05.21.2008', 'name' => 'Desa Slemanan'],
            ['district_code' => '35.05.21', 'code' => '35.05.21.2009', 'name' => 'Desa Sukorejo'],
            ['district_code' => '35.05.21', 'code' => '35.05.21.2010', 'name' => 'Desa Sumbersari'],
            ['district_code' => '35.05.21', 'code' => '35.05.21.2011', 'name' => 'Desa Temenggungan'],
            ['district_code' => '35.05.21', 'code' => '35.05.21.2012', 'name' => 'Desa Tunjung'],

            // Selorejo (35.05.22) - 10 Desa
            ['district_code' => '35.05.22', 'code' => '35.05.22.2001', 'name' => 'Desa Ampelgading'],
            ['district_code' => '35.05.22', 'code' => '35.05.22.2002', 'name' => 'Desa Banjarsari'],
            ['district_code' => '35.05.22', 'code' => '35.05.22.2003', 'name' => 'Desa Boro'],
            ['district_code' => '35.05.22', 'code' => '35.05.22.2004', 'name' => 'Desa Ngreco'],
            ['district_code' => '35.05.22', 'code' => '35.05.22.2005', 'name' => 'Desa Ngrendeng'],
            ['district_code' => '35.05.22', 'code' => '35.05.22.2006', 'name' => 'Desa Olak-Alen'],
            ['district_code' => '35.05.22', 'code' => '35.05.22.2007', 'name' => 'Desa Pohgajih'],
            ['district_code' => '35.05.22', 'code' => '35.05.22.2008', 'name' => 'Desa Selorejo'],
            ['district_code' => '35.05.22', 'code' => '35.05.22.2009', 'name' => 'Desa Sidomulyo'],
            ['district_code' => '35.05.22', 'code' => '35.05.22.2010', 'name' => 'Desa Sumberagung'],
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
