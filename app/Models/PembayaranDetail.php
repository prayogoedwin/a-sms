<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranDetail extends Model
{
    protected $fillable = [
        'pembayaran_id',
        'tagihan_bulanan_detail_id',
        'nominal',
    ];

    protected function casts(): array
    {
        return [
            'nominal' => 'decimal:2',
        ];
    }

    public function pembayaran(): BelongsTo
    {
        return $this->belongsTo(Pembayaran::class);
    }

    public function tagihanBulananDetail(): BelongsTo
    {
        return $this->belongsTo(TagihanBulananDetail::class);
    }
}
