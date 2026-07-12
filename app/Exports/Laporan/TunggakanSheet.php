<?php

namespace App\Exports\Laporan;

use App\Models\TagihanBulanan;
use App\Services\KeuanganService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class TunggakanSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    public function __construct(private array $filters) {}

    public function collection()
    {
        $query = TagihanBulanan::with(['siswa.kelas.tingkat'])
            ->whereIn('status', ['belum_lunas', 'sebagian'])
            ->orderByDesc('tahun')
            ->orderByDesc('bulan');

        app(KeuanganService::class)->applyTagihanFilters($query, $this->filters);

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Siswa',
            'NIS',
            'Kelas',
            'Periode',
            'Total Tagihan',
            'Terbayar',
            'Sisa',
            'Status',
        ];
    }

    public function map($tagihan): array
    {
        $ks = app(KeuanganService::class);

        return [
            $tagihan->siswa->nama,
            $tagihan->siswa->nis ?: '-',
            $tagihan->siswa->kelas
                ? $tagihan->siswa->kelas->tingkat->nama . ' ' . $tagihan->siswa->kelas->nama_kelas
                : '-',
            KeuanganService::BULAN_NAMA[$tagihan->bulan] . ' ' . $tagihan->tahun,
            (float) $tagihan->total_nominal,
            (float) $tagihan->total_terbayar,
            (float) $tagihan->total_nominal - (float) $tagihan->total_terbayar,
            $ks->labelStatus($tagihan->status),
        ];
    }

    public function title(): string
    {
        return 'Tunggakan';
    }
}
