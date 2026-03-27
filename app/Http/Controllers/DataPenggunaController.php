<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Pegawai;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class DataPenggunaController extends Controller
{
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

            $user->assignRole($this->getOrCreateRole('pegawai'));

            Pegawai::create([
                'user_id' => $user->id,
                'nama' => $validated['nama'],
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
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $user->assignRole($this->getOrCreateRole('siswa'));

            Siswa::create([
                'user_id' => $user->id,
                'kelas_id' => $validated['kelas_id'] ?? null,
                'nama' => $validated['nama'],
                'nis' => $validated['nis'] ?? null,
            ]);
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

            $pegawai->update([
                'nama' => $validated['nama'],
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
        $siswa->load('user', 'kelas');
        $kelasList = Kelas::with('tingkat')->orderBy('tingkat_id')->orderBy('nama_kelas')->get();
        return view('data-pengguna.siswa-edit', compact('siswa', 'kelasList'));
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
        ]);

        DB::transaction(function () use ($siswa, $validated) {
            $siswa->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            if (! empty($validated['password'])) {
                $siswa->user->update(['password' => Hash::make($validated['password'])]);
            }

            $siswa->update([
                'nama' => $validated['nama'],
                'nis' => $validated['nis'] ?? null,
                'kelas_id' => $validated['kelas_id'] ?? null,
            ]);
        });

        return to_route('data-pengguna.siswa.index')->with('status', 'Data siswa berhasil diperbarui.');
    }

    public function siswaDestroy(Siswa $siswa): RedirectResponse
    {
        $siswa->user()->delete();
        return back()->with('status', 'Data siswa berhasil dihapus.');
    }

    private function getOrCreateRole(string $name): Role
    {
        return Role::firstOrCreate(['name' => $name]);
    }
}
