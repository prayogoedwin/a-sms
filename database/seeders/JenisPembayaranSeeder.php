<?php

namespace Database\Seeders;

use App\Models\JenisPembayaran;
use Illuminate\Database\Seeder;

class JenisPembayaranSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['kode' => 'SPP', 'nama' => 'SPP', 'frekuensi' => 'bulanan', 'wajib' => true, 'bulan_berlaku' => null],
            ['kode' => 'KEGIATAN', 'nama' => 'Uang Kegiatan', 'frekuensi' => 'bulanan', 'wajib' => true, 'bulan_berlaku' => null],
            ['kode' => 'EKSUL', 'nama' => 'Ekskul', 'frekuensi' => 'bulanan', 'wajib' => false, 'bulan_berlaku' => null],
            ['kode' => 'BUKU', 'nama' => 'Uang Buku/LKS', 'frekuensi' => 'tahunan', 'wajib' => true, 'bulan_berlaku' => 7],
            ['kode' => 'SERAGAM', 'nama' => 'Uang Seragam', 'frekuensi' => 'sekali', 'wajib' => true, 'bulan_berlaku' => null],
            ['kode' => 'GEDUNG', 'nama' => 'Uang Gedung', 'frekuensi' => 'tahunan', 'wajib' => true, 'bulan_berlaku' => 7],
        ];

        foreach ($items as $item) {
            JenisPembayaran::firstOrCreate(
                ['kode' => $item['kode']],
                array_merge($item, ['aktif' => true])
            );
        }
    }
}
