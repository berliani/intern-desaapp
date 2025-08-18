<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Penduduk;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat permission dengan pendekatan manage resource
        $permissions = [
            'manage users',
            'manage profil_desa',
            'manage penduduk',
            'import penduduk',
            'export penduduk',
            'restore penduduk',
            'manage bansos',
            'restore bansos',
            'manage berita',
            'restore berita',
            'manage keuangan',
            'restore keuangan',
            'manage inventaris',
            'restore inventaris',
            'manage pengaduan',
            'respond pengaduan',
            'create pengaduan',
            'manage umkm',
            'restore umkm',
            'approve umkm',
            'manage layanan',
            'restore layanan',
            'submit verifikasi data',
            'approve verifikasi data',
            'manage verifikasi data',
            'view trash',
            'force delete',
            // Permission baru untuk warga
            'view own profile',
            'view own bansos',
            'apply for bansos',
            'view own pengaduan',
            'track layanan status',
            // Permission untuk verifikasi
            'view verification status',
            'edit verification data',
            'cancel verification',
            'view profil_desa',
        ];

        // Buat permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 1. Super Admin - mendapatkan semua permission
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdminRole->syncPermissions($permissions);

        // 2. Admin Desa - mendapatkan sebagian besar permission kecuali beberapa
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminPermissions = [
            'manage users',
            'manage profil_desa',
            'manage penduduk',
            'restore penduduk',
            'import penduduk',
            'export penduduk',
            'manage bansos',
            'restore bansos',
            'manage berita',
            'restore berita',
            'manage keuangan',
            'restore keuangan',
            'manage inventaris',
            'restore inventaris',
            'manage pengaduan',
            'respond pengaduan',
            'approve umkm',
            'restore umkm',
            'manage layanan',
            'restore layanan',
            'approve verifikasi data',
            'view trash',
        ];
        $adminRole->syncPermissions($adminPermissions);

        // 3. Warga - mendapatkan permission terbatas
        $wargaRole = Role::firstOrCreate(['name' => 'warga']);
        $wargaPermissions = [
            'create pengaduan',
            'manage umkm',
            'submit verifikasi data',
            'view profil_desa',
            'view own profile',
            'view own bansos',
            'apply for bansos',
            'view own pengaduan',
            'track layanan status',
        ];
        $wargaRole->syncPermissions($wargaPermissions);

        // 4. Unverified - hanya bisa submit verifikasi data
        $unverifiedRole = Role::firstOrCreate(['name' => 'unverified']);
        $unverifiedPermissions = [
            'submit verifikasi data',
            'view profil_desa',
            'view verification status',
            'edit verification data',
            'cancel verification',
        ];
        $unverifiedRole->syncPermissions($unverifiedPermissions);

        // Buat user Super Admin pertama
        User::firstOrCreate(
            ['email' => 'admin@desaku.com'],
            [
                'name' => 'Kepala Desa',
                'password' => Hash::make('password')
            ]
        )->assignRole($superAdminRole);

        // Buat user Admin Desa
        User::firstOrCreate(
            ['email' => 'admin_desa@desaku.com'],
            [
                'name' => 'Admin Desa',
                'password' => Hash::make('password')
            ]
        )->assignRole($adminRole);

        // Buat beberapa user warga contoh
        $penduduk = Penduduk::inRandomOrder()->limit(2)->get();
        foreach ($penduduk as $p) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace(' ', '', $p->nama)) . '@desaku.com'],
                [
                    'name' => $p->nama,
                    'password' => Hash::make('password'),
                    'penduduk_id' => $p->id,
                    'nik' => $p->nik
                ]
            );
            $user->assignRole($wargaRole);
        }

        // Buat user unverified (belum terverifikasi)
        User::firstOrCreate(
            ['email' => 'new_user@desaku.com'],
            [
                'name' => 'User Baru',
                'password' => Hash::make('password')
            ]
        )->assignRole($unverifiedRole);
    }
}