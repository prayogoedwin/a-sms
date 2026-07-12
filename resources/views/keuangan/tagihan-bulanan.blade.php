@php $ks = app(\App\Services\KeuanganService::class); @endphp
<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Tagihan Bulanan</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Daftar tagihan pembayaran siswa per bulan.</p>
    </div>

    @if(session('status'))
        <p class="mb-4 text-sm text-green-600 dark:text-green-400">{{ session('status') }}</p>
    @endif

    <div class="mb-4 flex flex-wrap gap-2">
        @if(auth()->user()->hasPermission('create-tagihan-bulanans'))
            <a href="{{ route('keuangan.tagihan-bulanan.generate-form') }}"><x-button type="primary">Generate Tagihan</x-button></a>
        @endif
    </div>

    <form method="GET" class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 mb-4 grid grid-cols-1 md:grid-cols-5 gap-3">
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
            <input type="number" name="tahun" value="{{ request('tahun') }}" class="w-full px-3 py-1.5 rounded-lg text-sm bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600" placeholder="2025">
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
        <div>
            <label class="text-xs text-gray-500">Status</label>
            <select name="status" class="w-full px-3 py-1.5 rounded-lg text-sm bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600">
                <option value="">Semua</option>
                @foreach(['belum_lunas', 'sebagian', 'lunas'] as $s)
                    <option value="{{ $s }}" @selected(request('status') === $s)>{{ $ks->labelStatus($s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="md:col-span-5"><x-button type="secondary">Filter</x-button></div>
    </form>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 overflow-x-auto">
        <table id="dt-tagihan" class="min-w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                    <th class="py-2 pr-4">Siswa</th>
                    <th class="py-2 pr-4">Kelas</th>
                    <th class="py-2 pr-4">Periode</th>
                    <th class="py-2 pr-4">Total</th>
                    <th class="py-2 pr-4">Terbayar</th>
                    <th class="py-2 pr-4">Status</th>
                    <th class="py-2 pr-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tagihans as $tagihan)
                    <tr class="border-b border-gray-100 dark:border-gray-700">
                        <td class="py-2 pr-4">{{ $tagihan->siswa->nama }}</td>
                        <td class="py-2 pr-4">{{ $tagihan->siswa->kelas ? $tagihan->siswa->kelas->tingkat->nama . ' ' . $tagihan->siswa->kelas->nama_kelas : '—' }}</td>
                        <td class="py-2 pr-4">{{ $ks::BULAN_NAMA[$tagihan->bulan] }} {{ $tagihan->tahun }}</td>
                        <td class="py-2 pr-4">{{ $ks->formatRupiah($tagihan->total_nominal) }}</td>
                        <td class="py-2 pr-4">{{ $ks->formatRupiah($tagihan->total_terbayar) }}</td>
                        <td class="py-2 pr-4">
                            <span class="px-2 py-0.5 rounded text-xs {{ $ks->badgeClass($tagihan->status) }}">{{ $ks->labelStatus($tagihan->status) }}</span>
                        </td>
                        <td class="py-2 pr-4 text-right whitespace-nowrap">
                            <a href="{{ route('keuangan.tagihan-bulanan.show', $tagihan) }}" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Detail</a>
                            @if(auth()->user()->hasPermission('cetak-tagihan'))
                                <a href="{{ route('keuangan.tagihan-bulanan.cetak', $tagihan) }}" target="_blank" class="text-green-600 dark:text-green-400 hover:underline">Cetak</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-4 text-gray-500">Belum ada tagihan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <x-datatables-client table-id="dt-tagihan" />
</x-layouts.app>
