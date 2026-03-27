<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NilaiSiswa extends Model
{
    use HasFactory;

    protected $fillable = ['jadwal_id', 'siswa_id', 'nilai_angka', 'catatan', 'dinilai_oleh_guru_id'];

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function guruPenilai(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'dinilai_oleh_guru_id');
    }
}
