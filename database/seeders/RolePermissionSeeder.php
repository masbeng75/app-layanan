<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'dashboard.view',
            'reports.view',
            'service_requests.view_any',
            'service_requests.view',
            'service_requests.create',
            'service_requests.update',
            'service_requests.delete',
            'complaints.view_any',
            'complaints.view',
            'complaints.create',
            'complaints.update',
            'complaints.delete',
            'rehabilitation_cases.view_any',
            'rehabilitation_cases.view',
            'rehabilitation_cases.create',
            'rehabilitation_cases.update',
            'rehabilitation_cases.delete',
            'clients.view_any',
            'clients.view',
            'clients.create',
            'clients.update',
            'clients.delete',
            'information_pages.manage',
            'master_data.manage',
            'users.manage',
            'audit_logs.view',
            'approvals.decide',
            'public.access',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        $roles = [
            'administrator' => $permissions,
            'pimpinan' => [
                'dashboard.view',
                'reports.view',
                'service_requests.view_any',
                'service_requests.view',
                'complaints.view_any',
                'complaints.view',
                'rehabilitation_cases.view_any',
                'rehabilitation_cases.view',
                'clients.view_any',
                'clients.view',
            ],
            'pejabat_penandatangan' => [
                'dashboard.view',
                'reports.view',
                'service_requests.view_any',
                'service_requests.view',
                'approvals.decide',
            ],
            'petugas_dinsos' => [
                'dashboard.view',
                'reports.view',
                'service_requests.view_any',
                'service_requests.view',
                'service_requests.create',
                'service_requests.update',
                'complaints.view_any',
                'complaints.view',
                'complaints.create',
                'complaints.update',
                'rehabilitation_cases.view_any',
                'rehabilitation_cases.view',
                'rehabilitation_cases.create',
                'rehabilitation_cases.update',
                'clients.view_any',
                'clients.view',
                'clients.create',
                'clients.update',
                'information_pages.manage',
            ],
            'operator_kecamatan_desa' => [
                'dashboard.view',
                'service_requests.view_any',
                'service_requests.view',
                'service_requests.create',
                'service_requests.update',
                'complaints.view_any',
                'complaints.view',
                'complaints.create',
                'complaints.update',
            ],
            'warga' => [
                'public.access',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($rolePermissions);
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
