<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Tagihan Anak</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $siswa->nama }}</p>
    </div>

    <x-portal.anak-selector :anak-list="$anakList" :siswa="$siswa" />

    <div class="space-y-4">
        @foreach($tagihans as $tagihan)
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex flex-wrap justify-between gap-2 mb-3">
                    <h2 class="font-semibold">{{ $ks->labelBulan($tagihan->bulan) }} {{ $tagihan->tahun }}</h2>
                    <span class="text-sm capitalize px-2 py-0.5 rounded {{ $ks->badgeClass($tagihan->status) }}">{{ $ks->labelStatus($tagihan->status) }}</span>
                </div>
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                            <th class="py-1 pr-4">Item</th>
                            <th class="py-1 pr-4">Nominal</th>
                            <th class="py-1 pr-4">Terbayar</th>
                            <th class="py-1 pr-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tagihan->details as $d)
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-1 pr-4">{{ $d->jenisPembayaran->nama }}</td>
                                <td class="py-1 pr-4">{{ $ks->formatRupiah($d->nominal) }}</td>
                                <td class="py-1 pr-4">{{ $ks->formatRupiah($d->nominal_terbayar) }}</td>
                                <td class="py-1 pr-4 capitalize">{{ str_replace('_', ' ', $d->status) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if(auth()->user()->hasPermission('cetak-tagihan-anak'))
                    <div class="mt-3">
                        <a href="{{ route('keuangan.tagihan-bulanan.cetak', $tagihan) }}" target="_blank" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Cetak tagihan</a>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</x-layouts.app>
