<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Nilai</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Rekap nilai Anda per mata pelajaran.</p>
    </div>

    @if($tahunAjarans->isEmpty())
        <div class="mb-4 p-4 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 text-sm text-amber-800 dark:text-amber-200">
            Belum ada data tahun ajaran.
        </div>
    @endif

    <form method="GET" action="{{ route('akademik.siswa.nilai') }}" class="mb-4 flex flex-wrap items-end gap-3">
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
                        <th class="py-2 pr-4">Mata pelajaran</th>
                        <th class="py-2 pr-4">Semester</th>
                        <th class="py-2 pr-4">Nilai</th>
                        <th class="py-2 pr-4">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nilais as $row)
                        <tr class="border-b border-gray-100 dark:border-gray-700">
                            <td class="py-2 pr-4">{{ $row->jadwal->mataPelajaran->nama }}</td>
                            <td class="py-2 pr-4 capitalize">{{ $row->jadwal->semester }}</td>
                            <td class="py-2 pr-4">{{ $row->nilai_angka !== null ? number_format((float) $row->nilai_angka, 0, ',', '.') : '—' }}</td>
                            <td class="py-2 pr-4 text-gray-600 dark:text-gray-400">{{ $row->catatan ?: '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-4 text-gray-500">Belum ada nilai untuk filter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
