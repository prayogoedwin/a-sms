<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Detail Pengajuan #{{ $pengajuan->id }}</h1>
        <a href="{{ route('keuangan.verifikasi-pengajuan.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">← Kembali</a>
    </div>

    @if(session('status'))
        <p class="mb-4 text-sm text-green-600 dark:text-green-400">{{ session('status') }}</p>
    @endif
    @if(session('error'))
        <p class="mb-4 text-sm text-red-600 dark:text-red-400">{{ session('error') }}</p>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <dl class="space-y-2 text-sm">
                <div><dt class="text-gray-500">Siswa</dt><dd class="font-medium">{{ $pengajuan->siswa->nama }} @if($pengajuan->siswa->kelas)({{ $pengajuan->siswa->kelas->tingkat->nama }} {{ $pengajuan->siswa->kelas->nama_kelas }})@endif</dd></div>
                <div><dt class="text-gray-500">Orang Tua</dt><dd>{{ $pengajuan->orangTua->nama }} ({{ $pengajuan->orangTua->user->email }})</dd></div>
                <div><dt class="text-gray-500">Tanggal Transfer</dt><dd>{{ $pengajuan->tanggal_transfer->format('d/m/Y') }}</dd></div>
                <div><dt class="text-gray-500">Total</dt><dd class="font-semibold">{{ $ks->formatRupiah($pengajuan->total_nominal) }}</dd></div>
                <div><dt class="text-gray-500">Status</dt><dd><span class="px-2 py-0.5 rounded text-xs {{ $pengajuanService->badgeClass($pengajuan->status) }}">{{ $pengajuanService->labelStatus($pengajuan->status) }}</span></dd></div>
                @if($pengajuan->keterangan)<div><dt class="text-gray-500">Keterangan</dt><dd>{{ $pengajuan->keterangan }}</dd></div>@endif
                @if($pengajuan->catatan_admin)<div><dt class="text-gray-500">Catatan Admin</dt><dd>{{ $pengajuan->catatan_admin }}</dd></div>@endif
                @if($pengajuan->diverifikasiOleh)<div><dt class="text-gray-500">Diverifikasi</dt><dd>{{ $pengajuan->diverifikasiOleh->name }} — {{ $pengajuan->diverifikasi_pada?->format('d/m/Y H:i') }}</dd></div>@endif
            </dl>

            @if($pengajuan->bukti_path)
                <div class="mt-4">
                    <p class="text-sm text-gray-500 mb-2">Bukti transfer:</p>
                    <a href="{{ asset('storage/' . $pengajuan->bukti_path) }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline text-sm">Lihat bukti</a>
                </div>
            @endif
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="font-semibold mb-3">Rincian Item</h2>
            <table class="min-w-full text-sm mb-6">
                <thead>
                    <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                        <th class="py-1 pr-4">Item</th>
                        <th class="py-1 pr-4">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengajuan->details as $d)
                        <tr class="border-b border-gray-100 dark:border-gray-700">
                            <td class="py-1 pr-4">{{ $d->tagihanBulananDetail->jenisPembayaran->nama }} ({{ $ks->labelBulan($d->tagihanBulananDetail->tagihanBulanan->bulan) }} {{ $d->tagihanBulananDetail->tagihanBulanan->tahun }})</td>
                            <td class="py-1 pr-4">{{ $ks->formatRupiah($d->nominal) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($pengajuan->status === 'menunggu' && auth()->user()->hasPermission('verifikasi-pembayaran'))
                <div class="space-y-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                    <form method="POST" action="{{ route('keuangan.verifikasi-pengajuan.approve', $pengajuan) }}" onsubmit="return confirm('Setujui pengajuan ini?')">
                        @csrf
                        <x-button type="primary">Setujui & Catat Pembayaran</x-button>
                    </form>
                    <form method="POST" action="{{ route('keuangan.verifikasi-pengajuan.reject', $pengajuan) }}" class="space-y-2">
                        @csrf
                        <label class="block text-sm text-gray-700 dark:text-gray-300">Alasan penolakan</label>
                        <textarea name="catatan_admin" required class="w-full px-3 py-2 rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 text-sm" rows="3"></textarea>
                        <x-button type="secondary">Tolak Pengajuan</x-button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
