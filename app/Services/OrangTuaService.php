<?php

namespace App\Services;

use App\Models\OrangTua;
use App\Models\Siswa;
use App\Models\SiswaOrangTua;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class OrangTuaService
{
    public const SESSION_ANAK_AKTIF = 'anak_aktif_id';

    public function canAccessPortal(User $user): bool
    {
        return $user->hasRole('super-admin')
            || $user->hasRole('orang-tua');
    }

    public function getAccessibleSiswas(User $user): Collection
    {
        if ($user->hasRole('super-admin')) {
            return Siswa::with('kelas.tingkat')->orderBy('nama')->get();
        }

        $orangTua = $user->orangTua;
        if (! $orangTua) {
            return collect();
        }

        return $orangTua->siswas()->with('kelas.tingkat')->orderBy('nama')->get();
    }

    public function resolveAnakAktif(Request $request): ?Siswa
    {
        $user = $request->user();
        $accessible = $this->getAccessibleSiswas($user);

        if ($accessible->isEmpty()) {
            return null;
        }

        if ($request->filled('anak_id')) {
            $anakId = (int) $request->input('anak_id');
            if ($accessible->contains('id', $anakId)) {
                session([self::SESSION_ANAK_AKTIF => $anakId]);
            }
        }

        $anakId = session(self::SESSION_ANAK_AKTIF);

        if ($anakId && $accessible->contains('id', (int) $anakId)) {
            return Siswa::with('kelas.tingkat')->find($anakId);
        }

        $first = $accessible->first();
        session([self::SESSION_ANAK_AKTIF => $first->id]);

        return Siswa::with('kelas.tingkat')->find($first->id);
    }

    public function authorizeAnakAccess(User $user, int $siswaId): void
    {
        abort_unless(
            $this->getAccessibleSiswas($user)->contains('id', $siswaId),
            403
        );
    }

    public function syncAkunFlags(Siswa $siswa): void
    {
        $hubungans = SiswaOrangTua::where('siswa_id', $siswa->id)->pluck('hubungan');

        $siswa->update([
            'is_ayah_memiliki_akun' => $hubungans->contains('ayah'),
            'is_ibu_memiliki_akun' => $hubungans->contains('ibu'),
            'is_wali_memiliki_akun' => $hubungans->contains('wali'),
        ]);
    }

    public function linkToSiswa(OrangTua $orangTua, Siswa $siswa, string $hubungan, bool $penanggungJawab = false): void
    {
        if (SiswaOrangTua::where('siswa_id', $siswa->id)->where('hubungan', $hubungan)->exists()) {
            throw new \InvalidArgumentException("Siswa sudah memiliki akun untuk hubungan {$hubungan}.");
        }

        $orangTua->siswas()->attach($siswa->id, [
            'hubungan' => $hubungan,
            'is_penanggung_jawab' => $penanggungJawab,
        ]);

        $this->syncAkunFlags($siswa->fresh());
    }

    public function unlinkFromSiswa(OrangTua $orangTua, Siswa $siswa): void
    {
        $orangTua->siswas()->detach($siswa->id);
        $this->syncAkunFlags($siswa->fresh());
    }

    public function labelHubungan(string $hubungan): string
    {
        return match ($hubungan) {
            'ayah' => 'Ayah',
            'ibu' => 'Ibu',
            'wali' => 'Wali',
            default => ucfirst($hubungan),
        };
    }
}
