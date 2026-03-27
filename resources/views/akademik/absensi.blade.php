<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Input Absensi</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">
            {{ $jadwal->mataPelajaran->nama }} - {{ $jadwal->kelas->tingkat->nama }} {{ $jadwal->kelas->nama_kelas }}
        </p>
    </div>

    <form method="GET" action="{{ route('akademik.absensi.form', $jadwal) }}" class="mb-4">
        <label class="text-sm text-gray-700 dark:text-gray-300">Tanggal</label>
        <input type="date" name="tanggal" value="{{ $tanggal }}"
            class="ml-2 rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
        <x-button type="secondary">Tampilkan</x-button>
    </form>

    <form method="POST" action="{{ route('akademik.absensi.simpan', $jadwal) }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="tanggal" value="{{ $tanggal }}">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-4 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2 pr-4">Siswa</th>
                            <th class="py-2 pr-4">Status</th>
                            <th class="py-2 pr-4">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jadwal->kelas->siswa as $siswa)
                            @php($row = $absensiBySiswa[$siswa->id] ?? null)
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-2 pr-4">{{ $siswa->nama }}</td>
                                <td class="py-2 pr-4">
                                    <select name="absensi[{{ $siswa->id }}][status]"
                                        class="rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                                        @foreach(['hadir','izin','sakit','terlambat','pulang_cepat','alpha'] as $status)
                                            <option value="{{ $status }}"
                                                @selected(old('absensi.'.$siswa->id.'.status', $row?->status ?? 'hadir') === $status)>
                                                {{ str_replace('_', ' ', ucfirst($status)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="py-2 pr-4">
                                    <input type="text"
                                        name="absensi[{{ $siswa->id }}][keterangan]"
                                        value="{{ old('absensi.'.$siswa->id.'.keterangan', $row?->keterangan ?? '') }}"
                                        class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4">
            <x-button type="primary">Simpan Absensi</x-button>
        </div>
    </form>
</x-layouts.app>
