<?php

namespace App\Exports\Laporan;

use App\Models\Pembayaran;
use App\Services\KeuanganService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class PembayaranSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    public function __construct(private array $filters) {}

    public function collection()
    {
        $query = Pembayaran::with(['siswa.kelas.tingkat', 'dicatatOleh'])
            ->orderByDesc('tanggal')
            ->orderByDesc('id');

        app(KeuanganService::class)->applyPembayaranFilters($query, $this->filters);

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Siswa',
            'NIS',
            'Kelas',
            'Nominal',
            'Metode',
            'Keterangan',
            'Dicatat Oleh',
        ];
    }

    public function map($pembayaran): array
    {
        return [
            $pembayaran->tanggal->format('d/m/Y'),
            $pembayaran->siswa->nama,
            $pembayaran->siswa->nis ?: '-',
            $pembayaran->siswa->kelas
                ? $pembayaran->siswa->kelas->tingkat->nama . ' ' . $pembayaran->siswa->kelas->nama_kelas
                : '-',
            (float) $pembayaran->total_nominal,
            ucfirst($pembayaran->metode),
            $pembayaran->keterangan ?: '-',
            $pembayaran->dicatatOleh->name,
        ];
    }

    public function title(): string
    {
        return 'Pembayaran';
    }
}
