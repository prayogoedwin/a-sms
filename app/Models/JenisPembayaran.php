<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisPembayaran extends Model
{
    protected $fillable = [
        'kode',
        'nama',
        'frekuensi',
        'wajib',
        'bulan_berlaku',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'wajib' => 'boolean',
            'aktif' => 'boolean',
            'bulan_berlaku' => 'integer',
        ];
    }

    public function tarifs(): HasMany
    {
        return $this->hasMany(TarifPembayaran::class);
    }

    public function siswaAssignments(): HasMany
    {
        return $this->hasMany(SiswaJenisPembayaran::class);
    }

    public function tagihanDetails(): HasMany
    {
        return $this->hasMany(TagihanBulananDetail::class);
    }
}
