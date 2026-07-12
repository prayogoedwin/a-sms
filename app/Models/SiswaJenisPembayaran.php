<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiswaJenisPembayaran extends Model
{
    protected $fillable = [
        'siswa_id',
        'jenis_pembayaran_id',
        'nominal_override',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'nominal_override' => 'decimal:2',
            'aktif' => 'boolean',
        ];
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function jenisPembayaran(): BelongsTo
    {
        return $this->belongsTo(JenisPembayaran::class);
    }
}
