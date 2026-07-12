<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanPembayaranDetail extends Model
{
    protected $fillable = [
        'pengajuan_pembayaran_id',
        'tagihan_bulanan_detail_id',
        'nominal',
    ];

    protected function casts(): array
    {
        return [
            'nominal' => 'decimal:2',
        ];
    }

    public function pengajuanPembayaran(): BelongsTo
    {
        return $this->belongsTo(PengajuanPembayaran::class);
    }

    public function tagihanBulananDetail(): BelongsTo
    {
        return $this->belongsTo(TagihanBulananDetail::class);
    }
}
