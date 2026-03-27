<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guru extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'pegawai_id', 'nip', 'nama'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class);
    }

    public function waliKelasDi(): HasMany
    {
        return $this->hasMany(Kelas::class, 'wali_kelas_guru_id');
    }
}
