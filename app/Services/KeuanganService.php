<?php

namespace App\Services;

use App\Models\JenisPembayaran;
use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\SiswaJenisPembayaran;
use App\Models\TagihanBulanan;
use App\Models\TagihanBulananDetail;
use App\Models\TarifPembayaran;
use Illuminate\Support\Collection;

class KeuanganService
{
    public const BULAN_NAMA = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    public function hitungStatus(float $nominal, float $terbayar): string
    {
        if ($terbayar <= 0) {
            return 'belum_lunas';
        }

        if ($terbayar < $nominal) {
            return 'sebagian';
        }

        return 'lunas';
    }

    public function recalculateDetail(TagihanBulananDetail $detail): void
    {
        $detail->status = $this->hitungStatus((float) $detail->nominal, (float) $detail->nominal_terbayar);
        $detail->save();
    }

    public function recalculateTagihan(TagihanBulanan $tagihan): void
    {
        $tagihan->load('details');

        $tagihan->total_nominal = $tagihan->details->sum('nominal');
        $tagihan->total_terbayar = $tagihan->details->sum('nominal_terbayar');

        $statuses = $tagihan->details->pluck('status');
        if ($statuses->every(fn ($s) => $s === 'lunas')) {
            $tagihan->status = 'lunas';
        } elseif ($statuses->contains(fn ($s) => in_array($s, ['lunas', 'sebagian'], true))) {
            $tagihan->status = 'sebagian';
        } else {
            $tagihan->status = 'belum_lunas';
        }

        $tagihan->save();
    }

    public function generateTagihan(int $tahunAjaranId, int $bulan, int $tahun, ?int $kelasId = null): array
    {
        $created = 0;
        $skipped = 0;

        $jenisList = JenisPembayaran::where('aktif', true)->get();
        $siswas = Siswa::with(['kelas.tingkat', 'jenisPembayarans'])
            ->when($kelasId, fn ($q) => $q->where('kelas_id', $kelasId))
            ->get();

        foreach ($siswas as $siswa) {
            if (! $siswa->kelas?->tingkat_id) {
                $skipped++;
                continue;
            }

            $existing = TagihanBulanan::where('siswa_id', $siswa->id)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->exists();

            if ($existing) {
                $skipped++;
                continue;
            }

            $applicableJenis = $this->getApplicableJenis($siswa, $jenisList, $bulan);

            if ($applicableJenis->isEmpty()) {
                $skipped++;
                continue;
            }

            $tagihan = TagihanBulanan::create([
                'siswa_id' => $siswa->id,
                'tahun_ajaran_id' => $tahunAjaranId,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'total_nominal' => 0,
                'total_terbayar' => 0,
                'status' => 'belum_lunas',
            ]);

            foreach ($applicableJenis as $jenis) {
                $nominal = $this->resolveNominal($siswa, $jenis, $tahunAjaranId);

                if ($nominal === null || $nominal <= 0) {
                    continue;
                }

                TagihanBulananDetail::create([
                    'tagihan_bulanan_id' => $tagihan->id,
                    'jenis_pembayaran_id' => $jenis->id,
                    'nominal' => $nominal,
                    'nominal_terbayar' => 0,
                    'status' => 'belum_lunas',
                ]);
            }

            $this->recalculateTagihan($tagihan->fresh('details'));

            if ($tagihan->fresh()->details()->count() === 0) {
                $tagihan->delete();
                $skipped++;
                continue;
            }

            $created++;
        }

        return ['created' => $created, 'skipped' => $skipped];
    }

    public function getApplicableJenis(Siswa $siswa, Collection $jenisList, int $bulan): Collection
    {
        return $jenisList->filter(function (JenisPembayaran $jenis) use ($siswa, $bulan) {
            if (! $this->siswaBerhakJenis($siswa, $jenis)) {
                return false;
            }

            return match ($jenis->frekuensi) {
                'bulanan' => true,
                'tahunan' => $jenis->bulan_berlaku === $bulan,
                'sekali' => ! TagihanBulananDetail::whereHas('tagihanBulanan', fn ($q) => $q->where('siswa_id', $siswa->id))
                    ->where('jenis_pembayaran_id', $jenis->id)
                    ->exists(),
                default => false,
            };
        });
    }

    public function siswaBerhakJenis(Siswa $siswa, JenisPembayaran $jenis): bool
    {
        if ($jenis->wajib) {
            return true;
        }

        return $siswa->jenisPembayarans
            ->where('jenis_pembayaran_id', $jenis->id)
            ->where('aktif', true)
            ->isNotEmpty();
    }

