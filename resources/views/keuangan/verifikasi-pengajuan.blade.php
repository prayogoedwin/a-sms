<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Verifikasi Pengajuan Pembayaran</h1>
    </div>

    @if(session('status'))
        <p class="mb-4 text-sm text-green-600 dark:text-green-400">{{ session('status') }}</p>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 overflow-x-auto">
        <table id="dt-verifikasi" class="min-w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                    <th class="py-2 pr-4">Tanggal</th>
                    <th class="py-2 pr-4">Siswa</th>
                    <th class="py-2 pr-4">Orang Tua</th>
                    <th class="py-2 pr-4">Nominal</th>
                    <th class="py-2 pr-4">Status</th>
                    <th class="py-2 pr-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengajuans as $p)
                    <tr class="border-b border-gray-100 dark:border-gray-700">
                        <td class="py-2 pr-4">{{ $p->created_at->format('d/m/Y H:i') }}</td>
                        <td class="py-2 pr-4">{{ $p->siswa->nama }}</td>
                        <td class="py-2 pr-4">{{ $p->orangTua->nama }}</td>
                        <td class="py-2 pr-4">{{ app(\App\Services\KeuanganService::class)->formatRupiah($p->total_nominal) }}</td>
                        <td class="py-2 pr-4"><span class="px-2 py-0.5 rounded text-xs {{ $pengajuanService->badgeClass($p->status) }}">{{ $pengajuanService->labelStatus($p->status) }}</span></td>
                        <td class="py-2 pr-4 text-right">
                            <a href="{{ route('keuangan.verifikasi-pengajuan.show', $p) }}" class="text-blue-600 dark:text-blue-400 hover:underline">Detail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <x-datatables-client table-id="dt-verifikasi" />
</x-layouts.app>
