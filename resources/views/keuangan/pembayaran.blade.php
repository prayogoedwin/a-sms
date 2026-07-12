@php $ks = app(\App\Services\KeuanganService::class); @endphp
<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Riwayat Pembayaran</h1>
    </div>

    @if(session('status'))
        <p class="mb-4 text-sm text-green-600 dark:text-green-400">{{ session('status') }}</p>
    @endif

    <div class="mb-4">
        @if(auth()->user()->hasPermission('input-pembayaran'))
            <a href="{{ route('keuangan.pembayaran.create') }}"><x-button type="primary">Input Pembayaran</x-button></a>
        @endif
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 overflow-x-auto">
        <table id="dt-pembayaran" class="min-w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                    <th class="py-2 pr-4">Tanggal</th>
                    <th class="py-2 pr-4">Siswa</th>
                    <th class="py-2 pr-4">Kelas</th>
                    <th class="py-2 pr-4">Nominal</th>
                    <th class="py-2 pr-4">Metode</th>
                    <th class="py-2 pr-4">Dicatat Oleh</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pembayarans as $p)
                    <tr class="border-b border-gray-100 dark:border-gray-700">
                        <td class="py-2 pr-4">{{ $p->tanggal->format('d/m/Y') }}</td>
                        <td class="py-2 pr-4">{{ $p->siswa->nama }}</td>
                        <td class="py-2 pr-4">{{ $p->siswa->kelas ? $p->siswa->kelas->tingkat->nama . ' ' . $p->siswa->kelas->nama_kelas : '—' }}</td>
                        <td class="py-2 pr-4">{{ $ks->formatRupiah($p->total_nominal) }}</td>
                        <td class="py-2 pr-4 capitalize">{{ $p->metode }}</td>
                        <td class="py-2 pr-4">{{ $p->dicatatOleh->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <x-datatables-client table-id="dt-pembayaran" />
</x-layouts.app>
