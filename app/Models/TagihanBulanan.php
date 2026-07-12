<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TagihanBulanan extends Model
{
    protected $fillable = [
        'siswa_id',
        'tahun_ajaran_id',
        'bulan',
        'tahun',
        'total_nominal',
        'total_terbayar',
        'status',
        'dicetak_pada',
    ];

    protected function casts(): array
    {
        return [
            'bulan' => 'integer',
            'tahun' => 'integer',
            'total_nominal' => 'decimal:2',
            'total_terbayar' => 'decimal:2',
            'dicetak_pada' => 'datetime',
        ];
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(TagihanBulananDetail::class);
    }
}
