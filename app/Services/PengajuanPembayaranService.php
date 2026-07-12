<?php

namespace App\Services;

use App\Models\Pembayaran;
use App\Models\PembayaranDetail;
use App\Models\PengajuanPembayaran;
use App\Models\PengajuanPembayaranDetail;
use App\Models\TagihanBulanan;
use App\Models\TagihanBulananDetail;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PengajuanPembayaranService
{
    public function __construct(private KeuanganService $keuangan) {}

    public function store(
        User $user,
        int $siswaId,
        int $orangTuaId,
        array $validated,
        UploadedFile $bukti,
        array $items
    ): PengajuanPembayaran {
        return DB::transaction(function () use ($user, $siswaId, $orangTuaId, $validated, $bukti, $items) {
            $path = $bukti->store('bukti-pembayaran', 'public');
            $total = collect($items)->sum('nominal');

            $pengajuan = PengajuanPembayaran::create([
                'orang_tua_id' => $orangTuaId,
                'siswa_id' => $siswaId,
                'tanggal_transfer' => $validated['tanggal_transfer'],
                'total_nominal' => $total,
                'metode' => 'transfer',
                'bukti_path' => $path,
                'keterangan' => $validated['keterangan'] ?? null,
                'status' => 'menunggu',
            ]);

            foreach ($items as $item) {
                PengajuanPembayaranDetail::create([
                    'pengajuan_pembayaran_id' => $pengajuan->id,
                    'tagihan_bulanan_detail_id' => $item['detail_id'],
                    'nominal' => $item['nominal'],
                ]);
            }

            return $pengajuan;
        });
    }

    public function approve(PengajuanPembayaran $pengajuan, User $admin): void
    {
        if ($pengajuan->status !== 'menunggu') {
            throw new \InvalidArgumentException('Pengajuan sudah diproses.');
        }

        DB::transaction(function () use ($pengajuan, $admin) {
            $pengajuan->load('details.tagihanBulananDetail');

            $pembayaran = Pembayaran::create([
                'siswa_id' => $pengajuan->siswa_id,
                'tanggal' => $pengajuan->tanggal_transfer,
                'total_nominal' => $pengajuan->total_nominal,
                'metode' => 'transfer',
                'keterangan' => 'Disetujui dari pengajuan #' . $pengajuan->id,
                'dicatat_oleh' => $admin->id,
            ]);

            $tagihanIds = [];

            foreach ($pengajuan->details as $detail) {
                $tagihanDetail = $detail->tagihanBulananDetail;
                PembayaranDetail::create([
                    'pembayaran_id' => $pembayaran->id,
                    'tagihan_bulanan_detail_id' => $tagihanDetail->id,
                    'nominal' => $detail->nominal,
                ]);

                $tagihanDetail->nominal_terbayar = (float) $tagihanDetail->nominal_terbayar + (float) $detail->nominal;
                $this->keuangan->recalculateDetail($tagihanDetail);
                $tagihanIds[$tagihanDetail->tagihan_bulanan_id] = $tagihanDetail->tagihan_bulanan_id;
            }

            foreach ($tagihanIds as $tagihanId) {
                $this->keuangan->recalculateTagihan(TagihanBulanan::with('details')->find($tagihanId));
            }

            $pengajuan->update([
                'status' => 'disetujui',
                'diverifikasi_oleh' => $admin->id,
                'diverifikasi_pada' => now(),
            ]);
        });
    }

    public function reject(PengajuanPembayaran $pengajuan, User $admin, string $catatan): void
    {
        if ($pengajuan->status !== 'menunggu') {
            throw new \InvalidArgumentException('Pengajuan sudah diproses.');
        }

        $pengajuan->update([
            'status' => 'ditolak',
            'catatan_admin' => $catatan,
            'diverifikasi_oleh' => $admin->id,
            'diverifikasi_pada' => now(),
        ]);
    }

    public function labelStatus(string $status): string
    {
        return match ($status) {
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            default => 'Menunggu',
        };
    }

    public function badgeClass(string $status): string
    {
        return match ($status) {
            'disetujui' => 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300',
            'ditolak' => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300',
            default => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300',
        };
    }
}
