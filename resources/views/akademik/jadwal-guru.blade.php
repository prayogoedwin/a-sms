<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Jadwal Mengajar</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">
            @if($isAdminView)
                Tampilan admin: semua jadwal dapat difilter per guru dan tahun ajaran.
            @else
                Daftar jadwal untuk {{ $guru->nama }}.
            @endif
        </p>
    </div>

    @if($tahunAjarans->isEmpty())
        <div class="mb-4 p-4 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 text-sm text-amber-800 dark:text-amber-200">
            Belum ada data tahun ajaran. Tambahkan di menu <strong>Master Data → Tahun Ajaran</strong> terlebih dahulu.
        </div>
    @endif

    <form method="GET" action="{{ route('akademik.jadwal-guru') }}" class="mb-4 flex flex-wrap items-end gap-3">
        <div>
            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Tahun ajaran</label>
            <select name="tahun_ajaran_id" class="rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 min-w-[10rem]">
                @foreach($tahunAjarans as $ta)
                    <option value="{{ $ta->id }}" @selected((int) $tahunAjaranId === (int) $ta->id)>{{ $ta->nama }}</option>
                @endforeach
            </select>
        </div>
        @if($isAdminView)
            <div>
                <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Guru</label>
                <select name="guru_id" class="rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 min-w-[12rem]">
                    <option value="all" @selected($guruFilter === null || $guruFilter === '' || $guruFilter === 'all')>Semua guru</option>
                    @foreach($gurus as $g)
                        <option value="{{ $g->id }}" @selected((string) $guruFilter === (string) $g->id)>{{ $g->nama }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        <x-button type="secondary">Terapkan</x-button>
    </form>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-4 overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                        @if($isAdminView)
                            <th class="py-2 pr-4">Guru</th>
                        @endif
                        <th class="py-2 pr-4">Hari</th>
                        <th class="py-2 pr-4">Kelas</th>
                        <th class="py-2 pr-4">Mata Pelajaran</th>
                        <th class="py-2 pr-4">Jam</th>
                        <th class="py-2 pr-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwals as $jadwal)
                        <tr class="border-b border-gray-100 dark:border-gray-700">
                            @if($isAdminView)
                                <td class="py-2 pr-4">{{ $jadwal->guru->nama }}</td>
                            @endif
                            <td class="py-2 pr-4 capitalize">{{ $jadwal->hari }}</td>
                            <td class="py-2 pr-4">{{ $jadwal->kelas->tingkat->nama }} {{ $jadwal->kelas->nama_kelas }}</td>
                            <td class="py-2 pr-4">{{ $jadwal->mataPelajaran->nama }}</td>
                            <td class="py-2 pr-4">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                            <td class="py-2 pr-4 whitespace-nowrap">
                                @php
                                    $bolehKelola = ! $isAdminView || ($guru && (int) $guru->id === (int) $jadwal->guru_id);
                                @endphp
                                @if($bolehKelola)
                                    <a class="text-blue-600 dark:text-blue-400 hover:underline mr-2" href="{{ route('akademik.nilai.form', $jadwal) }}">Nilai</a>
                                    <a class="text-green-600 dark:text-green-400 hover:underline mr-2" href="{{ route('akademik.absensi.form', $jadwal) }}">Absensi</a>
                                    <a class="text-amber-600 dark:text-amber-400 hover:underline" href="{{ route('akademik.rekap-absensi', $jadwal) }}">Rekap absensi</a>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $isAdminView ? 6 : 5 }}" class="py-4 text-gray-500">Belum ada jadwal untuk filter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
