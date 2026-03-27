<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Penjadwalan</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Atur jadwal pelajaran per kelas.</p>
    </div>

    <div class="mb-4">
        @if(auth()->user()->hasPermission('create-jadwal'))
            <a href="{{ route('master-data.penjadwalan.create') }}"><x-button type="primary">Tambah Jadwal</x-button></a>
        @endif
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                    <th class="py-2 pr-4">Hari</th>
                    <th class="py-2 pr-4">Kelas</th>
                    <th class="py-2 pr-4">Mapel</th>
                    <th class="py-2 pr-4">Guru</th>
                    <th class="py-2 pr-4">Th. ajaran</th>
                    <th class="py-2 pr-4">Jam</th>
                    <th class="py-2 pr-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($jadwals as $item)
                <tr class="border-b border-gray-100 dark:border-gray-700">
                    <td class="py-2 pr-4">{{ ucfirst($item->hari) }}</td>
                    <td class="py-2 pr-4">{{ $item->kelas->tingkat->nama }} {{ $item->kelas->nama_kelas }}</td>
                    <td class="py-2 pr-4">{{ $item->mataPelajaran->nama }}</td>
                    <td class="py-2 pr-4">{{ $item->guru->nama }}</td>
                    <td class="py-2 pr-4">{{ $item->tahunAjaran?->nama ?? '—' }}</td>
                    <td class="py-2 pr-4">{{ $item->jam_mulai }} - {{ $item->jam_selesai }}</td>
                    <td class="py-2 pr-4 text-right">
                        @if(auth()->user()->hasPermission('edit-jadwal'))
                            <a href="{{ route('master-data.penjadwalan.edit', $item) }}" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Edit</a>
                        @endif
                        @if(auth()->user()->hasPermission('delete-jadwal'))
                            <form method="POST" action="{{ route('master-data.penjadwalan.destroy', $item) }}" class="inline" onsubmit="return confirm('Hapus jadwal ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 dark:text-red-400 hover:underline">Hapus</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="py-4 text-gray-500">Belum ada data jadwal.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
