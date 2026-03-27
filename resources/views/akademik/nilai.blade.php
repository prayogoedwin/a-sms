<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Input Nilai</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">
            {{ $jadwal->mataPelajaran->nama }} - {{ $jadwal->kelas->tingkat->nama }} {{ $jadwal->kelas->nama_kelas }}
        </p>
    </div>

    <form method="POST" action="{{ route('akademik.nilai.simpan', $jadwal) }}">
        @csrf
        @method('PUT')
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-4 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2 pr-4">Siswa</th>
                            <th class="py-2 pr-4">Nilai</th>
                            <th class="py-2 pr-4">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jadwal->kelas->siswa as $siswa)
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-2 pr-4">{{ $siswa->nama }}</td>
                                <td class="py-2 pr-4">
                                    <input type="number" min="0" max="100" step="0.01"
                                        name="nilai[{{ $siswa->id }}][nilai_angka]"
                                        value="{{ old('nilai.'.$siswa->id.'.nilai_angka', $nilaiBySiswa[$siswa->id]->nilai_angka ?? '') }}"
                                        class="w-28 rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                                </td>
                                <td class="py-2 pr-4">
                                    <input type="text"
                                        name="nilai[{{ $siswa->id }}][catatan]"
                                        value="{{ old('nilai.'.$siswa->id.'.catatan', $nilaiBySiswa[$siswa->id]->catatan ?? '') }}"
                                        class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4">
            <x-button type="primary">Simpan Nilai</x-button>
        </div>
    </form>
</x-layouts.app>
