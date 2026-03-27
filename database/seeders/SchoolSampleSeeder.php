<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\Tingkat;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SchoolSampleSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan role & permission sudah ada (guru, pegawai, siswa, dll).
        $this->call(RolePermissionSeeder::class);

        DB::transaction(function () {
            $tingkatMap = $this->seedTingkat();
            $mapelMap = $this->seedMataPelajaran();
            $guruMap = $this->seedGuruDanPegawai();
            $kelasMap = $this->seedKelas($tingkatMap, $guruMap);
            $this->seedSiswa($kelasMap);
            $this->seedJadwal($kelasMap, $mapelMap, $guruMap);
        });
    }

    private function seedTingkat(): array
    {
        $result = [];

        foreach ([
            ['nama' => '1', 'urutan' => 1],
            ['nama' => '2', 'urutan' => 2],
            ['nama' => '3', 'urutan' => 3],
        ] as $row) {
            $tingkat = Tingkat::updateOrCreate(
                ['nama' => $row['nama']],
                ['urutan' => $row['urutan']]
            );

            $result[$row['nama']] = $tingkat;
        }

        return $result;
    }

    private function seedMataPelajaran(): array
    {
        $result = [];

        $mapelList = [
            ['kode' => 'MTK', 'nama' => 'Matematika'],
            ['kode' => 'BIN', 'nama' => 'Bahasa Indonesia'],
            ['kode' => 'BIG', 'nama' => 'Bahasa Inggris'],
            ['kode' => 'PKN', 'nama' => 'Pendidikan Pancasila'],
            ['kode' => 'KJR', 'nama' => 'Kejuruan Dasar'],
            ['kode' => 'PPL', 'nama' => 'Projek Profil Pelajar Pancasila'],
            ['kode' => 'INF', 'nama' => 'Informatika'],
            ['kode' => 'PJK', 'nama' => 'PJOK'],
        ];

        foreach ($mapelList as $row) {
            $mapel = MataPelajaran::updateOrCreate(
                ['kode' => $row['kode']],
                ['nama' => $row['nama']]
            );

            $result[$row['kode']] = $mapel;
        }

        return $result;
    }

    private function seedGuruDanPegawai(): array
    {
        $result = [];
        $guruRole = Role::where('name', 'guru')->first();

        $guruList = [
            ['nama' => 'Budi Santoso', 'nip' => '1985001', 'email' => 'guru.budi@example.com'],
            ['nama' => 'Siti Aminah', 'nip' => '1985002', 'email' => 'guru.siti@example.com'],
            ['nama' => 'Rina Kartika', 'nip' => '1985003', 'email' => 'guru.rina@example.com'],
            ['nama' => 'Agus Prasetyo', 'nip' => '1985004', 'email' => 'guru.agus@example.com'],
            ['nama' => 'Dewi Lestari', 'nip' => '1985005', 'email' => 'guru.dewi@example.com'],
            ['nama' => 'Yusuf Maulana', 'nip' => '1985006', 'email' => 'guru.yusuf@example.com'],
        ];

        foreach ($guruList as $row) {
            $user = User::updateOrCreate(
                ['email' => $row['email']],
                [
                    'name' => $row['nama'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            if ($guruRole) {
                // Guru hanya punya peran guru (bukan pegawai).
                $user->roles()->sync([$guruRole->id]);
            }

            $guru = Guru::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'pegawai_id' => null,
                    'nama' => $row['nama'],
                    'nip' => $row['nip'],
                ]
            );

            $result[$row['nama']] = $guru;
        }

        return $result;
    }

    private function seedKelas(array $tingkatMap, array $guruMap): array
    {
        $result = [];

        $kelasList = [
            ['kode' => '1A', 'tingkat' => '1', 'nama_kelas' => 'A', 'wali' => 'Budi Santoso'],
            ['kode' => '1B', 'tingkat' => '1', 'nama_kelas' => 'B', 'wali' => 'Siti Aminah'],
            ['kode' => '2A', 'tingkat' => '2', 'nama_kelas' => 'A', 'wali' => 'Rina Kartika'],
            ['kode' => '2B', 'tingkat' => '2', 'nama_kelas' => 'B', 'wali' => 'Agus Prasetyo'],
            ['kode' => '3A', 'tingkat' => '3', 'nama_kelas' => 'A', 'wali' => 'Dewi Lestari'],
            ['kode' => '3B', 'tingkat' => '3', 'nama_kelas' => 'B', 'wali' => 'Yusuf Maulana'],
        ];

        foreach ($kelasList as $row) {
            $kelas = Kelas::updateOrCreate(
                [
                    'tingkat_id' => $tingkatMap[$row['tingkat']]->id,
                    'nama_kelas' => $row['nama_kelas'],
                ],
                [
                    'wali_kelas_guru_id' => $guruMap[$row['wali']]->id ?? null,
                ]
            );

            $result[$row['kode']] = $kelas;
        }

        return $result;
    }

    private function seedSiswa(array $kelasMap): void
    {
        $siswaRole = Role::where('name', 'siswa')->first();

        foreach ($kelasMap as $kodeKelas => $kelas) {
            for ($i = 1; $i <= 6; $i++) {
                $nomor = str_pad((string) $i, 2, '0', STR_PAD_LEFT);
                $nis = 'S' . str_replace(['A', 'B'], ['1', '2'], $kodeKelas) . $nomor;
                $email = strtolower("siswa.{$kodeKelas}.{$nomor}@example.com");
                $nama = "Siswa {$kodeKelas} {$nomor}";

                $user = User::updateOrCreate(
                    ['email' => $email],
                    [
                        'name' => $nama,
                        'password' => Hash::make('password'),
                        'email_verified_at' => now(),
                    ]
                );

                if ($siswaRole) {
                    $user->assignRole($siswaRole);
                }

                Siswa::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'kelas_id' => $kelas->id,
                        'nis' => $nis,
                        'nama' => $nama,
                    ]
                );
            }
        }
    }

    private function seedJadwal(array $kelasMap, array $mapelMap, array $guruMap): void
    {
        $tahunAjaran = TahunAjaran::firstOrCreate(
            ['nama' => '2025/2026'],
            ['tanggal_mulai' => '2025-07-01', 'tanggal_selesai' => '2026-06-30']
        );

        $hariList = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
        $jamByIndex = [
            ['07:00', '08:30'],
            ['08:30', '10:00'],
            ['10:15', '11:45'],
            ['13:00', '14:30'],
        ];

        $guruKeys = array_values($guruMap);
        $mapelKeys = array_values($mapelMap);

        foreach ($kelasMap as $kodeKelas => $kelas) {
            foreach ($mapelKeys as $idx => $mapel) {
                $slot = $jamByIndex[$idx % count($jamByIndex)];
                $hari = $hariList[$idx % count($hariList)];
                $guru = $guruKeys[$idx % count($guruKeys)];

                Jadwal::updateOrCreate(
                    [
                        'kelas_id' => $kelas->id,
                        'mata_pelajaran_id' => $mapel->id,
                        'semester' => 'ganjil',
                        'tahun_ajaran_id' => $tahunAjaran->id,
                    ],
                    [
                        'guru_id' => $guru->id,
                        'hari' => $hari,
                        'jam_mulai' => $slot[0],
                        'jam_selesai' => $slot[1],
                    ]
                );
            }
        }
    }
}
