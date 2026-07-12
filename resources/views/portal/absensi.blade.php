<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Absensi Anak</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $siswa->nama }}</p>
    </div>

    <x-portal.anak-selector :anak-list="$anakList" :siswa="$siswa" />

    <form method="GET" action="{{ route('portal.absensi') }}" class="mb-4 flex flex-wrap items-end gap-3">
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
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                    <th class="py-2 px-4">Tanggal</th>
                    <th class="py-2 px-4">Mapel</th>
                    <th class="py-2 px-4">Status</th>
                    <th class="py-2 px-4">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($absensis as $abs)
                    <tr class="border-b border-gray-100 dark:border-gray-700">
                        <td class="py-2 px-4">{{ $abs->tanggal->format('d/m/Y') }}</td>
                        <td class="py-2 px-4">{{ $abs->jadwal->mataPelajaran->nama }}</td>
                        <td class="py-2 px-4 capitalize">{{ $abs->status }}</td>
                        <td class="py-2 px-4">{{ $abs->keterangan ?: '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layouts.app>
