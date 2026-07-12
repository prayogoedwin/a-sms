<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiswaOrangTua extends Model
{
    protected $table = 'siswa_orang_tua';

    protected $fillable = [
        'orang_tua_id',
        'siswa_id',
        'hubungan',
        'is_penanggung_jawab',
    ];

    protected function casts(): array
    {
        return [
            'is_penanggung_jawab' => 'boolean',
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
}
