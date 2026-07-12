<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\OrangTua;
use App\Models\Pegawai;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\User;
use App\Services\OrangTuaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class DataPenggunaController extends Controller
{
    public function __construct(private OrangTuaService $orangTuaService) {}
    public function pegawaiIndex(): View
    {
        $pegawais = Pegawai::with('user')->latest()->get();
        return view('data-pengguna.pegawai', compact('pegawais'));
    }

    public function pegawaiCreate(): View
    {
        return view('data-pengguna.pegawai-create');
    }

    public function guruIndex(): View
    {
        $gurus = Guru::with(['user', 'pegawai'])->latest()->get();
        return view('data-pengguna.guru', compact('gurus'));
    }

    public function guruCreate(): View
    {
        return view('data-pengguna.guru-create');
    }

    public function siswaIndex(): View
    {
        $siswas = Siswa::with(['user', 'kelas.tingkat'])->latest()->get();
        return view('data-pengguna.siswa', compact('siswas'));
    }

    public function siswaCreate(): View
    {
        $kelasList = Kelas::with('tingkat')->orderBy('tingkat_id')->orderBy('nama_kelas')->get();
        return view('data-pengguna.siswa-create', compact('kelasList'));
    }

    public function storePegawai(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'nama' => ['required', 'string', 'max:255'],
            'jenis_pegawai' => ['required', 'in:tu,lainnya'],
            'nip' => ['nullable', 'string', 'max:50', 'unique:pegawais,nip'],
            'telepon' => ['nullable', 'string', 'max:30'],
            'alamat' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $roleName = $validated['jenis_pegawai'] === 'tu' ? 'pegawai' : 'pegawai-lainnya';
            $user->assignRole($this->getOrCreateRole($roleName));

            Pegawai::create([
                'user_id' => $user->id,
                'nama' => $validated['nama'],
                'jenis_pegawai' => $validated['jenis_pegawai'],
                'nip' => $validated['nip'] ?? null,
                'telepon' => $validated['telepon'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
            ]);
        });

        return to_route('data-pengguna.pegawai.index')->with('status', 'Pegawai dan user berhasil dibuat.');
    }

    public function storeGuru(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'nama' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:50', 'unique:gurus,nip'],
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $user->assignRole($this->getOrCreateRole('guru'));

            Guru::create([
                'user_id' => $user->id,
                'nama' => $validated['nama'],
                'nip' => $validated['nip'] ?? null,
            ]);
        });

        return to_route('data-pengguna.guru.index')->with('status', 'Guru dan user berhasil dibuat.');
    }

    public function storeSiswa(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'nama' => ['required', 'string', 'max:255'],
            'nis' => ['nullable', 'string', 'max:50', 'unique:siswas,nis'],
            'kelas_id' => ['nullable', 'exists:kelas,id'],
            ...$this->siswaBiodataRules(),
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $user->assignRole($this->getOrCreateRole('siswa'));

            Siswa::create(array_merge(
                $this->siswaBiodataPayload($validated),
                [
                    'user_id' => $user->id,
                    'kelas_id' => $validated['kelas_id'] ?? null,
                    'nama' => $validated['nama'],
                    'nis' => $validated['nis'] ?? null,
                ]
            ));
        });

        return to_route('data-pengguna.siswa.index')->with('status', 'Siswa dan user berhasil dibuat.');
    }

    public function pegawaiEdit(Pegawai $pegawai): View
    {
        $pegawai->load('user');
        return view('data-pengguna.pegawai-edit', compact('pegawai'));
    }

    public function pegawaiUpdate(Request $request, Pegawai $pegawai): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $pegawai->user_id],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'nama' => ['required', 'string', 'max:255'],
            'jenis_pegawai' => ['required', 'in:tu,lainnya'],
            'nip' => ['nullable', 'string', 'max:50', 'unique:pegawais,nip,' . $pegawai->id],
            'telepon' => ['nullable', 'string', 'max:30'],
            'alamat' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($pegawai, $validated) {
            $pegawai->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            if (! empty($validated['password'])) {
                $pegawai->user->update(['password' => Hash::make($validated['password'])]);
            }

            $roleName = $validated['jenis_pegawai'] === 'tu' ? 'pegawai' : 'pegawai-lainnya';
            $pegawai->user->roles()->sync([$this->getOrCreateRole($roleName)->id]);

            $pegawai->update([
                'nama' => $validated['nama'],
                'jenis_pegawai' => $validated['jenis_pegawai'],
                'nip' => $validated['nip'] ?? null,
                'telepon' => $validated['telepon'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
            ]);
        });

        return to_route('data-pengguna.pegawai.index')->with('status', 'Data pegawai berhasil diperbarui.');
    }

    public function pegawaiDestroy(Pegawai $pegawai): RedirectResponse
    {
        $pegawai->user()->delete();
        return back()->with('status', 'Data pegawai berhasil dihapus.');
    }

    public function guruEdit(Guru $guru): View
    {
        $guru->load('user');
        return view('data-pengguna.guru-edit', compact('guru'));
    }

    public function guruUpdate(Request $request, Guru $guru): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $guru->user_id],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'nama' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:50', 'unique:gurus,nip,' . $guru->id],
        ]);

        DB::transaction(function () use ($guru, $validated) {
            $guru->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            if (! empty($validated['password'])) {
                $guru->user->update(['password' => Hash::make($validated['password'])]);
            }

            $guru->update([
                'nama' => $validated['nama'],
                'nip' => $validated['nip'] ?? null,
            ]);
        });

        return to_route('data-pengguna.guru.index')->with('status', 'Data guru berhasil diperbarui.');
    }

    public function guruDestroy(Guru $guru): RedirectResponse
    {
        $guru->user()->delete();
        return back()->with('status', 'Data guru berhasil dihapus.');
    }

    public function siswaEdit(Siswa $siswa): View
    {
        $siswa->load(['user', 'kelas', 'orangTuas.user']);
        $kelasList = Kelas::with('tingkat')->orderBy('tingkat_id')->orderBy('nama_kelas')->get();
        $orangTuas = OrangTua::with('user')->orderBy('nama')->get();

        return view('data-pengguna.siswa-edit', compact('siswa', 'kelasList', 'orangTuas'));
    }

    public function siswaUpdate(Request $request, Siswa $siswa): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $siswa->user_id],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'nama' => ['required', 'string', 'max:255'],
            'nis' => ['nullable', 'string', 'max:50', 'unique:siswas,nis,' . $siswa->id],
            'kelas_id' => ['nullable', 'exists:kelas,id'],
            ...$this->siswaBiodataRules($siswa->id),
        ]);

        DB::transaction(function () use ($siswa, $validated) {
            $siswa->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            if (! empty($validated['password'])) {
                $siswa->user->update(['password' => Hash::make($validated['password'])]);
            }

            $siswa->update(array_merge(
                $this->siswaBiodataPayload($validated),
                [
                    'nama' => $validated['nama'],
                    'nis' => $validated['nis'] ?? null,
                    'kelas_id' => $validated['kelas_id'] ?? null,
                ]
            ));
        });

        return to_route('data-pengguna.siswa.index')->with('status', 'Data siswa berhasil diperbarui.');
    }

    public function siswaDestroy(Siswa $siswa): RedirectResponse
    {
        $siswa->user()->delete();
        return back()->with('status', 'Data siswa berhasil dihapus.');
    }

    // --- Orang Tua ---

    public function orangTuaIndex(): View
    {
        $orangTuas = OrangTua::with(['user', 'siswas'])->latest()->get();
        return view('data-pengguna.orang-tua', compact('orangTuas'));
    }

    public function orangTuaCreate(): View
    {
        $siswas = Siswa::with('kelas.tingkat')->orderBy('nama')->get();
        return view('data-pengguna.orang-tua-create', compact('siswas'));
    }

    public function storeOrangTua(Request $request): RedirectResponse
    {
        $mode = $request->input('mode', 'baru');

        if ($mode === 'existing') {
            return $this->linkExistingOrangTua($request);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'nama' => ['required', 'string', 'max:255'],
            'nik' => ['nullable', 'string', 'max:16'],
            'telepon' => ['nullable', 'string', 'max:30'],
            'alamat' => ['nullable', 'string'],
            'pekerjaan' => ['nullable', 'string', 'max:100'],
            'siswa_ids' => ['nullable', 'array'],
            'siswa_ids.*' => ['exists:siswas,id'],
            'hubungan' => ['nullable', 'in:ayah,ibu,wali'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
            $user->assignRole($this->getOrCreateRole('orang-tua'));

            $orangTua = OrangTua::create([
                'user_id' => $user->id,
                'nama' => $validated['nama'],
                'nik' => $validated['nik'] ?? null,
                'telepon' => $validated['telepon'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'pekerjaan' => $validated['pekerjaan'] ?? null,
            ]);

            if (! empty($validated['siswa_ids']) && ! empty($validated['hubungan'])) {
                foreach ($validated['siswa_ids'] as $siswaId) {
                    $siswa = Siswa::find($siswaId);
                    if ($siswa) {
                        $this->orangTuaService->linkToSiswa($orangTua, $siswa, $validated['hubungan']);
                    }
                }
            }
        });

        return to_route('data-pengguna.orang-tua.index')->with('status', 'Orang tua dan akun berhasil dibuat.');
    }

    public function orangTuaEdit(OrangTua $orangTua): View
    {
        $orangTua->load(['user', 'siswas.kelas']);
        $siswas = Siswa::with('kelas.tingkat')->orderBy('nama')->get();
        return view('data-pengguna.orang-tua-edit', compact('orangTua', 'siswas'));
    }

    public function orangTuaUpdate(Request $request, OrangTua $orangTua): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $orangTua->user_id],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'nama' => ['required', 'string', 'max:255'],
            'nik' => ['nullable', 'string', 'max:16'],
            'telepon' => ['nullable', 'string', 'max:30'],
            'alamat' => ['nullable', 'string'],
            'pekerjaan' => ['nullable', 'string', 'max:100'],
        ]);

        DB::transaction(function () use ($orangTua, $validated) {
            $orangTua->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);
            if (! empty($validated['password'])) {
                $orangTua->user->update(['password' => Hash::make($validated['password'])]);
            }
            $orangTua->update([
                'nama' => $validated['nama'],
                'nik' => $validated['nik'] ?? null,
                'telepon' => $validated['telepon'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'pekerjaan' => $validated['pekerjaan'] ?? null,
            ]);
        });

        return to_route('data-pengguna.orang-tua.index')->with('status', 'Data orang tua berhasil diperbarui.');
    }

    public function orangTuaDestroy(OrangTua $orangTua): RedirectResponse
    {
        foreach ($orangTua->siswas as $siswa) {
            $this->orangTuaService->unlinkFromSiswa($orangTua, $siswa);
        }
        $orangTua->user()->delete();
        return back()->with('status', 'Data orang tua berhasil dihapus.');
    }

    public function siswaLinkOrangTua(Request $request, Siswa $siswa): RedirectResponse
    {
        $mode = $request->input('mode', 'existing');

        if ($mode === 'baru') {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'confirmed', Password::defaults()],
                'nama' => ['required', 'string', 'max:255'],
                'telepon' => ['nullable', 'string', 'max:30'],
                'hubungan' => ['required', 'in:ayah,ibu,wali'],
                'is_penanggung_jawab' => ['nullable', 'boolean'],
            ]);

            $penanggungJawab = $request->boolean('is_penanggung_jawab');

            DB::transaction(function () use ($validated, $siswa, $penanggungJawab) {
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                ]);
                $user->assignRole($this->getOrCreateRole('orang-tua'));

                $orangTua = OrangTua::create([
                    'user_id' => $user->id,
                    'nama' => $validated['nama'],
                    'telepon' => $validated['telepon'] ?? null,
                ]);

                $this->orangTuaService->linkToSiswa(
                    $orangTua,
                    $siswa,
                    $validated['hubungan'],
                    $penanggungJawab
                );
            });
        } else {
            $validated = $request->validate([
                'orang_tua_id' => ['required', 'exists:orang_tuas,id'],
                'hubungan' => ['required', 'in:ayah,ibu,wali'],
                'is_penanggung_jawab' => ['nullable', 'boolean'],
            ]);

            $orangTua = OrangTua::findOrFail($validated['orang_tua_id']);

            try {
                $this->orangTuaService->linkToSiswa(
                    $orangTua,
                    $siswa,
                    $validated['hubungan'],
                    $request->boolean('is_penanggung_jawab')
                );
            } catch (\InvalidArgumentException $e) {
                return back()->with('error', $e->getMessage());
            }
        }

        return back()->with('status', 'Orang tua berhasil dihubungkan ke siswa.');
    }

    public function siswaUnlinkOrangTua(Siswa $siswa, OrangTua $orangTua): RedirectResponse
    {
        $this->orangTuaService->unlinkFromSiswa($orangTua, $siswa);
        return back()->with('status', 'Orang tua berhasil dilepas dari siswa.');
    }

    private function linkExistingOrangTua(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'orang_tua_id' => ['required', 'exists:orang_tuas,id'],
            'siswa_ids' => ['required', 'array', 'min:1'],
            'siswa_ids.*' => ['exists:siswas,id'],
            'hubungan' => ['required', 'in:ayah,ibu,wali'],
        ]);

        $orangTua = OrangTua::findOrFail($validated['orang_tua_id']);

        foreach ($validated['siswa_ids'] as $siswaId) {
            $siswa = Siswa::find($siswaId);
            if ($siswa) {
                try {
                    $this->orangTuaService->linkToSiswa($orangTua, $siswa, $validated['hubungan']);
                } catch (\InvalidArgumentException $e) {
                    return back()->with('error', $e->getMessage());
                }
            }
        }

        return to_route('data-pengguna.orang-tua.index')->with('status', 'Orang tua berhasil dihubungkan.');
    }

    private function siswaBiodataRules(?int $siswaId = null): array
    {
        $nisnRule = ['nullable', 'string', 'max:20', 'unique:siswas,nisn'];
        if ($siswaId) {
            $nisnRule = ['nullable', 'string', 'max:20', 'unique:siswas,nisn,' . $siswaId];
        }

        return [
            'jenis_kelamin' => ['nullable', 'in:L,P'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'nisn' => $nisnRule,
            'nik' => ['nullable', 'string', 'max:16'],
            'agama' => ['nullable', 'string', 'max:30'],
            'alamat' => ['nullable', 'string'],
            'telepon' => ['nullable', 'string', 'max:30'],
            'status' => ['nullable', 'in:aktif,lulus,pindah,keluar'],
            'nama_ayah' => ['nullable', 'string', 'max:255'],
            'nama_ibu' => ['nullable', 'string', 'max:255'],
            'nama_wali' => ['nullable', 'string', 'max:255'],
        ];
    }

    private function siswaBiodataPayload(array $validated): array
    {
        return [
            'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
            'tempat_lahir' => $validated['tempat_lahir'] ?? null,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'nisn' => $validated['nisn'] ?? null,
            'nik' => $validated['nik'] ?? null,
            'agama' => $validated['agama'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'telepon' => $validated['telepon'] ?? null,
            'status' => $validated['status'] ?? 'aktif',
            'nama_ayah' => $validated['nama_ayah'] ?? null,
            'nama_ibu' => $validated['nama_ibu'] ?? null,
            'nama_wali' => $validated['nama_wali'] ?? null,
        ];
    }

    private function getOrCreateRole(string $name): Role
    {
        return Role::firstOrCreate(['name' => $name]);
    }
}
