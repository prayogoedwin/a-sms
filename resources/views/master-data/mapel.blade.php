<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Master Mata Pelajaran</h1>
    </div>

    <div class="mb-4">
        @if(auth()->user()->hasPermission('create-mata-pelajarans'))
            <a href="{{ route('master-data.mapel.create') }}"><x-button type="primary">Tambah Mata Pelajaran</x-button></a>
        @endif
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                    <th class="py-2 pr-4">Kode</th>
                    <th class="py-2 pr-4">Nama</th>
                    <th class="py-2 pr-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mataPelajarans as $mapel)
                    <tr class="border-b border-gray-100 dark:border-gray-700">
                        <td class="py-2 pr-4">{{ $mapel->kode ?: '-' }}</td>
                        <td class="py-2 pr-4">{{ $mapel->nama }}</td>
                        <td class="py-2 pr-4 text-right">
                            @if(auth()->user()->hasPermission('edit-mata-pelajarans'))
                                <a href="{{ route('master-data.mapel.edit', $mapel) }}" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Edit</a>
                            @endif
                            @if(auth()->user()->hasPermission('delete-mata-pelajarans'))
                                <form method="POST" action="{{ route('master-data.mapel.destroy', $mapel) }}" class="inline" onsubmit="return confirm('Hapus mata pelajaran ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 dark:text-red-400 hover:underline">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="py-4 text-gray-500">Belum ada data mata pelajaran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
