<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Data Guru</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola data guru.</p>
    </div>

    <div class="mb-4">
        @if(auth()->user()->hasPermission('create-gurus'))
            <a href="{{ route('data-pengguna.guru.create') }}"><x-button type="primary">Tambah Guru</x-button></a>
        @endif
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                    <th class="py-2 pr-4">Nama</th>
                    <th class="py-2 pr-4">NIP</th>
                    <th class="py-2 pr-4">Email</th>
                    <th class="py-2 pr-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gurus as $guru)
                    <tr class="border-b border-gray-100 dark:border-gray-700">
                        <td class="py-2 pr-4">{{ $guru->nama }}</td>
                        <td class="py-2 pr-4">{{ $guru->nip ?: '-' }}</td>
                        <td class="py-2 pr-4">{{ $guru->user->email }}</td>
                        <td class="py-2 pr-4 text-right">
                            @if(auth()->user()->hasPermission('edit-gurus'))
                                <a href="{{ route('data-pengguna.guru.edit', $guru) }}" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Edit</a>
                            @endif
                            @if(auth()->user()->hasPermission('delete-gurus'))
                                <form method="POST" action="{{ route('data-pengguna.guru.destroy', $guru) }}" class="inline" onsubmit="return confirm('Hapus data guru ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 dark:text-red-400 hover:underline">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-4 text-gray-500">Belum ada data guru.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
