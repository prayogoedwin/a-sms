<?php

namespace App\Exports\Laporan;

use App\Services\KeuanganService;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;

class RingkasanSheet implements FromArray, WithTitle
{
    public function __construct(private array $filters) {}

    public function array(): array
    {
        $ks = app(KeuanganService::class);
        $summary = $ks->getLaporanSummary($this->filters);

        return [
            ['LAPORAN KEUANGAN'],
            ['Dicetak', now()->format('d/m/Y H:i')],
            [],
            ['Ringkasan'],
            ['Total Tagihan', $summary['total_tagihan']],
            ['Total Terbayar', $summary['total_terbayar']],
            ['Total Pemasukan (Transaksi)', $summary['total_pemasukan']],
            ['Sisa Tagihan', $summary['total_tagihan'] - $summary['total_terbayar']],
            [],
            ['Status Tagihan'],
            ['Belum Lunas', $summary['belum_lunas']],
            ['Sebagian', $summary['sebagian']],
            ['Lunas', $summary['lunas']],
            [],
            ['Filter'],
            ['Tahun Ajaran ID', $this->filters['tahun_ajaran_id'] ?: 'Semua'],
            ['Bulan', $this->filters['bulan'] ? KeuanganService::BULAN_NAMA[$this->filters['bulan']] : 'Semua'],
            ['Tahun', $this->filters['tahun'] ?: 'Semua'],
            ['Kelas ID', $this->filters['kelas_id'] ?: 'Semua'],
        ];
    }

    public function title(): string
    {
        return 'Ringkasan';
    }
}
