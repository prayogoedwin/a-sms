<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PengajuanPembayaran extends Model
{
    protected $fillable = [
        'orang_tua_id',
        'siswa_id',
        'tanggal_transfer',
        'total_nominal',
        'metode',
        'bukti_path',
        'keterangan',
        'status',
        'catatan_admin',
        'diverifikasi_oleh',
        'diverifikasi_pada',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_transfer' => 'date',
            'total_nominal' => 'decimal:2',
            'diverifikasi_pada' => 'datetime',
        ];
    }

    public function orangTua(): BelongsTo
    {
        return $this->belongsTo(OrangTua::class);
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function diverifikasiOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    public function details(): HasMany
    {
        return $this->hasMany(PengajuanPembayaranDetail::class);
    }
}
