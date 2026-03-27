<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbsensiSiswa extends Model
{
    use HasFactory;

    protected $fillable = ['jadwal_id', 'siswa_id', 'tanggal', 'status', 'keterangan', 'diinput_oleh_guru_id'];

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function guruPenginput(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'diinput_oleh_guru_id');
    }
}
