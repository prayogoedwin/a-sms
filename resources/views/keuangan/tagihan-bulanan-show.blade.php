@php $ks = app(\App\Services\KeuanganService::class); @endphp
<x-layouts.app>
    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Detail Tagihan</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">
                {{ $tagihanBulanan->siswa->nama }} —
                {{ $ks::BULAN_NAMA[$tagihanBulanan->bulan] }} {{ $tagihanBulanan->tahun }}
            </p>
        </div>
        <div class="flex gap-2">
            @if(auth()->user()->hasPermission('cetak-tagihan'))
                <a href="{{ route('keuangan.tagihan-bulanan.cetak', $tagihanBulanan) }}" target="_blank"><x-button type="primary">Cetak Semua</x-button></a>
            @endif
            <a href="{{ route('keuangan.tagihan-bulanan.index') }}"><x-button type="secondary">Kembali</x-button></a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg border p-4">
            <p class="text-xs text-gray-500">Total Tagihan</p>
            <p class="text-lg font-semibold">{{ $ks->formatRupiah($tagihanBulanan->total_nominal) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg border p-4">
            <p class="text-xs text-gray-500">Terbayar</p>
            <p class="text-lg font-semibold">{{ $ks->formatRupiah($tagihanBulanan->total_terbayar) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg border p-4">
            <p class="text-xs text-gray-500">Status</p>
            <p><span class="px-2 py-0.5 rounded text-xs {{ $ks->badgeClass($tagihanBulanan->status) }}">{{ $ks->labelStatus($tagihanBulanan->status) }}</span></p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                    <th class="py-2 pr-4">Jenis Pembayaran</th>
                    <th class="py-2 pr-4">Nominal</th>
                    <th class="py-2 pr-4">Terbayar</th>
                    <th class="py-2 pr-4">Sisa</th>
                    <th class="py-2 pr-4">Status</th>
                    <th class="py-2 pr-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tagihanBulanan->details as $detail)
                    <tr class="border-b border-gray-100 dark:border-gray-700">
                        <td class="py-2 pr-4">{{ $detail->jenisPembayaran->nama }}</td>
                        <td class="py-2 pr-4">{{ $ks->formatRupiah($detail->nominal) }}</td>
                        <td class="py-2 pr-4">{{ $ks->formatRupiah($detail->nominal_terbayar) }}</td>
                        <td class="py-2 pr-4">{{ $ks->formatRupiah($detail->nominal - $detail->nominal_terbayar) }}</td>
                        <td class="py-2 pr-4">
                            <span class="px-2 py-0.5 rounded text-xs {{ $ks->badgeClass($detail->status) }}">{{ $ks->labelStatus($detail->status) }}</span>
                        </td>
                        <td class="py-2 pr-4 text-right">
                            @if(auth()->user()->hasPermission('cetak-tagihan'))
                                <a href="{{ route('keuangan.tagihan-bulanan.cetak-item', [$tagihanBulanan, $detail]) }}" target="_blank" class="text-green-600 dark:text-green-400 hover:underline">Cetak Item</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layouts.app>
