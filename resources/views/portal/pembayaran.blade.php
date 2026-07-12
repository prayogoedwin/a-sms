<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Riwayat Pembayaran</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $siswa->nama }}</p>
    </div>

    <x-portal.anak-selector :anak-list="$anakList" :siswa="$siswa" />

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                    <th class="py-2 pr-4">Tanggal</th>
                    <th class="py-2 pr-4">Nominal</th>
                    <th class="py-2 pr-4">Metode</th>
                    <th class="py-2 pr-4">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pembayarans as $p)
                    <tr class="border-b border-gray-100 dark:border-gray-700">
                        <td class="py-2 pr-4">{{ $p->tanggal->format('d/m/Y') }}</td>
                        <td class="py-2 pr-4">{{ $ks->formatRupiah($p->total_nominal) }}</td>
                        <td class="py-2 pr-4 capitalize">{{ $p->metode }}</td>
                        <td class="py-2 pr-4">{{ $p->keterangan ?: '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layouts.app>
