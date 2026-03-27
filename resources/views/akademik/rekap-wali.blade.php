<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Rekap Wali Kelas</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Monitoring seluruh nilai dan absensi siswa per kelas wali.</p>
    </div>

    @forelse($kelas as $itemKelas)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-4">
            <h2 class="font-semibold text-gray-800 dark:text-gray-100 mb-2">
                {{ $itemKelas->tingkat->nama }} {{ $itemKelas->nama_kelas }}
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                Jumlah siswa: {{ $itemKelas->siswa->count() }} | Jumlah jadwal: {{ $itemKelas->jadwals->count() }}
            </p>
            <div class="text-sm text-gray-700 dark:text-gray-300">
                @foreach($itemKelas->jadwals as $jadwal)
                    <div class="mb-2">
                        <strong>{{ $jadwal->mataPelajaran->nama }}</strong>:
                        {{ $jadwal->nilais->count() }} data nilai,
                        {{ $jadwal->absensis->count() }} data absensi.
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <p class="text-gray-600 dark:text-gray-400">Anda belum menjadi wali kelas.</p>
        </div>
    @endforelse
</x-layouts.app>
