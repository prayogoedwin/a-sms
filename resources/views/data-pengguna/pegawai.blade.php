<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Data Pegawai</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola data pegawai.</p>
    </div>

    <div class="mb-4">
        @if(auth()->user()->hasPermission('create-pegawais'))
            <a href="{{ route('data-pengguna.pegawai.create') }}"><x-button type="primary">Tambah Pegawai</x-button></a>
        @endif
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 overflow-x-auto">
        <table id="dt-pegawai" class="min-w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                    <th class="py-2 pr-4">Nama</th>
                    <th class="py-2 pr-4">NIP</th>
                    <th class="py-2 pr-4">Email</th>
                    <th class="py-2 pr-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pegawais as $pegawai)
                    <tr class="border-b border-gray-100 dark:border-gray-700">
                        <td class="py-2 pr-4">{{ $pegawai->nama }}</td>
                        <td class="py-2 pr-4">{{ $pegawai->nip ?: '-' }}</td>
                        <td class="py-2 pr-4">{{ $pegawai->user->email }}</td>
                        <td class="py-2 pr-4 text-right whitespace-nowrap">
                            @if(auth()->user()->hasPermission('edit-pegawais'))
                                <a href="{{ route('data-pengguna.pegawai.edit', $pegawai) }}" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Edit</a>
                            @endif
                            @if(auth()->user()->hasPermission('delete-pegawais'))
                                <form method="POST" action="{{ route('data-pengguna.pegawai.destroy', $pegawai) }}" class="inline" onsubmit="return confirm('Hapus data pegawai ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <x-datatables-client table-id="dt-pegawai" />
</x-layouts.app>
