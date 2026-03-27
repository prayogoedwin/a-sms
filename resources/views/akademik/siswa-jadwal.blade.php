<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Jadwal kelas</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">
            Jadwal pelajaran untuk kelas Anda
            @if($siswa->kelas)
                ({{ $siswa->kelas->tingkat->nama ?? '' }} {{ $siswa->kelas->nama_kelas }}).
            @endif
        </p>
    </div>

    @if(! empty($tanpaKelas))
        <div class="mb-4 p-4 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 text-sm text-amber-800 dark:text-amber-200">
            Akun Anda belum ditetapkan ke kelas. Hubungi admin sekolah agar jadwal dapat ditampilkan.
        </div>
    @endif

    @if($tahunAjarans->isEmpty())
        <div class="mb-4 p-4 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 text-sm text-amber-800 dark:text-amber-200">
            Belum ada data tahun ajaran.
        </div>
    @endif

    @if(empty($tanpaKelas))
        <form method="GET" action="{{ route('akademik.siswa.jadwal') }}" class="mb-4 flex flex-wrap items-end gap-3">
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

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-4 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2 pr-4">Hari</th>
                            <th class="py-2 pr-4">Jam</th>
                            <th class="py-2 pr-4">Mata pelajaran</th>
                            <th class="py-2 pr-4">Guru</th>
                            <th class="py-2 pr-4">Semester</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwals as $jadwal)
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-2 pr-4 capitalize">{{ $jadwal->hari }}</td>
                                <td class="py-2 pr-4 whitespace-nowrap">{{ $jadwal->jam_mulai }} – {{ $jadwal->jam_selesai }}</td>
                                <td class="py-2 pr-4">{{ $jadwal->mataPelajaran->nama }}</td>
                                <td class="py-2 pr-4">{{ $jadwal->guru->nama }}</td>
                                <td class="py-2 pr-4 capitalize">{{ $jadwal->semester }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-gray-500">Belum ada jadwal untuk filter ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-layouts.app>
