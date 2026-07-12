<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TagihanBulananDetail extends Model
{
    protected $fillable = [
        'tagihan_bulanan_id',
        'jenis_pembayaran_id',
        'nominal',
        'nominal_terbayar',
        'status',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'nominal' => 'decimal:2',
            'nominal_terbayar' => 'decimal:2',
        ];
    }

    public function tagihanBulanan(): BelongsTo
    {
        return $this->belongsTo(TagihanBulanan::class);
    }

    public function jenisPembayaran(): BelongsTo
    {
        return $this->belongsTo(JenisPembayaran::class);
    }

    public function pembayaranDetails(): HasMany
    {
        return $this->hasMany(PembayaranDetail::class);
    }
}
