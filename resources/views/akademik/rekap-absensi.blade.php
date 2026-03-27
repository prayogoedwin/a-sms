@php
    $labelStatus = [
        'hadir' => 'Hadir',
        'izin' => 'Izin',
        'sakit' => 'Sakit',
        'terlambat' => 'Terlambat',
        'pulang_cepat' => 'Pulang cepat',
        'alpha' => 'Alpha',
    ];
    $siswaList = $jadwal->kelas->siswa->sortBy('nama');
@endphp

<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Rekap Absensi</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">
            {{ $jadwal->mataPelajaran->nama }} — {{ $jadwal->kelas->tingkat->nama }} {{ $jadwal->kelas->nama_kelas }}
        </p>
    </div>

    <form method="GET" action="{{ route('akademik.rekap-absensi', $jadwal) }}" class="mb-6 flex flex-wrap items-end gap-3">
        <div>
            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Dari</label>
            <input type="date" name="dari" value="{{ $dari }}"
                class="rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
        </div>
        <div>
            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Sampai</label>
            <input type="date" name="sampai" value="{{ $sampai }}"
                class="rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
        </div>
        <x-button type="secondary">Tampilkan</x-button>
        <a href="{{ route('akademik.jadwal-guru') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline self-end">← Kembali ke jadwal</a>
    </form>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-4 overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                        <th class="py-2 pr-4">Siswa</th>
                        @foreach($statusKeys as $key)
                            <th class="py-2 pr-4 text-center whitespace-nowrap">{{ $labelStatus[$key] }}</th>
                        @endforeach
                        <th class="py-2 pr-4 text-center">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php $kolomTotal = array_fill_keys($statusKeys, 0); $grandTotal = 0; @endphp
                    @forelse($siswaList as $siswa)
                        @php
                            $row = $countsBySiswa[$siswa->id] ?? [];
                            $baris = 0;
                            foreach ($statusKeys as $k) {
                                $baris += $row[$k] ?? 0;
                            }
                            $grandTotal += $baris;
                        @endphp
                        <tr class="border-b border-gray-100 dark:border-gray-700">
                            <td class="py-2 pr-4">{{ $siswa->nama }}</td>
                            @foreach($statusKeys as $key)
                                @php $c = $row[$key] ?? 0; $kolomTotal[$key] += $c; @endphp
                                <td class="py-2 pr-4 text-center">{{ $c }}</td>
                            @endforeach
                            <td class="py-2 pr-4 text-center font-medium">{{ $baris }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($statusKeys) + 2 }}" class="py-4 text-gray-500">Belum ada siswa di kelas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if($siswaList->isNotEmpty())
                    <tfoot>
                        <tr class="border-t-2 border-gray-200 dark:border-gray-600 font-medium">
                            <td class="py-2 pr-4">Jumlah</td>
                            @foreach($statusKeys as $key)
                                <td class="py-2 pr-4 text-center">{{ $kolomTotal[$key] }}</td>
                            @endforeach
                            <td class="py-2 pr-4 text-center">{{ $grandTotal }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">
        Periode: {{ $dari }} s/d {{ $sampai }}. Total dihitung dari data absensi yang sudah diinput pada jadwal ini.
    </p>
</x-layouts.app>
