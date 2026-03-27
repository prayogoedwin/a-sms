<?php

namespace App\Http\Controllers;

use App\Models\AbsensiSiswa;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\NilaiSiswa;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AkademikController extends Controller
{
    public function jadwalGuru(Request $request): View
    {
        $bolehGuru = $request->user()->hasPermission('view-jadwal-mengajar');
        $bolehSemua = $request->user()->hasPermission('view-jadwal-mengajar-semua');
        abort_unless($bolehGuru || $bolehSemua, 403);

        $tahunAjarans = TahunAjaran::query()
            ->orderByDesc('tanggal_mulai')
            ->orderByDesc('nama')
            ->get();

        $tahunAjaranTerbaru = $tahunAjarans->first();
        $tahunAjaranId = (int) $request->query('tahun_ajaran_id', $tahunAjaranTerbaru?->id);
        if ($tahunAjaranId && ! $tahunAjarans->contains('id', $tahunAjaranId)) {
            $tahunAjaranId = $tahunAjaranTerbaru?->id ?? 0;
        }

        $hariOrder = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
        $query = Jadwal::with(['kelas.tingkat', 'mataPelajaran', 'guru', 'tahunAjaran'])
            ->where('tahun_ajaran_id', $tahunAjaranId);

        $isAdminView = $bolehSemua;
        $guruFilter = $request->query('guru_id');
        $guruAktif = $this->getGuruAktif($request);

        if ($isAdminView) {
            if ($guruFilter !== null && $guruFilter !== '' && $guruFilter !== 'all') {
                $query->where('guru_id', (int) $guruFilter);
            }
        } else {
            abort_unless($guruAktif, 403);
            $query->where('guru_id', $guruAktif->id);
        }

        $jadwals = $query->get()->sort(function (Jadwal $a, Jadwal $b) use ($hariOrder) {
            $ha = array_search($a->hari, $hariOrder, true);
            $hb = array_search($b->hari, $hariOrder, true);
            if ($ha !== $hb) {
                return $ha <=> $hb;
            }
            return strcmp((string) $a->jam_mulai, (string) $b->jam_mulai);
        })->values();

        $gurus = Guru::orderBy('nama')->get();

        return view('akademik.jadwal-guru', [
            'jadwals' => $jadwals,
            'guru' => $guruAktif,
            'tahunAjarans' => $tahunAjarans,
            'tahunAjaranId' => $tahunAjaranId,
            'isAdminView' => $isAdminView,
            'guruFilter' => $guruFilter,
            'gurus' => $gurus,
        ]);
    }

    public function formNilai(Request $request, Jadwal $jadwal): View
    {
        $guru = $this->getGuruAktif($request);
        abort_if(! $guru || (int) $jadwal->guru_id !== (int) $guru->id, 403);

        $jadwal->load(['kelas.siswa', 'mataPelajaran']);
        $nilaiBySiswa = NilaiSiswa::where('jadwal_id', $jadwal->id)->get()->keyBy('siswa_id');

        return view('akademik.nilai', compact('jadwal', 'nilaiBySiswa'));
    }

    public function simpanNilai(Request $request, Jadwal $jadwal): RedirectResponse
    {
        $guru = $this->getGuruAktif($request);
        abort_if(! $guru || (int) $jadwal->guru_id !== (int) $guru->id, 403);

        $validated = $request->validate([
            'nilai' => ['nullable', 'array'],
            'nilai.*.nilai_angka' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai.*.catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        foreach (($validated['nilai'] ?? []) as $siswaId => $row) {
            NilaiSiswa::updateOrCreate(
                ['jadwal_id' => $jadwal->id, 'siswa_id' => $siswaId],
                [
                    'nilai_angka' => $row['nilai_angka'] ?? null,
                    'catatan' => $row['catatan'] ?? null,
                    'dinilai_oleh_guru_id' => $guru->id,
                ]
            );
        }

        return back()->with('status', 'Nilai berhasil disimpan.');
    }

    public function formAbsensi(Request $request, Jadwal $jadwal): View
    {
        $guru = $this->getGuruAktif($request);
        abort_if(! $guru || (int) $jadwal->guru_id !== (int) $guru->id, 403);

        $tanggal = $request->string('tanggal')->toString() ?: now()->toDateString();

        $jadwal->load(['kelas.siswa', 'mataPelajaran']);
        $absensiBySiswa = AbsensiSiswa::where('jadwal_id', $jadwal->id)
            ->whereDate('tanggal', $tanggal)
            ->get()
            ->keyBy('siswa_id');

        return view('akademik.absensi', compact('jadwal', 'tanggal', 'absensiBySiswa'));
    }

    public function simpanAbsensi(Request $request, Jadwal $jadwal): RedirectResponse
    {
        $guru = $this->getGuruAktif($request);
        abort_if(! $guru || (int) $jadwal->guru_id !== (int) $guru->id, 403);

        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'absensi' => ['nullable', 'array'],
            'absensi.*.status' => ['required_with:absensi', 'in:hadir,izin,sakit,terlambat,pulang_cepat,alpha'],
            'absensi.*.keterangan' => ['nullable', 'string', 'max:1000'],
        ]);

        foreach (($validated['absensi'] ?? []) as $siswaId => $row) {
            AbsensiSiswa::updateOrCreate(
                ['jadwal_id' => $jadwal->id, 'siswa_id' => $siswaId, 'tanggal' => $validated['tanggal']],
                [
                    'status' => $row['status'],
                    'keterangan' => $row['keterangan'] ?? null,
                    'diinput_oleh_guru_id' => $guru->id,
                ]
            );
        }

        return back()->with('status', 'Absensi berhasil disimpan.');
    }

    public function rekapAbsensi(Request $request, Jadwal $jadwal): View
    {
        $guru = $this->getGuruAktif($request);
        abort_if(! $guru || (int) $jadwal->guru_id !== (int) $guru->id, 403);

        $request->validate([
            'dari' => ['nullable', 'date'],
            'sampai' => ['nullable', 'date'],
        ]);

        $sampai = $request->date('sampai')?->toDateString() ?? now()->toDateString();
        $dari = $request->date('dari')?->toDateString() ?? now()->copy()->subDays(29)->toDateString();

        if ($dari > $sampai) {
            [$dari, $sampai] = [$sampai, $dari];
        }

        $jadwal->load(['kelas.siswa', 'mataPelajaran', 'kelas.tingkat']);

        $absensiRows = AbsensiSiswa::query()
            ->where('jadwal_id', $jadwal->id)
            ->whereBetween('tanggal', [$dari, $sampai])
            ->get(['siswa_id', 'status']);

        $countsBySiswa = [];
        foreach ($absensiRows as $row) {
            $sid = $row->siswa_id;
            $st = $row->status;
            $countsBySiswa[$sid][$st] = ($countsBySiswa[$sid][$st] ?? 0) + 1;
        }

        $statusKeys = ['hadir', 'izin', 'sakit', 'terlambat', 'pulang_cepat', 'alpha'];

        return view('akademik.rekap-absensi', compact(
            'jadwal',
            'dari',
            'sampai',
            'countsBySiswa',
            'statusKeys'
        ));
    }

    public function rekapWali(Request $request): View
    {
        $guru = $this->getGuruAktif($request);
        abort_if(! $guru, 403);

        $kelas = $guru->waliKelasDi()->with([
            'siswa',
            'jadwals.mataPelajaran',
            'jadwals.nilais',
            'jadwals.absensis',
        ])->get();

        return view('akademik.rekap-wali', compact('kelas', 'guru'));
    }

    public function jadwalSiswa(Request $request): View
    {
        abort_unless($request->user()->hasPermission('view-jadwal-siswa'), 403);

        $siswa = $this->getSiswaAktif($request);
        abort_if(! $siswa, 403);

        $tahunAjarans = TahunAjaran::query()
            ->orderByDesc('tanggal_mulai')
            ->orderByDesc('nama')
            ->get();

        $tahunAjaranTerbaru = $tahunAjarans->first();
        $tahunAjaranId = (int) $request->query('tahun_ajaran_id', $tahunAjaranTerbaru?->id);
        if ($tahunAjaranId && ! $tahunAjarans->contains('id', $tahunAjaranId)) {
            $tahunAjaranId = $tahunAjaranTerbaru?->id ?? 0;
        }

        $siswa->load(['kelas.tingkat']);

        if (! $siswa->kelas_id) {
            return view('akademik.siswa-jadwal', [
                'siswa' => $siswa,
                'jadwals' => collect(),
                'tahunAjarans' => $tahunAjarans,
                'tahunAjaranId' => $tahunAjaranId,
                'tanpaKelas' => true,
            ]);
        }

        $hariOrder = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
        $jadwals = Jadwal::with(['kelas.tingkat', 'mataPelajaran', 'guru', 'tahunAjaran'])
            ->where('kelas_id', $siswa->kelas_id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->get()
            ->sort(function (Jadwal $a, Jadwal $b) use ($hariOrder) {
                $ha = array_search($a->hari, $hariOrder, true);
                $hb = array_search($b->hari, $hariOrder, true);
                if ($ha !== $hb) {
                    return $ha <=> $hb;
                }

                return strcmp((string) $a->jam_mulai, (string) $b->jam_mulai);
            })
            ->values();

        return view('akademik.siswa-jadwal', [
            'siswa' => $siswa,
            'jadwals' => $jadwals,
            'tahunAjarans' => $tahunAjarans,
            'tahunAjaranId' => $tahunAjaranId,
            'tanpaKelas' => false,
        ]);
    }

    public function nilaiSiswa(Request $request): View
    {
        abort_unless($request->user()->hasPermission('view-nilai-siswa'), 403);

        $siswa = $this->getSiswaAktif($request);
        abort_if(! $siswa, 403);

        $tahunAjarans = TahunAjaran::query()
            ->orderByDesc('tanggal_mulai')
            ->orderByDesc('nama')
            ->get();

        $tahunAjaranTerbaru = $tahunAjarans->first();
        $tahunAjaranId = (int) $request->query('tahun_ajaran_id', $tahunAjaranTerbaru?->id);
        if ($tahunAjaranId && ! $tahunAjarans->contains('id', $tahunAjaranId)) {
            $tahunAjaranId = $tahunAjaranTerbaru?->id ?? 0;
        }

        $nilais = NilaiSiswa::query()
            ->where('siswa_id', $siswa->id)
            ->whereHas('jadwal', fn ($q) => $q->where('tahun_ajaran_id', $tahunAjaranId))
            ->with(['jadwal.mataPelajaran', 'jadwal.kelas.tingkat', 'jadwal.tahunAjaran', 'guruPenilai'])
            ->get()
            ->sortBy(fn (NilaiSiswa $n) => optional($n->jadwal?->mataPelajaran)->nama ?? '')
            ->values();

        return view('akademik.siswa-nilai', [
            'siswa' => $siswa,
            'nilais' => $nilais,
            'tahunAjarans' => $tahunAjarans,
            'tahunAjaranId' => $tahunAjaranId,
        ]);
    }

    public function absensiSiswa(Request $request): View
    {
        abort_unless($request->user()->hasPermission('view-absensi-siswa'), 403);

        $siswa = $this->getSiswaAktif($request);
        abort_if(! $siswa, 403);

        $tahunAjarans = TahunAjaran::query()
            ->orderByDesc('tanggal_mulai')
            ->orderByDesc('nama')
            ->get();

        $tahunAjaranTerbaru = $tahunAjarans->first();
        $tahunAjaranId = (int) $request->query('tahun_ajaran_id', $tahunAjaranTerbaru?->id);
        if ($tahunAjaranId && ! $tahunAjarans->contains('id', $tahunAjaranId)) {
            $tahunAjaranId = $tahunAjaranTerbaru?->id ?? 0;
        }

        $absensis = AbsensiSiswa::query()
            ->where('siswa_id', $siswa->id)
            ->whereHas('jadwal', fn ($q) => $q->where('tahun_ajaran_id', $tahunAjaranId))
            ->with(['jadwal.mataPelajaran', 'jadwal.tahunAjaran'])
            ->orderByDesc('tanggal')
            ->orderBy('jadwal_id')
            ->get();

        return view('akademik.siswa-absensi', [
            'siswa' => $siswa,
            'absensis' => $absensis,
            'tahunAjarans' => $tahunAjarans,
            'tahunAjaranId' => $tahunAjaranId,
        ]);
    }

    private function getGuruAktif(Request $request): ?Guru
    {
        return Guru::where('user_id', $request->user()->id)->first();
    }

    private function getSiswaAktif(Request $request): ?Siswa
    {
        return Siswa::where('user_id', $request->user()->id)->first();
    }
}
