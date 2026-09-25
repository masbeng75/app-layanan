<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'administrator',
            'pimpinan',
            'pejabat_penandatangan',
            'petugas_dinsos',
            'operator_kecamatan_desa',
            'warga',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // Assign roles to users seeded
        $admin = User::where('email', 'admin@dinsos.blitarkab.go.id')->first();
        $admin?->assignRole('administrator');

        $kadis = User::where('email', 'kadis@dinsos.blitarkab.go.id')->first();
        $kadis?->assignRole(['pimpinan', 'pejabat_penandatangan']);

        $kabids = User::where('email', 'like', 'kabid.%')->get();
        foreach ($kabids as $kabid) {
            $kabid->assignRole('pejabat_penandatangan');
        }

        $petugas = User::where('email', 'like', 'petugas.%')->get();
        foreach ($petugas as $p) {
            $p->assignRole('petugas_dinsos');
        }

        $operators = User::where('email', 'like', 'operator.%')->get();
        foreach ($operators as $op) {
            $op->assignRole('operator_kecamatan_desa');
        }

        $wargas = User::whereIn('email', [
            'siti.aminah@gmail.com',
            'bambang.wijaya@gmail.com',
            'agus.santoso@gmail.com',
        ])->get();
        foreach ($wargas as $w) {
            $w->assignRole('warga');
        }
    }
}
