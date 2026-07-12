<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrangTua extends Model
{
    protected $fillable = ['user_id', 'nama', 'nik', 'telepon', 'alamat', 'pekerjaan'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function siswas(): BelongsToMany
    {
        return $this->belongsToMany(Siswa::class, 'siswa_orang_tua')
            ->withPivot(['hubungan', 'is_penanggung_jawab'])
            ->withTimestamps();
    }

    public function pengajuanPembayarans(): HasMany
    {
        return $this->hasMany(PengajuanPembayaran::class);
    }
}
