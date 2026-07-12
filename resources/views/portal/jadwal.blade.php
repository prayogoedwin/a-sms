<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Jadwal Anak</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $siswa->nama }} @if($siswa->kelas)({{ $siswa->kelas->tingkat->nama }} {{ $siswa->kelas->nama_kelas }})@endif</p>
    </div>

    <x-portal.anak-selector :anak-list="$anakList" :siswa="$siswa" />

    @if(! $siswa->kelas_id)
        <div class="mb-4 p-4 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 text-sm">Siswa belum ditetapkan ke kelas.</div>
    @else
        <form method="GET" action="{{ route('portal.jadwal') }}" class="mb-4 flex flex-wrap items-end gap-3">
            <input type="hidden" name="anak_id" value="{{ $siswa->id }}">
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

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-x-auto">
            <table class="min-w-full text-sm p-4">
                <thead>
                    <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                        <th class="py-2 px-4">Hari</th>
                        <th class="py-2 px-4">Jam</th>
                        <th class="py-2 px-4">Mapel</th>
                        <th class="py-2 px-4">Guru</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jadwals as $jadwal)
                        <tr class="border-b border-gray-100 dark:border-gray-700">
                            <td class="py-2 px-4 capitalize">{{ $jadwal->hari }}</td>
                            <td class="py-2 px-4">{{ $jadwal->jam_mulai }} – {{ $jadwal->jam_selesai }}</td>
                            <td class="py-2 px-4">{{ $jadwal->mataPelajaran->nama }}</td>
                            <td class="py-2 px-4">{{ $jadwal->guru->nama }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-layouts.app>
