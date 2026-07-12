<?php

namespace App\Exports;

use App\Exports\Laporan\PembayaranSheet;
use App\Exports\Laporan\RingkasanSheet;
use App\Exports\Laporan\TagihanSheet;
use App\Exports\Laporan\TunggakanSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LaporanKeuanganExport implements WithMultipleSheets
{
    public function __construct(private array $filters) {}

    public function sheets(): array
    {
        return [
            new RingkasanSheet($this->filters),
            new TagihanSheet($this->filters),
            new TunggakanSheet($this->filters),
            new PembayaranSheet($this->filters),
        ];
    }
}
