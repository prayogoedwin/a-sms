<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Data Siswa</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola siswa dan user login siswa.</p>
    </div>

    <div class="mb-4">
        @if(auth()->user()->hasPermission('create-siswas'))
            <a href="{{ route('data-pengguna.siswa.create') }}"><x-button type="primary">Tambah Siswa</x-button></a>
        @endif
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                    <th class="py-2 pr-4">Nama</th>
                    <th class="py-2 pr-4">NIS</th>
                    <th class="py-2 pr-4">Kelas</th>
                    <th class="py-2 pr-4">Email</th>
                    <th class="py-2 pr-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswas as $siswa)
                    <tr class="border-b border-gray-100 dark:border-gray-700">
                        <td class="py-2 pr-4">{{ $siswa->nama }}</td>
                        <td class="py-2 pr-4">{{ $siswa->nis ?: '-' }}</td>
                        <td class="py-2 pr-4">{{ $siswa->kelas?->tingkat?->nama }} {{ $siswa->kelas?->nama_kelas }}</td>
                        <td class="py-2 pr-4">{{ $siswa->user->email }}</td>
                        <td class="py-2 pr-4 text-right">
                            @if(auth()->user()->hasPermission('edit-siswas'))
                                <a href="{{ route('data-pengguna.siswa.edit', $siswa) }}" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Edit</a>
                            @endif
                            @if(auth()->user()->hasPermission('delete-siswas'))
                                <form method="POST" action="{{ route('data-pengguna.siswa.destroy', $siswa) }}" class="inline" onsubmit="return confirm('Hapus data siswa ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 dark:text-red-400 hover:underline">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-4 text-gray-500">Belum ada data siswa.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
