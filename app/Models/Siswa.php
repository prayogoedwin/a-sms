<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'kelas_id', 'nis', 'nama',
        'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
        'nisn', 'nik', 'agama', 'alamat', 'telepon', 'status',
        'nama_ayah', 'nama_ibu', 'nama_wali',
        'is_ayah_memiliki_akun', 'is_ibu_memiliki_akun', 'is_wali_memiliki_akun',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'is_ayah_memiliki_akun' => 'boolean',
            'is_ibu_memiliki_akun' => 'boolean',
            'is_wali_memiliki_akun' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function nilais(): HasMany
    {
        return $this->hasMany(NilaiSiswa::class);
    }

    public function absensis(): HasMany
    {
        return $this->hasMany(AbsensiSiswa::class);
    }

    public function jenisPembayarans(): HasMany
    {
        return $this->hasMany(SiswaJenisPembayaran::class);
    }

    public function tagihanBulanans(): HasMany
    {
        return $this->hasMany(TagihanBulanan::class);
    }

    public function pembayarans(): HasMany
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function orangTuas(): BelongsToMany
    {
        return $this->belongsToMany(OrangTua::class, 'siswa_orang_tua')
            ->withPivot(['hubungan', 'is_penanggung_jawab'])
            ->withTimestamps();
    }

    public function pengajuanPembayarans(): HasMany
    {
        return $this->hasMany(PengajuanPembayaran::class);
    }
}
