<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pegawai extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'nip', 'nama', 'telepon', 'alamat'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function guru(): HasOne
    {
        return $this->hasOne(Guru::class);
    }
}
