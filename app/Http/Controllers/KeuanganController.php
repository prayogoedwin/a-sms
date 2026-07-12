<?php

namespace App\Http\Controllers;

use App\Exports\LaporanKeuanganExport;
use App\Models\JenisPembayaran;
use App\Models\Kelas;
use App\Models\Pembayaran;
use App\Models\PembayaranDetail;
use App\Models\PengajuanPembayaran;
use App\Models\Siswa;
use App\Models\TagihanBulanan;
use App\Models\TagihanBulananDetail;
use App\Models\TarifPembayaran;
use App\Models\TahunAjaran;
use App\Models\Tingkat;
use App\Services\KeuanganService;
use App\Services\PengajuanPembayaranService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class KeuanganController extends Controller
{
    public function __construct(
        private KeuanganService $keuangan,
        private PengajuanPembayaranService $pengajuan
    ) {}

    // --- Jenis Pembayaran ---

    public function jenisPembayaranIndex(): View
    {
        $jenisPembayarans = JenisPembayaran::orderBy('nama')->get();

        return view('keuangan.jenis-pembayaran', compact('jenisPembayarans'));
    }

    public function jenisPembayaranCreate(): View
    {
        return view('keuangan.jenis-pembayaran-create');
    }

    public function storeJenisPembayaran(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:30', 'unique:jenis_pembayarans,kode'],
            'nama' => ['required', 'string', 'max:255'],
            'frekuensi' => ['required', 'in:bulanan,tahunan,sekali'],
            'wajib' => ['nullable', 'boolean'],
            'bulan_berlaku' => ['nullable', 'integer', 'min:1', 'max:12'],
            'aktif' => ['nullable', 'boolean'],
        ]);

        JenisPembayaran::create([
            'kode' => strtoupper($validated['kode']),
            'nama' => $validated['nama'],
            'frekuensi' => $validated['frekuensi'],
            'wajib' => $request->boolean('wajib', true),
            'bulan_berlaku' => $validated['bulan_berlaku'] ?? null,
            'aktif' => $request->boolean('aktif', true),
        ]);

        return to_route('keuangan.jenis-pembayaran.index')->with('status', 'Jenis pembayaran berhasil ditambahkan.');
    }

    public function jenisPembayaranEdit(JenisPembayaran $jenisPembayaran): View
    {
        return view('keuangan.jenis-pembayaran-edit', compact('jenisPembayaran'));
    }

    public function jenisPembayaranUpdate(Request $request, JenisPembayaran $jenisPembayaran): RedirectResponse
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:30', 'unique:jenis_pembayarans,kode,' . $jenisPembayaran->id],
            'nama' => ['required', 'string', 'max:255'],
            'frekuensi' => ['required', 'in:bulanan,tahunan,sekali'],
            'wajib' => ['nullable', 'boolean'],
            'bulan_berlaku' => ['nullable', 'integer', 'min:1', 'max:12'],
            'aktif' => ['nullable', 'boolean'],
        ]);

        $jenisPembayaran->update([
            'kode' => strtoupper($validated['kode']),
            'nama' => $validated['nama'],
            'frekuensi' => $validated['frekuensi'],
            'wajib' => $request->boolean('wajib', true),
            'bulan_berlaku' => $validated['bulan_berlaku'] ?? null,
            'aktif' => $request->boolean('aktif', true),
        ]);

        return to_route('keuangan.jenis-pembayaran.index')->with('status', 'Jenis pembayaran berhasil diperbarui.');
    }

    public function jenisPembayaranDestroy(JenisPembayaran $jenisPembayaran): RedirectResponse
    {
        if ($jenisPembayaran->tagihanDetails()->exists()) {
            return back()->with('error', 'Jenis pembayaran tidak dapat dihapus karena sudah digunakan di tagihan.');
        }

        $jenisPembayaran->delete();

        return back()->with('status', 'Jenis pembayaran berhasil dihapus.');
    }

    // --- Tarif Pembayaran ---

    public function tarifPembayaranIndex(): View
    {
        $tarifs = TarifPembayaran::with(['jenisPembayaran', 'tingkat', 'tahunAjaran'])
            ->orderByDesc('tahun_ajaran_id')
            ->orderBy('tingkat_id')
            ->get();

        return view('keuangan.tarif-pembayaran', compact('tarifs'));
    }

    public function tarifPembayaranCreate(): View
    {
        $jenisPembayarans = JenisPembayaran::where('aktif', true)->orderBy('nama')->get();
        $tingkats = Tingkat::orderBy('urutan')->orderBy('nama')->get();
        $tahunAjarans = TahunAjaran::orderByDesc('tanggal_mulai')->get();

        return view('keuangan.tarif-pembayaran-create', compact('jenisPembayarans', 'tingkats', 'tahunAjarans'));
    }

    public function storeTarifPembayaran(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'jenis_pembayaran_id' => ['required', 'exists:jenis_pembayarans,id'],
            'tingkat_id' => ['required', 'exists:tingkats,id'],
            'tahun_ajaran_id' => ['required', 'exists:tahun_ajarans,id'],
            'nominal' => ['required', 'numeric', 'min:0'],
        ]);

        TarifPembayaran::create($validated);

        return to_route('keuangan.tarif-pembayaran.index')->with('status', 'Tarif pembayaran berhasil ditambahkan.');
    }

    public function tarifPembayaranEdit(TarifPembayaran $tarifPembayaran): View
    {
        $jenisPembayarans = JenisPembayaran::where('aktif', true)->orderBy('nama')->get();
        $tingkats = Tingkat::orderBy('urutan')->orderBy('nama')->get();
        $tahunAjarans = TahunAjaran::orderByDesc('tanggal_mulai')->get();

        return view('keuangan.tarif-pembayaran-edit', compact('tarifPembayaran', 'jenisPembayarans', 'tingkats', 'tahunAjarans'));
    }

    public function tarifPembayaranUpdate(Request $request, TarifPembayaran $tarifPembayaran): RedirectResponse
    {
        $validated = $request->validate([
            'jenis_pembayaran_id' => ['required', 'exists:jenis_pembayarans,id'],
            'tingkat_id' => ['required', 'exists:tingkats,id'],
            'tahun_ajaran_id' => ['required', 'exists:tahun_ajarans,id'],
            'nominal' => ['required', 'numeric', 'min:0'],
        ]);

        $tarifPembayaran->update($validated);

        return to_route('keuangan.tarif-pembayaran.index')->with('status', 'Tarif pembayaran berhasil diperbarui.');
    }

    public function tarifPembayaranDestroy(TarifPembayaran $tarifPembayaran): RedirectResponse
    {
        $tarifPembayaran->delete();

        return back()->with('status', 'Tarif pembayaran berhasil dihapus.');
    }

    // --- Tagihan Bulanan ---

    public function tagihanBulananIndex(Request $request): View
    {
        $tahunAjarans = TahunAjaran::orderByDesc('tanggal_mulai')->get();
        $kelasList = Kelas::with('tingkat')->orderBy('tingkat_id')->orderBy('nama_kelas')->get();

        $query = TagihanBulanan::with(['siswa.kelas.tingkat', 'tahunAjaran'])
            ->orderByDesc('tahun')
            ->orderByDesc('bulan');

        if ($request->filled('tahun_ajaran_id')) {
            $query->where('tahun_ajaran_id', $request->tahun_ajaran_id);
        }

        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        if ($request->filled('kelas_id')) {
            $query->whereHas('siswa', fn ($q) => $q->where('kelas_id', $request->kelas_id));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tagihans = $query->get();

        return view('keuangan.tagihan-bulanan', compact('tagihans', 'tahunAjarans', 'kelasList'));
    }

    public function tagihanBulananShow(TagihanBulanan $tagihanBulanan): View
    {
        $tagihanBulanan->load(['siswa.kelas.tingkat', 'tahunAjaran', 'details.jenisPembayaran']);

        return view('keuangan.tagihan-bulanan-show', compact('tagihanBulanan'));
    }

    public function tagihanBulananGenerateForm(): View
    {
        $tahunAjarans = TahunAjaran::orderByDesc('tanggal_mulai')->get();
        $kelasList = Kelas::with('tingkat')->orderBy('tingkat_id')->orderBy('nama_kelas')->get();

        return view('keuangan.tagihan-bulanan-generate', compact('tahunAjarans', 'kelasList'));
    }

    public function tagihanBulananGenerate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tahun_ajaran_id' => ['required', 'exists:tahun_ajarans,id'],
            'bulan' => ['required', 'integer', 'min:1', 'max:12'],
            'tahun' => ['required', 'integer', 'min:2000', 'max:2100'],
            'kelas_id' => ['nullable', 'exists:kelas,id'],
        ]);

        $result = $this->keuangan->generateTagihan(
            (int) $validated['tahun_ajaran_id'],
            (int) $validated['bulan'],
            (int) $validated['tahun'],
            $validated['kelas_id'] ?? null
        );

        return to_route('keuangan.tagihan-bulanan.index', [
            'tahun_ajaran_id' => $validated['tahun_ajaran_id'],
            'bulan' => $validated['bulan'],
            'tahun' => $validated['tahun'],
        ])->with('status', "Generate selesai: {$result['created']} tagihan dibuat, {$result['skipped']} dilewati.");
    }

    public function cetakTagihan(TagihanBulanan $tagihanBulanan): View
    {
        $tagihanBulanan->load(['siswa.kelas.tingkat', 'tahunAjaran', 'details.jenisPembayaran']);
        $tagihanBulanan->update(['dicetak_pada' => now()]);

        return view('keuangan.cetak-tagihan', [
            'tagihan' => $tagihanBulanan,
            'mode' => 'semua',
            'detail' => null,
        ]);
    }

    public function cetakTagihanItem(TagihanBulanan $tagihanBulanan, TagihanBulananDetail $detail): View
    {
        abort_unless($detail->tagihan_bulanan_id === $tagihanBulanan->id, 404);

        $tagihanBulanan->load(['siswa.kelas.tingkat', 'tahunAjaran']);
        $detail->load('jenisPembayaran');

        return view('keuangan.cetak-tagihan', [
            'tagihan' => $tagihanBulanan,
            'mode' => 'item',
            'detail' => $detail,
        ]);
    }

    // --- Pembayaran ---

    public function pembayaranIndex(): View
    {
        $pembayarans = Pembayaran::with(['siswa.kelas', 'dicatatOleh'])
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->get();

        return view('keuangan.pembayaran', compact('pembayarans'));
    }

    public function pembayaranCreate(Request $request): View
    {
        $siswa = null;
        $tagihanDetails = collect();

        if ($request->filled('siswa_id')) {
            $siswa = Siswa::with('kelas.tingkat')->find($request->siswa_id);
            $tagihanDetails = TagihanBulananDetail::with(['jenisPembayaran', 'tagihanBulanan.tahunAjaran'])
                ->whereHas('tagihanBulanan', fn ($q) => $q->where('siswa_id', $siswa->id))
                ->whereIn('status', ['belum_lunas', 'sebagian'])
                ->orderByDesc('id')
                ->get();
        }

        $siswas = Siswa::with('kelas')->orderBy('nama')->get();

        return view('keuangan.pembayaran-create', compact('siswas', 'siswa', 'tagihanDetails'));
    }

    public function storePembayaran(Request $request): RedirectResponse
    {
        $items = collect($request->input('items', []))
            ->filter(fn ($item) => ! empty($item['nominal']) && (float) $item['nominal'] > 0)
            ->values()
            ->all();

        $validated = $request->validate([
            'siswa_id' => ['required', 'exists:siswas,id'],
            'tanggal' => ['required', 'date'],
            'metode' => ['required', 'in:tunai,transfer'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        if (empty($items)) {
            return back()->withInput()->withErrors(['items' => 'Pilih minimal satu item tagihan.']);
        }

        foreach ($items as $index => $item) {
            if (empty($item['detail_id']) || ! TagihanBulananDetail::where('id', $item['detail_id'])->exists()) {
                return back()->withInput()->withErrors(["items.{$index}.detail_id" => 'Item tagihan tidak valid.']);
            }
        }

        DB::transaction(function () use ($validated, $items) {
            $total = collect($items)->sum('nominal');

            $pembayaran = Pembayaran::create([
                'siswa_id' => $validated['siswa_id'],
                'tanggal' => $validated['tanggal'],
                'total_nominal' => $total,
                'metode' => $validated['metode'],
                'keterangan' => $validated['keterangan'] ?? null,
                'dicatat_oleh' => auth()->id(),
            ]);

            $tagihanIds = [];

            foreach ($items as $item) {
                $detail = TagihanBulananDetail::with('tagihanBulanan')->findOrFail($item['detail_id']);
                abort_unless($detail->tagihanBulanan->siswa_id === (int) $validated['siswa_id'], 422);

                $sisa = (float) $detail->nominal - (float) $detail->nominal_terbayar;
                if ((float) $item['nominal'] > $sisa) {
                    abort(422, 'Nominal pembayaran melebihi sisa tagihan.');
                }

                PembayaranDetail::create([
                    'pembayaran_id' => $pembayaran->id,
                    'tagihan_bulanan_detail_id' => $detail->id,
                    'nominal' => $item['nominal'],
                ]);

                $detail->nominal_terbayar = (float) $detail->nominal_terbayar + (float) $item['nominal'];
                $this->keuangan->recalculateDetail($detail);

                $tagihanIds[$detail->tagihan_bulanan_id] = $detail->tagihan_bulanan_id;
            }

            foreach ($tagihanIds as $tagihanId) {
                $this->keuangan->recalculateTagihan(TagihanBulanan::with('details')->find($tagihanId));
            }
        });

        return to_route('keuangan.pembayaran.index')->with('status', 'Pembayaran berhasil dicatat.');
    }

    // --- Assign Jenis Pembayaran Siswa ---

    public function assignJenisPembayaranIndex(Request $request): View
    {
        $kelasList = Kelas::with('tingkat')->orderBy('tingkat_id')->orderBy('nama_kelas')->get();
        $optionalJenis = JenisPembayaran::where('wajib', false)->where('aktif', true)->orderBy('nama')->get();

        $query = Siswa::with(['kelas.tingkat', 'jenisPembayarans.jenisPembayaran'])
            ->orderBy('nama');

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($builder) use ($q) {
                $builder->where('nama', 'like', "%{$q}%")
                    ->orWhere('nis', 'like', "%{$q}%");
            });
        }

        $siswas = $query->get();

        return view('keuangan.assign-jenis-pembayaran', compact('siswas', 'kelasList', 'optionalJenis'));
    }

    public function assignJenisPembayaranEdit(Siswa $siswa): View
    {
        $siswa->load(['kelas.tingkat', 'jenisPembayarans']);
        $optionalJenis = JenisPembayaran::where('wajib', false)->where('aktif', true)->orderBy('nama')->get();

        return view('keuangan.assign-jenis-pembayaran-edit', compact('siswa', 'optionalJenis'));
    }

    public function assignJenisPembayaranUpdate(Request $request, Siswa $siswa): RedirectResponse
    {
        $optionalIds = JenisPembayaran::where('wajib', false)->where('aktif', true)->pluck('id')->all();

        $validated = $request->validate([
            'items' => ['nullable', 'array'],
            'items.*.jenis_pembayaran_id' => ['required', 'integer'],
            'items.*.aktif' => ['nullable', 'boolean'],
            'items.*.nominal_override' => ['nullable', 'numeric', 'min:0'],
        ]);

        $items = collect($validated['items'] ?? [])
            ->filter(fn ($item) => ! empty($item['aktif']) && in_array((int) $item['jenis_pembayaran_id'], $optionalIds, true))
            ->values()
            ->all();

        $this->keuangan->syncSiswaJenisPembayaran($siswa, $items);

        return to_route('keuangan.assign-jenis-pembayaran.edit', $siswa)
            ->with('status', 'Jenis pembayaran siswa berhasil diperbarui.');
    }

    // --- Laporan ---

    public function laporanIndex(Request $request): View
    {
        $tahunAjarans = TahunAjaran::orderByDesc('tanggal_mulai')->get();
        $kelasList = Kelas::with('tingkat')->orderBy('tingkat_id')->orderBy('nama_kelas')->get();
        $filters = $this->keuangan->parseLaporanFilters($request->all());
        $summary = $this->keuangan->getLaporanSummary($filters);

        $tunggakanQuery = TagihanBulanan::with(['siswa.kelas'])
            ->whereIn('status', ['belum_lunas', 'sebagian'])
            ->orderByDesc('tahun')
            ->orderByDesc('bulan');

        $this->keuangan->applyTagihanFilters($tunggakanQuery, $filters);
        $tunggakan = $tunggakanQuery->get();

        return view('keuangan.laporan', [
            'tahunAjarans' => $tahunAjarans,
            'kelasList' => $kelasList,
            'totalTagihan' => $summary['total_tagihan'],
            'totalTerbayar' => $summary['total_terbayar'],
            'totalPemasukan' => $summary['total_pemasukan'],
            'belumLunas' => $summary['belum_lunas'],
            'sebagian' => $summary['sebagian'],
            'lunas' => $summary['lunas'],
            'tunggakan' => $tunggakan,
        ]);
    }

    public function laporanExport(Request $request): BinaryFileResponse
    {
        $filters = $this->keuangan->parseLaporanFilters($request->all());

        return Excel::download(
            new LaporanKeuanganExport($filters),
            'laporan-keuangan-' . date('Y-m-d') . '.xlsx'
        );
    }

    // --- Verifikasi Pengajuan Pembayaran ---

    public function verifikasiPengajuanIndex(): View
    {
        $pengajuans = PengajuanPembayaran::with(['siswa.kelas', 'orangTua'])
            ->orderByRaw("FIELD(status, 'menunggu', 'disetujui', 'ditolak')")
            ->orderByDesc('created_at')
            ->get();

        return view('keuangan.verifikasi-pengajuan', [
            'pengajuans' => $pengajuans,
            'pengajuanService' => $this->pengajuan,
        ]);
    }

    public function verifikasiPengajuanShow(PengajuanPembayaran $pengajuan): View
    {
        $pengajuan->load(['siswa.kelas', 'orangTua.user', 'details.tagihanBulananDetail.jenisPembayaran', 'details.tagihanBulananDetail.tagihanBulanan', 'diverifikasiOleh']);

        return view('keuangan.verifikasi-pengajuan-show', [
            'pengajuan' => $pengajuan,
            'ks' => $this->keuangan,
            'pengajuanService' => $this->pengajuan,
        ]);
    }

    public function verifikasiPengajuanApprove(PengajuanPembayaran $pengajuan): RedirectResponse
    {
        try {
            $this->pengajuan->approve($pengajuan, auth()->user());
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return to_route('keuangan.verifikasi-pengajuan.show', $pengajuan)
            ->with('status', 'Pengajuan pembayaran berhasil disetujui.');
    }

    public function verifikasiPengajuanReject(Request $request, PengajuanPembayaran $pengajuan): RedirectResponse
    {
        $validated = $request->validate([
            'catatan_admin' => ['required', 'string', 'max:500'],
        ]);

        try {
            $this->pengajuan->reject($pengajuan, auth()->user(), $validated['catatan_admin']);
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return to_route('keuangan.verifikasi-pengajuan.show', $pengajuan)
            ->with('status', 'Pengajuan pembayaran ditolak.');
    }
}
