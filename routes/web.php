<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AkademikController;
use App\Http\Controllers\DataPenggunaController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\Settings;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('settings/profile', [Settings\ProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::put('settings/profile', [Settings\ProfileController::class, 'update'])->name('settings.profile.update');
    Route::delete('settings/profile', [Settings\ProfileController::class, 'destroy'])->name('settings.profile.destroy');
    Route::get('settings/password', [Settings\PasswordController::class, 'edit'])->name('settings.password.edit');
    Route::put('settings/password', [Settings\PasswordController::class, 'update'])->name('settings.password.update');
    Route::get('settings/appearance', [Settings\AppearanceController::class, 'edit'])->name('settings.appearance.edit');
    Route::put('settings/appearance', [Settings\AppearanceController::class, 'update'])->name('settings.appearance.update');

    // Roles Management - dengan permission check
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index')->middleware('permission:view-roles');
    Route::get('roles/export', [RoleController::class, 'export'])->name('roles.export')->middleware('permission:download-roles');
    Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create')->middleware('permission:create-roles');
    Route::post('roles', [RoleController::class, 'store'])->name('roles.store')->middleware('permission:create-roles');
    Route::get('roles/{role}', [RoleController::class, 'show'])->name('roles.show')->middleware('permission:show-roles');
    Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit')->middleware('permission:edit-roles');
    Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update')->middleware('permission:edit-roles');
    Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy')->middleware('permission:delete-roles');
    
    // Permissions Management - dengan permission check
    Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index')->middleware('permission:view-permissions');
    Route::get('permissions/export', [PermissionController::class, 'export'])->name('permissions.export')->middleware('permission:download-permissions');
    Route::get('permissions/create', [PermissionController::class, 'create'])->name('permissions.create')->middleware('permission:create-permissions');
    Route::post('permissions', [PermissionController::class, 'store'])->name('permissions.store')->middleware('permission:create-permissions');
    Route::get('permissions/{permission}', [PermissionController::class, 'show'])->name('permissions.show')->middleware('permission:show-permissions');
    Route::get('permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit')->middleware('permission:edit-permissions');
    Route::put('permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update')->middleware('permission:edit-permissions');
    Route::delete('permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy')->middleware('permission:delete-permissions');
    
    // Users Management - dengan permission check
    Route::get('users', [UserController::class, 'index'])->name('users.index')->middleware('permission:view-users');
    Route::get('users/export', [UserController::class, 'export'])->name('users.export')->middleware('permission:download-users');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create')->middleware('permission:create-users');
    Route::post('users', [UserController::class, 'store'])->name('users.store')->middleware('permission:create-users');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show')->middleware('permission:show-users');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('permission:edit-users');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('permission:edit-users');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('permission:delete-users');

    // Data Pengguna Sekolah
    Route::get('data-pengguna/pegawai', [DataPenggunaController::class, 'pegawaiIndex'])
        ->name('data-pengguna.pegawai.index')
        ->middleware('permission:view-pegawais');
    Route::get('data-pengguna/pegawai/create', [DataPenggunaController::class, 'pegawaiCreate'])
        ->name('data-pengguna.pegawai.create')
        ->middleware('permission:create-pegawais');
    Route::post('data-pengguna/pegawai', [DataPenggunaController::class, 'storePegawai'])
        ->name('data-pengguna.pegawai.store')
        ->middleware('permission:create-pegawais');
    Route::get('data-pengguna/pegawai/{pegawai}/edit', [DataPenggunaController::class, 'pegawaiEdit'])
        ->name('data-pengguna.pegawai.edit')
        ->middleware('permission:edit-pegawais');
    Route::put('data-pengguna/pegawai/{pegawai}', [DataPenggunaController::class, 'pegawaiUpdate'])
        ->name('data-pengguna.pegawai.update')
        ->middleware('permission:edit-pegawais');
    Route::delete('data-pengguna/pegawai/{pegawai}', [DataPenggunaController::class, 'pegawaiDestroy'])
        ->name('data-pengguna.pegawai.destroy')
        ->middleware('permission:delete-pegawais');

    Route::get('data-pengguna/guru', [DataPenggunaController::class, 'guruIndex'])
        ->name('data-pengguna.guru.index')
        ->middleware('permission:view-gurus');
    Route::get('data-pengguna/guru/create', [DataPenggunaController::class, 'guruCreate'])
        ->name('data-pengguna.guru.create')
        ->middleware('permission:create-gurus');
    Route::post('data-pengguna/guru', [DataPenggunaController::class, 'storeGuru'])
        ->name('data-pengguna.guru.store')
        ->middleware('permission:create-gurus');
    Route::get('data-pengguna/guru/{guru}/edit', [DataPenggunaController::class, 'guruEdit'])
        ->name('data-pengguna.guru.edit')
        ->middleware('permission:edit-gurus');
    Route::put('data-pengguna/guru/{guru}', [DataPenggunaController::class, 'guruUpdate'])
        ->name('data-pengguna.guru.update')
        ->middleware('permission:edit-gurus');
    Route::delete('data-pengguna/guru/{guru}', [DataPenggunaController::class, 'guruDestroy'])
        ->name('data-pengguna.guru.destroy')
        ->middleware('permission:delete-gurus');

    Route::get('data-pengguna/siswa', [DataPenggunaController::class, 'siswaIndex'])
        ->name('data-pengguna.siswa.index')
        ->middleware('permission:view-siswas');
    Route::get('data-pengguna/siswa/create', [DataPenggunaController::class, 'siswaCreate'])
        ->name('data-pengguna.siswa.create')
        ->middleware('permission:create-siswas');
    Route::post('data-pengguna/siswa', [DataPenggunaController::class, 'storeSiswa'])
        ->name('data-pengguna.siswa.store')
        ->middleware('permission:create-siswas');
    Route::get('data-pengguna/siswa/{siswa}/edit', [DataPenggunaController::class, 'siswaEdit'])
        ->name('data-pengguna.siswa.edit')
        ->middleware('permission:edit-siswas');
    Route::put('data-pengguna/siswa/{siswa}', [DataPenggunaController::class, 'siswaUpdate'])
        ->name('data-pengguna.siswa.update')
        ->middleware('permission:edit-siswas');
    Route::delete('data-pengguna/siswa/{siswa}', [DataPenggunaController::class, 'siswaDestroy'])
        ->name('data-pengguna.siswa.destroy')
        ->middleware('permission:delete-siswas');

    // Master Data Sekolah
    Route::get('master-data/tingkat', [MasterDataController::class, 'tingkatIndex'])
        ->name('master-data.tingkat.index')
        ->middleware('permission:view-tingkats');
    Route::get('master-data/tingkat/create', [MasterDataController::class, 'tingkatCreate'])
        ->name('master-data.tingkat.create')
        ->middleware('permission:create-tingkats');
    Route::get('master-data/tingkat/{tingkat}/edit', [MasterDataController::class, 'tingkatEdit'])
        ->name('master-data.tingkat.edit')
        ->middleware('permission:edit-tingkats');
    Route::put('master-data/tingkat/{tingkat}', [MasterDataController::class, 'tingkatUpdate'])
        ->name('master-data.tingkat.update')
        ->middleware('permission:edit-tingkats');
    Route::delete('master-data/tingkat/{tingkat}', [MasterDataController::class, 'tingkatDestroy'])
        ->name('master-data.tingkat.destroy')
        ->middleware('permission:delete-tingkats');

    Route::get('master-data/kelas', [MasterDataController::class, 'kelasIndex'])
        ->name('master-data.kelas.index')
        ->middleware('permission:view-kelas');
    Route::get('master-data/kelas/create', [MasterDataController::class, 'kelasCreate'])
        ->name('master-data.kelas.create')
        ->middleware('permission:create-kelas');
    Route::get('master-data/kelas/{kelas}/edit', [MasterDataController::class, 'kelasEdit'])
        ->name('master-data.kelas.edit')
        ->middleware('permission:edit-kelas');
    Route::put('master-data/kelas/{kelas}', [MasterDataController::class, 'kelasUpdate'])
        ->name('master-data.kelas.update')
        ->middleware('permission:edit-kelas');
    Route::delete('master-data/kelas/{kelas}', [MasterDataController::class, 'kelasDestroy'])
        ->name('master-data.kelas.destroy')
        ->middleware('permission:delete-kelas');

    Route::get('master-data/tahun-ajaran', [MasterDataController::class, 'tahunAjaranIndex'])
        ->name('master-data.tahun-ajaran.index')
        ->middleware('permission:view-tahun-ajarans');
    Route::get('master-data/tahun-ajaran/create', [MasterDataController::class, 'tahunAjaranCreate'])
        ->name('master-data.tahun-ajaran.create')
        ->middleware('permission:create-tahun-ajarans');
    Route::get('master-data/tahun-ajaran/{tahunAjaran}/edit', [MasterDataController::class, 'tahunAjaranEdit'])
        ->name('master-data.tahun-ajaran.edit')
        ->middleware('permission:edit-tahun-ajarans');
    Route::put('master-data/tahun-ajaran/{tahunAjaran}', [MasterDataController::class, 'tahunAjaranUpdate'])
        ->name('master-data.tahun-ajaran.update')
        ->middleware('permission:edit-tahun-ajarans');
    Route::delete('master-data/tahun-ajaran/{tahunAjaran}', [MasterDataController::class, 'tahunAjaranDestroy'])
        ->name('master-data.tahun-ajaran.destroy')
        ->middleware('permission:delete-tahun-ajarans');
    Route::post('master-data/tahun-ajaran', [MasterDataController::class, 'storeTahunAjaran'])
        ->name('master-data.tahun-ajaran.store')
        ->middleware('permission:create-tahun-ajarans');

    Route::get('master-data/mata-pelajaran', [MasterDataController::class, 'mataPelajaranIndex'])
        ->name('master-data.mapel.index')
        ->middleware('permission:view-mata-pelajarans');
    Route::get('master-data/mata-pelajaran/create', [MasterDataController::class, 'mataPelajaranCreate'])
        ->name('master-data.mapel.create')
        ->middleware('permission:create-mata-pelajarans');
    Route::get('master-data/mata-pelajaran/{mataPelajaran}/edit', [MasterDataController::class, 'mataPelajaranEdit'])
        ->name('master-data.mapel.edit')
        ->middleware('permission:edit-mata-pelajarans');
    Route::put('master-data/mata-pelajaran/{mataPelajaran}', [MasterDataController::class, 'mataPelajaranUpdate'])
        ->name('master-data.mapel.update')
        ->middleware('permission:edit-mata-pelajarans');
    Route::delete('master-data/mata-pelajaran/{mataPelajaran}', [MasterDataController::class, 'mataPelajaranDestroy'])
        ->name('master-data.mapel.destroy')
        ->middleware('permission:delete-mata-pelajarans');

    Route::post('master-data/tingkat', [MasterDataController::class, 'storeTingkat'])
        ->name('master-data.tingkat.store')
        ->middleware('permission:create-tingkats');
    Route::post('master-data/kelas', [MasterDataController::class, 'storeKelas'])
        ->name('master-data.kelas.store')
        ->middleware('permission:create-kelas');
    Route::post('master-data/mapel', [MasterDataController::class, 'storeMataPelajaran'])
        ->name('master-data.mapel.store')
        ->middleware('permission:create-mata-pelajarans');
    Route::get('penjadwalan', [MasterDataController::class, 'penjadwalanIndex'])
        ->name('master-data.penjadwalan.index')
        ->middleware('permission:view-penjadwalan');
    Route::get('penjadwalan/create', [MasterDataController::class, 'jadwalCreate'])
        ->name('master-data.penjadwalan.create')
        ->middleware('permission:create-jadwal');
    Route::get('penjadwalan/{jadwal}/edit', [MasterDataController::class, 'jadwalEdit'])
        ->name('master-data.penjadwalan.edit')
        ->middleware('permission:edit-jadwal');
    Route::put('penjadwalan/{jadwal}', [MasterDataController::class, 'jadwalUpdate'])
        ->name('master-data.penjadwalan.update')
        ->middleware('permission:edit-jadwal');
    Route::delete('penjadwalan/{jadwal}', [MasterDataController::class, 'jadwalDestroy'])
        ->name('master-data.penjadwalan.destroy')
        ->middleware('permission:delete-jadwal');
    Route::post('master-data/jadwal', [MasterDataController::class, 'storeJadwal'])
        ->name('master-data.jadwal.store')
        ->middleware('permission:create-jadwal');

    // Akademik (akses: guru atau admin lihat semua — dicek di controller)
    Route::get('akademik/jadwal-guru', [AkademikController::class, 'jadwalGuru'])
        ->name('akademik.jadwal-guru');

    Route::get('akademik/jadwal/{jadwal}/nilai', [AkademikController::class, 'formNilai'])
        ->name('akademik.nilai.form')
        ->middleware('permission:input-nilai');
    Route::put('akademik/jadwal/{jadwal}/nilai', [AkademikController::class, 'simpanNilai'])
        ->name('akademik.nilai.simpan')
        ->middleware('permission:input-nilai');

    Route::get('akademik/jadwal/{jadwal}/absensi', [AkademikController::class, 'formAbsensi'])
        ->name('akademik.absensi.form')
        ->middleware('permission:input-absensi');
    Route::put('akademik/jadwal/{jadwal}/absensi', [AkademikController::class, 'simpanAbsensi'])
        ->name('akademik.absensi.simpan')
        ->middleware('permission:input-absensi');

    Route::get('akademik/jadwal/{jadwal}/rekap-absensi', [AkademikController::class, 'rekapAbsensi'])
        ->name('akademik.rekap-absensi')
        ->middleware('permission:input-absensi');

    Route::get('akademik/rekap-wali', [AkademikController::class, 'rekapWali'])
        ->name('akademik.rekap-wali')
        ->middleware('permission:view-rekap-wali');
});

require __DIR__.'/auth.php';
