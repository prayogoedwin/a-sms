<?php

namespace Database\Seeders;

use App\Models\JenisPembayaran;
use App\Models\TahunAjaran;
use App\Models\TarifPembayaran;
use App\Models\Tingkat;
use Illuminate\Database\Seeder;

class TarifPembayaranSeeder extends Seeder
{
    public function run(): void
    {
        $tahunAjaran = TahunAjaran::orderByDesc('id')->first();
        if (! $tahunAjaran) {
            return;
        }

        $defaults = [
            'SPP' => [150000, 175000, 200000],
            'KEGIATAN' => [25000, 25000, 25000],
            'EKSUL' => [50000, 50000, 50000],
            'BUKU' => [300000, 350000, 400000],
            'SERAGAM' => [450000, 450000, 450000],
            'GEDUNG' => [500000, 500000, 500000],
        ];

        $tingkats = Tingkat::orderBy('urutan')->get();

        foreach ($defaults as $kode => $nominals) {
            $jenis = JenisPembayaran::where('kode', $kode)->first();
            if (! $jenis) {
                continue;
            }

            foreach ($tingkats as $index => $tingkat) {
                TarifPembayaran::firstOrCreate(
                    [
                        'jenis_pembayaran_id' => $jenis->id,
                        'tingkat_id' => $tingkat->id,
                        'tahun_ajaran_id' => $tahunAjaran->id,
                    ],
                    ['nominal' => $nominals[$index] ?? $nominals[0]]
                );
            }
        }
    }
}
