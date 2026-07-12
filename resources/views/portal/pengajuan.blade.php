<x-layouts.app>
    <div class="mb-6 flex flex-wrap justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Pengajuan Pembayaran</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $siswa->nama }}</p>
        </div>
        @if(auth()->user()->hasPermission('ajukan-pembayaran') && auth()->user()->orangTua)
            <a href="{{ route('portal.pengajuan.create', ['anak_id' => $siswa->id]) }}"><x-button type="primary">Ajukan Pembayaran</x-button></a>
        @endif
    </div>

    <x-portal.anak-selector :anak-list="$anakList" :siswa="$siswa" />

    @if(session('status'))
        <p class="mb-4 text-sm text-green-600 dark:text-green-400">{{ session('status') }}</p>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                    <th class="py-2 pr-4">Tanggal Transfer</th>
                    <th class="py-2 pr-4">Nominal</th>
                    <th class="py-2 pr-4">Status</th>
                    <th class="py-2 pr-4">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengajuans as $p)
                    <tr class="border-b border-gray-100 dark:border-gray-700">
                        <td class="py-2 pr-4">{{ $p->tanggal_transfer->format('d/m/Y') }}</td>
                        <td class="py-2 pr-4">{{ $ks->formatRupiah($p->total_nominal) }}</td>
                        <td class="py-2 pr-4"><span class="px-2 py-0.5 rounded text-xs {{ $pengajuanService->badgeClass($p->status) }}">{{ $pengajuanService->labelStatus($p->status) }}</span></td>
                        <td class="py-2 pr-4">{{ $p->keterangan ?: '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layouts.app>