    public function resolveNominal(Siswa $siswa, JenisPembayaran $jenis, int $tahunAjaranId): ?float
    {
        $assignment = $siswa->jenisPembayarans
            ->where('jenis_pembayaran_id', $jenis->id)
            ->where('aktif', true)
            ->first();

        if ($assignment?->nominal_override !== null) {
            return (float) $assignment->nominal_override;
        }

        $tarif = TarifPembayaran::where('jenis_pembayaran_id', $jenis->id)
            ->where('tingkat_id', $siswa->kelas->tingkat_id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->first();

        return $tarif ? (float) $tarif->nominal : null;
    }

    public function formatRupiah(float|int|string $amount): string
    {
        return 'Rp ' . number_format((float) $amount, 0, ',', '.');
    }

    public function labelBulan(int $bulan): string
    {
        return self::BULAN_NAMA[$bulan] ?? (string) $bulan;
    }

    public function labelStatus(string $status): string
    {
        return match ($status) {
            'lunas' => 'Lunas',
            'sebagian' => 'Sebagian',
            default => 'Belum Lunas',
        };
    }

    public function badgeClass(string $status): string
    {
        return match ($status) {
            'lunas' => 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300',
            'sebagian' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300',
            default => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300',
        };
    }

    public function parseLaporanFilters(array $input): array
    {
        return [
            'tahun_ajaran_id' => $input['tahun_ajaran_id'] ?? null,
            'bulan' => ! empty($input['bulan']) ? (int) $input['bulan'] : null,
            'tahun' => ! empty($input['tahun']) ? (int) $input['tahun'] : null,
            'kelas_id' => $input['kelas_id'] ?? null,
        ];
    }

    public function applyTagihanFilters($query, array $filters): void
    {
        if (! empty($filters['tahun_ajaran_id'])) {
            $query->where('tahun_ajaran_id', $filters['tahun_ajaran_id']);
        }
        if (! empty($filters['bulan'])) {
            $query->where('bulan', $filters['bulan']);
        }
        if (! empty($filters['tahun'])) {
            $query->where('tahun', $filters['tahun']);
        }
        if (! empty($filters['kelas_id'])) {
            $query->whereHas('siswa', fn ($q) => $q->where('kelas_id', $filters['kelas_id']));
        }
    }

    public function applyPembayaranFilters($query, array $filters): void
    {
        if (! empty($filters['bulan'])) {
            $query->whereMonth('tanggal', $filters['bulan']);
        }
        if (! empty($filters['tahun'])) {
            $query->whereYear('tanggal', $filters['tahun']);
        }
        if (! empty($filters['kelas_id'])) {
            $query->whereHas('siswa', fn ($q) => $q->where('kelas_id', $filters['kelas_id']));
        }
    }

    public function getLaporanSummary(array $filters): array
    {
        $tagihanQuery = TagihanBulanan::query();
        $this->applyTagihanFilters($tagihanQuery, $filters);

        $pembayaranQuery = Pembayaran::query();
        $this->applyPembayaranFilters($pembayaranQuery, $filters);

        return [
            'total_tagihan' => (float) (clone $tagihanQuery)->sum('total_nominal'),
            'total_terbayar' => (float) (clone $tagihanQuery)->sum('total_terbayar'),
            'total_pemasukan' => (float) $pembayaranQuery->sum('total_nominal'),
            'belum_lunas' => (clone $tagihanQuery)->where('status', 'belum_lunas')->count(),
            'sebagian' => (clone $tagihanQuery)->where('status', 'sebagian')->count(),
            'lunas' => (clone $tagihanQuery)->where('status', 'lunas')->count(),
        ];
    }

    public function syncSiswaJenisPembayaran(Siswa $siswa, array $items): void
    {
        $optionalIds = JenisPembayaran::where('wajib', false)->where('aktif', true)->pluck('id');

        SiswaJenisPembayaran::where('siswa_id', $siswa->id)
            ->whereIn('jenis_pembayaran_id', $optionalIds)
            ->delete();

        foreach ($items as $item) {
            if (empty($item['aktif'])) {
                continue;
            }

            SiswaJenisPembayaran::create([
                'siswa_id' => $siswa->id,
                'jenis_pembayaran_id' => $item['jenis_pembayaran_id'],
                'nominal_override' => ! empty($item['nominal_override']) ? $item['nominal_override'] : null,
                'aktif' => true,
            ]);
        }
    }
}
