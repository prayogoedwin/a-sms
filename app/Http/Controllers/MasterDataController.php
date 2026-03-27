<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use App\Models\Tingkat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MasterDataController extends Controller
{
    public function tingkatIndex(): View
    {
        $tingkats = Tingkat::orderBy('urutan')->orderBy('nama')->get();

        return view('master-data.tingkat', compact('tingkats'));
    }

    public function kelasIndex(): View
    {
        $kelas = Kelas::with(['tingkat', 'waliKelas'])->orderByDesc('id')->get();
        return view('master-data.kelas', compact('kelas'));
    }

    public function mataPelajaranIndex(): View
    {
        $mataPelajarans = MataPelajaran::orderBy('nama')->get();
        return view('master-data.mapel', compact('mataPelajarans'));
    }

    public function penjadwalanIndex(): View
    {
        $jadwals = Jadwal::with(['kelas.tingkat', 'mataPelajaran', 'guru', 'tahunAjaran'])->orderByDesc('id')->get();
        return view('master-data.penjadwalan', compact('jadwals'));
    }

    public function tahunAjaranIndex(): View
    {
        $tahunAjarans = TahunAjaran::orderByDesc('tanggal_mulai')->orderByDesc('nama')->get();
        return view('master-data.tahun-ajaran', compact('tahunAjarans'));
    }

    public function tahunAjaranCreate(): View
    {
        return view('master-data.tahun-ajaran-create');
    }

    public function storeTahunAjaran(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:30', 'unique:tahun_ajarans,nama'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
        ]);

        TahunAjaran::create($validated);
        return to_route('master-data.tahun-ajaran.index')->with('status', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function tahunAjaranEdit(TahunAjaran $tahunAjaran): View
    {
        return view('master-data.tahun-ajaran-edit', compact('tahunAjaran'));
    }

    public function tahunAjaranUpdate(Request $request, TahunAjaran $tahunAjaran): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:30', 'unique:tahun_ajarans,nama,' . $tahunAjaran->id],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
        ]);

        $tahunAjaran->update($validated);
        return to_route('master-data.tahun-ajaran.index')->with('status', 'Tahun ajaran berhasil diperbarui.');
    }

    public function tahunAjaranDestroy(TahunAjaran $tahunAjaran): RedirectResponse
    {
        if ($tahunAjaran->jadwals()->exists()) {
            return back()->with('error', 'Tahun ajaran tidak bisa dihapus karena masih dipakai jadwal.');
        }
        $tahunAjaran->delete();
        return back()->with('status', 'Tahun ajaran berhasil dihapus.');
    }

    public function tingkatCreate(): View
    {
        return view('master-data.tingkat-create');
    }

    public function kelasCreate(): View
    {
        $tingkats = Tingkat::orderBy('urutan')->orderBy('nama')->get();
        $gurus = Guru::orderBy('nama')->get();
        return view('master-data.kelas-create', compact('tingkats', 'gurus'));
    }

    public function mataPelajaranCreate(): View
    {
        return view('master-data.mapel-create');
    }

    public function jadwalCreate(): View
    {
        $kelas = Kelas::with('tingkat')->orderBy('tingkat_id')->orderBy('nama_kelas')->get();
        $mataPelajarans = MataPelajaran::orderBy('nama')->get();
        $gurus = Guru::orderBy('nama')->get();
        $tahunAjarans = TahunAjaran::orderByDesc('tanggal_mulai')->orderByDesc('nama')->get();
        return view('master-data.penjadwalan-create', compact('kelas', 'mataPelajarans', 'gurus', 'tahunAjarans'));
    }

    public function storeTingkat(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:50'],
            'urutan' => ['nullable', 'integer', 'min:1', 'max:12'],
        ]);

        Tingkat::create($validated);
        return to_route('master-data.tingkat.index')->with('status', 'Tingkat berhasil ditambahkan.');
    }

    public function storeKelas(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tingkat_id' => ['required', 'exists:tingkats,id'],
            'nama_kelas' => ['required', 'string', 'max:50'],
            'wali_kelas_guru_id' => ['nullable', 'exists:gurus,id'],
        ]);

        Kelas::create($validated);
        return to_route('master-data.kelas.index')->with('status', 'Kelas berhasil ditambahkan.');
    }

    public function storeMataPelajaran(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode' => ['nullable', 'string', 'max:20', 'unique:mata_pelajarans,kode'],
            'nama' => ['required', 'string', 'max:255'],
        ]);

        MataPelajaran::create($validated);
        return to_route('master-data.mapel.index')->with('status', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function storeJadwal(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kelas_id' => ['required', 'exists:kelas,id'],
            'mata_pelajaran_id' => ['required', 'exists:mata_pelajarans,id'],
            'guru_id' => ['required', 'exists:gurus,id'],
            'hari' => ['required', 'in:senin,selasa,rabu,kamis,jumat,sabtu'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'tahun_ajaran_id' => ['required', 'exists:tahun_ajarans,id'],
            'semester' => ['required', 'in:ganjil,genap'],
        ]);

        Jadwal::create($validated);
        return to_route('master-data.penjadwalan.index')->with('status', 'Jadwal berhasil ditambahkan.');
    }

    public function tingkatEdit(Tingkat $tingkat): View
    {
        return view('master-data.tingkat-edit', compact('tingkat'));
    }

    public function tingkatUpdate(Request $request, Tingkat $tingkat): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:50'],
            'urutan' => ['nullable', 'integer', 'min:1', 'max:12'],
        ]);

        $tingkat->update($validated);
        return to_route('master-data.tingkat.index')->with('status', 'Tingkat berhasil diperbarui.');
    }

    public function tingkatDestroy(Tingkat $tingkat): RedirectResponse
    {
        $tingkat->delete();
        return back()->with('status', 'Tingkat berhasil dihapus.');
    }

    public function kelasEdit(Kelas $kelas): View
    {
        $tingkats = Tingkat::orderBy('urutan')->orderBy('nama')->get();
        $gurus = Guru::orderBy('nama')->get();
        return view('master-data.kelas-edit', compact('kelas', 'tingkats', 'gurus'));
    }

    public function kelasUpdate(Request $request, Kelas $kelas): RedirectResponse
    {
        $validated = $request->validate([
            'tingkat_id' => ['required', 'exists:tingkats,id'],
            'nama_kelas' => ['required', 'string', 'max:50'],
            'wali_kelas_guru_id' => ['nullable', 'exists:gurus,id'],
        ]);

        $kelas->update($validated);
        return to_route('master-data.kelas.index')->with('status', 'Kelas berhasil diperbarui.');
    }

    public function kelasDestroy(Kelas $kelas): RedirectResponse
    {
        $kelas->delete();
        return back()->with('status', 'Kelas berhasil dihapus.');
    }

    public function mataPelajaranEdit(MataPelajaran $mataPelajaran): View
    {
        return view('master-data.mapel-edit', compact('mataPelajaran'));
    }

    public function mataPelajaranUpdate(Request $request, MataPelajaran $mataPelajaran): RedirectResponse
    {
        $validated = $request->validate([
            'kode' => ['nullable', 'string', 'max:20', 'unique:mata_pelajarans,kode,' . $mataPelajaran->id],
            'nama' => ['required', 'string', 'max:255'],
        ]);

        $mataPelajaran->update($validated);
        return to_route('master-data.mapel.index')->with('status', 'Mata pelajaran berhasil diperbarui.');
    }

    public function mataPelajaranDestroy(MataPelajaran $mataPelajaran): RedirectResponse
    {
        $mataPelajaran->delete();
        return back()->with('status', 'Mata pelajaran berhasil dihapus.');
    }

    public function jadwalEdit(Jadwal $jadwal): View
    {
        $kelas = Kelas::with('tingkat')->orderBy('tingkat_id')->orderBy('nama_kelas')->get();
        $mataPelajarans = MataPelajaran::orderBy('nama')->get();
        $gurus = Guru::orderBy('nama')->get();
        $tahunAjarans = TahunAjaran::orderByDesc('tanggal_mulai')->orderByDesc('nama')->get();
        return view('master-data.penjadwalan-edit', compact('jadwal', 'kelas', 'mataPelajarans', 'gurus', 'tahunAjarans'));
    }

    public function jadwalUpdate(Request $request, Jadwal $jadwal): RedirectResponse
    {
        $validated = $request->validate([
            'kelas_id' => ['required', 'exists:kelas,id'],
            'mata_pelajaran_id' => ['required', 'exists:mata_pelajarans,id'],
            'guru_id' => ['required', 'exists:gurus,id'],
            'hari' => ['required', 'in:senin,selasa,rabu,kamis,jumat,sabtu'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'tahun_ajaran_id' => ['required', 'exists:tahun_ajarans,id'],
            'semester' => ['required', 'in:ganjil,genap'],
        ]);

        $jadwal->update($validated);
        return to_route('master-data.penjadwalan.index')->with('status', 'Jadwal berhasil diperbarui.');
    }

    public function jadwalDestroy(Jadwal $jadwal): RedirectResponse
    {
        $jadwal->delete();
        return back()->with('status', 'Jadwal berhasil dihapus.');
    }
}
