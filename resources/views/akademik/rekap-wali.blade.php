<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Rekap Wali Kelas</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">
            @if(! empty($bolehPilihGuru))
                Pilih wali kelas (guru) dan tahun ajaran untuk melihat ringkasan nilai dan absensi per jadwal.
            @else
                Ringkasan nilai dan absensi untuk kelas yang Anda ampu sebagai wali kelas.
            @endif
        </p>
    </div>

    @if($tahunAjarans->isEmpty())
        <div class="mb-4 p-4 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 text-sm text-amber-800 dark:text-amber-200">
            Belum ada data tahun ajaran. Tambahkan di menu <strong>Master Data → Tahun Ajaran</strong>.
        </div>
    @endif

    @if($guru)
        <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">
            Menampilkan rekap untuk: <span class="font-semibold">{{ $guru->nama }}</span>
        </p>
    @endif

    <form method="GET" action="{{ route('akademik.rekap-wali') }}" class="mb-6 flex flex-wrap items-end gap-4">
        @if(! empty($bolehPilihGuru))
            <div>
                <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Wali kelas (guru)</label>
                <select name="guru_id" class="rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 min-w-[14rem]">
                    @forelse($gurus as $g)
                        <option value="{{ $g->id }}" @selected($guru && (int) $guru->id === (int) $g->id)>{{ $g->nama }}</option>
                    @empty
                        <option value="">— tidak ada guru wali kelas —</option>
                    @endforelse
                </select>
            </div>
        @endif
        <div>
            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Tahun ajaran</label>
            <select name="tahun_ajaran_id" class="rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 min-w-[10rem]">
                @foreach($tahunAjarans as $ta)
                    <option value="{{ $ta->id }}" @selected((int) $tahunAjaranId === (int) $ta->id)>{{ $ta->nama }}</option>
                @endforeach
            </select>
        </div>
        <x-button type="secondary">Terapkan</x-button>
    </form>

    @if(! $guru)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <p class="text-gray-600 dark:text-gray-400">
                @if(! empty($bolehPilihGuru))
                    Belum ada guru yang ditetapkan sebagai wali kelas, atau data belum dipilih.
                @else
                    Akun Anda tidak terhubung ke data guru. Hubungi admin jika Anda seharusnya menjadi wali kelas.
                @endif
            </p>
        </div>
    @else
        @forelse($kelas as $itemKelas)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-4">
                <h2 class="font-semibold text-gray-800 dark:text-gray-100 mb-2">
                    {{ $itemKelas->tingkat->nama }} {{ $itemKelas->nama_kelas }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                    Jumlah siswa: {{ $itemKelas->siswa->count() }} | Jumlah jadwal (tahun ajaran ini): {{ $itemKelas->jadwals->count() }}
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
                <p class="text-gray-600 dark:text-gray-400">
                    @if($tahunAjaranId)
                        Guru ini belum menjadi wali kelas, atau belum ada jadwal untuk tahun ajaran yang dipilih.
                    @else
                        Pilih tahun ajaran yang valid.
                    @endif
                </p>
            </div>
        @endforelse
    @endif
</x-layouts.app>
