<?php

namespace App\Http\Controllers;

use App\Models\AbsensiSiswa;
use App\Models\Jadwal;
use App\Models\NilaiSiswa;
use App\Models\Pembayaran;
use App\Models\PengajuanPembayaran;
use App\Models\TagihanBulanan;
use App\Models\TagihanBulananDetail;
use App\Models\TahunAjaran;
use App\Services\KeuanganService;
use App\Services\OrangTuaService;
use App\Services\PengajuanPembayaranService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortalOrangTuaController extends Controller
{
    public function __construct(
        private OrangTuaService $orangTua,
        private KeuanganService $keuangan,
        private PengajuanPembayaranService $pengajuan
    ) {}

    public function profil(Request $request): View
    {
        abort_unless($request->user()->hasPermission('view-profil-anak'), 403);
        $data = $this->portalContext($request);

        return view('portal.profil', $data);
    }

    public function jadwal(Request $request): View
    {
        abort_unless($request->user()->hasPermission('view-jadwal-anak'), 403);
        $data = $this->portalContext($request);
        $siswa = $data['siswa'];

        $tahunAjarans = TahunAjaran::orderByDesc('tanggal_mulai')->get();
        $tahunAjaranId = (int) $request->query('tahun_ajaran_id', $tahunAjarans->first()?->id);

        $jadwals = collect();
        if ($siswa->kelas_id) {
            $hariOrder = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
            $jadwals = Jadwal::with(['kelas.tingkat', 'mataPelajaran', 'guru', 'tahunAjaran'])
                ->where('kelas_id', $siswa->kelas_id)
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->get()
                ->sort(fn ($a, $b) => array_search($a->hari, $hariOrder) <=> array_search($b->hari, $hariOrder))
                ->values();
        }

        return view('portal.jadwal', array_merge($data, compact('tahunAjarans', 'tahunAjaranId', 'jadwals')));
    }

    public function nilai(Request $request): View
    {
        abort_unless($request->user()->hasPermission('view-nilai-anak'), 403);
        $data = $this->portalContext($request);
        $siswa = $data['siswa'];

        $tahunAjarans = TahunAjaran::orderByDesc('tanggal_mulai')->get();
        $tahunAjaranId = (int) $request->query('tahun_ajaran_id', $tahunAjarans->first()?->id);

        $nilais = NilaiSiswa::where('siswa_id', $siswa->id)
            ->whereHas('jadwal', fn ($q) => $q->where('tahun_ajaran_id', $tahunAjaranId))
            ->with(['jadwal.mataPelajaran', 'jadwal.tahunAjaran'])
            ->get();

        return view('portal.nilai', array_merge($data, compact('tahunAjarans', 'tahunAjaranId', 'nilais')));
    }

    public function absensi(Request $request): View
    {
        abort_unless($request->user()->hasPermission('view-absensi-anak'), 403);
        $data = $this->portalContext($request);
        $siswa = $data['siswa'];

        $tahunAjarans = TahunAjaran::orderByDesc('tanggal_mulai')->get();
        $tahunAjaranId = (int) $request->query('tahun_ajaran_id', $tahunAjarans->first()?->id);

        $absensis = AbsensiSiswa::where('siswa_id', $siswa->id)
            ->whereHas('jadwal', fn ($q) => $q->where('tahun_ajaran_id', $tahunAjaranId))
            ->with(['jadwal.mataPelajaran'])
            ->orderByDesc('tanggal')
            ->get();

        return view('portal.absensi', array_merge($data, compact('tahunAjarans', 'tahunAjaranId', 'absensis')));
    }

    public function tagihan(Request $request): View
    {
        abort_unless($request->user()->hasPermission('view-tagihan-anak'), 403);
        $data = $this->portalContext($request);

        $tagihans = TagihanBulanan::with(['details.jenisPembayaran', 'tahunAjaran'])
            ->where('siswa_id', $data['siswa']->id)
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->get();

        return view('portal.tagihan', array_merge($data, compact('tagihans')));
    }

    public function pembayaran(Request $request): View
    {
        abort_unless($request->user()->hasPermission('view-pembayaran-anak'), 403);
        $data = $this->portalContext($request);

        $pembayarans = Pembayaran::where('siswa_id', $data['siswa']->id)
            ->orderByDesc('tanggal')
            ->get();

        return view('portal.pembayaran', array_merge($data, compact('pembayarans')));
    }

    public function pengajuanIndex(Request $request): View
    {
        abort_unless($request->user()->hasPermission('view-pengajuan-sendiri'), 403);
        $data = $this->portalContext($request);

        $query = PengajuanPembayaran::with('siswa')
            ->where('siswa_id', $data['siswa']->id)
            ->orderByDesc('created_at');

        if ($request->user()->orangTua) {
            $query->where('orang_tua_id', $request->user()->orangTua->id);
        }

        $pengajuans = $query->get();

        return view('portal.pengajuan', array_merge($data, compact('pengajuans')));
    }

    public function pengajuanCreate(Request $request): View
    {
        abort_unless($request->user()->hasPermission('ajukan-pembayaran'), 403);
        $data = $this->portalContext($request);

        $tagihanDetails = TagihanBulananDetail::with(['jenisPembayaran', 'tagihanBulanan'])
            ->whereHas('tagihanBulanan', fn ($q) => $q->where('siswa_id', $data['siswa']->id))
            ->whereIn('status', ['belum_lunas', 'sebagian'])
            ->get();

        return view('portal.pengajuan-create', array_merge($data, compact('tagihanDetails')));
    }

    public function pengajuanStore(Request $request): RedirectResponse
    {
        abort_unless($request->user()->hasPermission('ajukan-pembayaran'), 403);
        $data = $this->portalContext($request);
        $user = $request->user();
        $orangTua = $user->orangTua;

        abort_if(! $orangTua && ! $user->hasRole('super-admin'), 403);

        $items = collect($request->input('items', []))
            ->filter(fn ($i) => ! empty($i['detail_id']) && ! empty($i['nominal']) && (float) $i['nominal'] > 0)
            ->values()
            ->all();

        $validated = $request->validate([
            'tanggal_transfer' => ['required', 'date'],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'bukti' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        if (empty($items)) {
            return back()->withInput()->withErrors(['items' => 'Pilih minimal satu item tagihan.']);
        }

        if (! $orangTua) {
            return back()->with('error', 'Super admin tidak dapat mengajukan pembayaran tanpa profil orang tua.');
        }

        $this->pengajuan->store(
            $user,
            $data['siswa']->id,
            $orangTua->id,
            $validated,
            $request->file('bukti'),
            $items
        );

        return to_route('portal.pengajuan.index')->with('status', 'Pengajuan pembayaran berhasil dikirim. Menunggu verifikasi admin.');
    }

    private function portalContext(Request $request): array
    {
        abort_unless($this->orangTua->canAccessPortal($request->user()), 403);

        $anakList = $this->orangTua->getAccessibleSiswas($request->user());
        $siswa = $this->orangTua->resolveAnakAktif($request);

        abort_if(! $siswa, 403, 'Tidak ada data siswa yang dapat diakses.');

        return [
            'siswa' => $siswa,
            'anakList' => $anakList,
            'ks' => $this->keuangan,
            'pengajuanService' => $this->pengajuan,
        ];
    }
}
