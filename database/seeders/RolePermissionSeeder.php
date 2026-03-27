<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\Pegawai;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'view-users',
            'show-users',
            'create-users',
            'edit-users',
            'download-users',
            'delete-users',
            'view-roles',
            'show-roles',
            'create-roles',
            'edit-roles',
            'download-roles',
            'delete-roles',
            'view-permissions',
            'show-permissions',
            'create-permissions',
            'edit-permissions',
            'download-permissions',
            'delete-permissions',
            'view-tingkats',
            'create-tingkats',
            'edit-tingkats',
            'delete-tingkats',
            'view-kelas',
            'create-kelas',
            'edit-kelas',
            'delete-kelas',
            'view-mata-pelajarans',
            'create-mata-pelajarans',
            'edit-mata-pelajarans',
            'delete-mata-pelajarans',
            'view-pegawais',
            'create-pegawais',
            'edit-pegawais',
            'delete-pegawais',
            'view-gurus',
            'create-gurus',
            'edit-gurus',
            'delete-gurus',
            'view-siswas',
            'create-siswas',
            'edit-siswas',
            'delete-siswas',
            'view-jadwal',
            'view-jadwal-mengajar',
            'create-jadwal',
            'edit-jadwal',
            'delete-jadwal',
            'view-penjadwalan',
            'view-tahun-ajarans',
            'create-tahun-ajarans',
            'edit-tahun-ajarans',
            'delete-tahun-ajarans',
            'view-jadwal-mengajar-semua',
            'input-nilai',
            'input-absensi',
            'view-rekap-wali',
            'view-jadwal-siswa',
            'view-nilai-siswa',
            'view-absensi-siswa',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $adminSistemRole = Role::firstOrCreate(['name' => 'admin-sistem']);
        $pimpinanRole = Role::firstOrCreate(['name' => 'pimpinan']);
        $pegawaiRole = Role::firstOrCreate(['name' => 'pegawai']);
        $guruRole = Role::firstOrCreate(['name' => 'guru']);
        $siswaRole = Role::firstOrCreate(['name' => 'siswa']);

        // Hapus role lama yang tidak dipakai lagi.
        Role::whereIn('name', ['admin', 'editor', 'user'])->get()->each->delete();

        $manajemenAksesPermissions = Permission::whereIn('name', [
            'view-users', 'show-users', 'create-users', 'edit-users', 'download-users', 'delete-users',
            'view-roles', 'show-roles', 'create-roles', 'edit-roles', 'download-roles', 'delete-roles',
            'view-permissions', 'show-permissions', 'create-permissions', 'edit-permissions', 'download-permissions', 'delete-permissions',
        ])->pluck('id');

        $penggunaPermissions = Permission::whereIn('name', [
            'view-pegawais', 'create-pegawais', 'edit-pegawais', 'delete-pegawais',
            'view-gurus', 'create-gurus', 'edit-gurus', 'delete-gurus',
            'view-siswas', 'create-siswas', 'edit-siswas', 'delete-siswas',
        ])->pluck('id');

        $penjadwalanPermissions = Permission::whereIn('name', [
            'view-tingkats', 'create-tingkats', 'edit-tingkats', 'delete-tingkats',
            'view-kelas', 'create-kelas', 'edit-kelas', 'delete-kelas',
            'view-mata-pelajarans', 'create-mata-pelajarans', 'edit-mata-pelajarans', 'delete-mata-pelajarans',
            'view-penjadwalan', 'view-jadwal', 'create-jadwal', 'edit-jadwal', 'delete-jadwal',
            'view-tahun-ajarans', 'create-tahun-ajarans', 'edit-tahun-ajarans', 'delete-tahun-ajarans',
            'view-jadwal-mengajar-semua',
        ])->pluck('id');

        $akademikPermissions = Permission::whereIn('name', [
            'view-jadwal-mengajar',
            'input-nilai',
            'input-absensi',
            'view-rekap-wali',
        ])->pluck('id');

        $siswaAkademikPermissions = Permission::whereIn('name', [
            'view-jadwal-siswa',
            'view-nilai-siswa',
            'view-absensi-siswa',
        ])->pluck('id');

        $superAdminRole->permissions()->sync(Permission::all()->pluck('id')->all());
        $adminSistemRole->permissions()->sync(
            $manajemenAksesPermissions
                ->merge($penggunaPermissions)
                ->merge($penjadwalanPermissions)
                ->merge($akademikPermissions)
                ->unique()
                ->values()
                ->all()
        );
        $pegawaiRole->permissions()->sync(
            $penggunaPermissions
                ->merge($penjadwalanPermissions)
                ->merge($akademikPermissions)
                ->unique()
                ->values()
                ->all()
        );
        $guruRole->permissions()->sync($akademikPermissions->values()->all());
        $pimpinanRole->permissions()->sync([]);
        $siswaRole->permissions()->sync($siswaAkademikPermissions->values()->all());

        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );

        $superAdmin->roles()->sync([$superAdminRole->id]);

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Sistem',
                'password' => Hash::make('password'),
            ]
        );

        $admin->roles()->sync([$adminSistemRole->id]);

        // Hubungkan user login dengan role sesuai data master.
        // RolePermissionSeeder saja tidak mengisi role_user untuk guru/siswa/pegawai.
        foreach (Guru::query()->with('user')->get() as $guru) {
            if ($guru->user) {
                $guru->user->roles()->sync([$guruRole->id]);
            }
        }

        foreach (Siswa::query()->with('user')->get() as $siswa) {
            if ($siswa->user) {
                $siswa->user->roles()->sync([$siswaRole->id]);
            }
        }

        foreach (Pegawai::query()->with('user')->get() as $pegawai) {
            if (! $pegawai->user) {
                continue;
            }
            if (Guru::where('user_id', $pegawai->user_id)->exists()) {
                continue;
            }
            $pegawai->user->roles()->sync([$pegawaiRole->id]);
        }
    }
}
