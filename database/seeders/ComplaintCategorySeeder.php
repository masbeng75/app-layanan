<?php

namespace Database\Seeders;

use App\Models\ComplaintCategory;
use Illuminate\Database\Seeder;

class ComplaintCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Pemerlu Pelayanan Kesejahteraan Sosial (PPKS) Terlantar di Tempat Umum',
            'Penanganan Kedaruratan ODGJ Mengamuk / Meresahkan',
            'Bantuan Sosial (PKH/BPNT/BLT) Tidak Tepat Sasaran',
            'Dugaan Pungutan Liar / Pemotongan Dana Bantuan Sosial',
            'Lansia Sebatang Kara / Disabilitas Terlantar Butuh Evakuasi Darurat',
            'Penelantaran Anak & Kekerasan Sosial Terhadap Perempuan / Anak',
            'Pengaduan Pelayanan Sosial Lainnya',
        ];

        foreach ($categories as $cat) {
            ComplaintCategory::firstOrCreate(
                ['name' => $cat],
                ['is_active' => true]
            );
        }
    }
}
