<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Tahun Ajaran</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Data master tahun ajaran untuk penjadwalan.</p>
    </div>

    @if(session('error'))
        <p class="mb-4 text-sm text-red-600 dark:text-red-400">{{ session('error') }}</p>
    @endif

    <div class="mb-4">
        @if(auth()->user()->hasPermission('create-tahun-ajarans'))
            <a href="{{ route('master-data.tahun-ajaran.create') }}"><x-button type="primary">Tambah Tahun Ajaran</x-button></a>
        @endif
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                    <th class="py-2 pr-4">Nama</th>
                    <th class="py-2 pr-4">Mulai</th>
                    <th class="py-2 pr-4">Selesai</th>
                    <th class="py-2 pr-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tahunAjarans as $ta)
                    <tr class="border-b border-gray-100 dark:border-gray-700">
                        <td class="py-2 pr-4 font-medium">{{ $ta->nama }}</td>
                        <td class="py-2 pr-4">{{ $ta->tanggal_mulai?->format('d/m/Y') ?? '—' }}</td>
                        <td class="py-2 pr-4">{{ $ta->tanggal_selesai?->format('d/m/Y') ?? '—' }}</td>
                        <td class="py-2 pr-4 text-right">
                            @if(auth()->user()->hasPermission('edit-tahun-ajarans'))
                                <a href="{{ route('master-data.tahun-ajaran.edit', $ta) }}" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Edit</a>
                            @endif
                            @if(auth()->user()->hasPermission('delete-tahun-ajarans'))
                                <form method="POST" action="{{ route('master-data.tahun-ajaran.destroy', $ta) }}" class="inline" onsubmit="return confirm('Hapus tahun ajaran ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 dark:text-red-400 hover:underline">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-4 text-gray-500">Belum ada data tahun ajaran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
