@php $ks = app(\App\Services\KeuanganService::class); @endphp
<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Laporan Keuangan</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Rekap tagihan dan tunggakan.</p>
    </div>

    <form method="GET" class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 mb-6 grid grid-cols-1 md:grid-cols-4 gap-3">
        <div>
            <label class="text-xs text-gray-500">Tahun Ajaran</label>
            <select name="tahun_ajaran_id" class="w-full px-3 py-1.5 rounded-lg text-sm bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600">
                <option value="">Semua</option>
                @foreach($tahunAjarans as $ta)
                    <option value="{{ $ta->id }}" @selected(request('tahun_ajaran_id') == $ta->id)>{{ $ta->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs text-gray-500">Bulan</label>
            <select name="bulan" class="w-full px-3 py-1.5 rounded-lg text-sm bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600">
                <option value="">Semua</option>
                @foreach(\App\Services\KeuanganService::BULAN_NAMA as $num => $nama)
                    <option value="{{ $num }}" @selected(request('bulan') == $num)>{{ $nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs text-gray-500">Tahun</label>
            <input type="number" name="tahun" value="{{ request('tahun') }}" class="w-full px-3 py-1.5 rounded-lg text-sm bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600">
        </div>
        <div>
            <label class="text-xs text-gray-500">Kelas</label>
            <select name="kelas_id" class="w-full px-3 py-1.5 rounded-lg text-sm bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600">
                <option value="">Semua</option>
                @foreach($kelasList as $kelas)
                    <option value="{{ $kelas->id }}" @selected(request('kelas_id') == $kelas->id)>{{ $kelas->tingkat->nama }} {{ $kelas->nama_kelas }}</option>
                @endforeach
            </select>
        </div>
        <div class="md:col-span-4 flex flex-wrap gap-2">
            <x-button type="secondary">Tampilkan</x-button>
            @if(auth()->user()->hasPermission('download-laporan-keuangan'))
                <a href="{{ route('keuangan.laporan.export', request()->query()) }}">
                    <x-button type="primary">Export Excel</x-button>
                </a>
            @endif
        </div>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg border p-4">
            <p class="text-xs text-gray-500">Total Tagihan</p>
            <p class="text-xl font-semibold">{{ $ks->formatRupiah($totalTagihan) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg border p-4">
            <p class="text-xs text-gray-500">Total Terbayar</p>
            <p class="text-xl font-semibold">{{ $ks->formatRupiah($totalTerbayar) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg border p-4">
            <p class="text-xs text-gray-500">Pemasukan (transaksi)</p>
            <p class="text-xl font-semibold">{{ $ks->formatRupiah($totalPemasukan) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-red-50 dark:bg-red-900/20 rounded-lg border p-4 text-center">
            <p class="text-2xl font-bold">{{ $belumLunas }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400">Belum Lunas</p>
        </div>
        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border p-4 text-center">
            <p class="text-2xl font-bold">{{ $sebagian }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400">Sebagian</p>
        </div>
        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg border p-4 text-center">
            <p class="text-2xl font-bold">{{ $lunas }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-400">Lunas</p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 overflow-x-auto">
        <h2 class="font-semibold mb-3">Daftar Tunggakan</h2>
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left border-b">
                    <th class="py-2 pr-4">Siswa</th>
                    <th class="py-2 pr-4">Kelas</th>
                    <th class="py-2 pr-4">Periode</th>
                    <th class="py-2 pr-4">Total</th>
                    <th class="py-2 pr-4">Sisa</th>
                    <th class="py-2 pr-4">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tunggakan as $t)
                    <tr class="border-b border-gray-100 dark:border-gray-700">
                        <td class="py-2 pr-4">{{ $t->siswa->nama }}</td>
                        <td class="py-2 pr-4">{{ $t->siswa->kelas ? $t->siswa->kelas->tingkat->nama . ' ' . $t->siswa->kelas->nama_kelas : '—' }}</td>
                        <td class="py-2 pr-4">{{ $ks::BULAN_NAMA[$t->bulan] }} {{ $t->tahun }}</td>
                        <td class="py-2 pr-4">{{ $ks->formatRupiah($t->total_nominal) }}</td>
                        <td class="py-2 pr-4">{{ $ks->formatRupiah($t->total_nominal - $t->total_terbayar) }}</td>
                        <td class="py-2 pr-4">
                            <span class="px-2 py-0.5 rounded text-xs {{ $ks->badgeClass($t->status) }}">{{ $ks->labelStatus($t->status) }}</span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-4 text-gray-500">Tidak ada tunggakan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
