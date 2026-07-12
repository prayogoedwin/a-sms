<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Data Orang Tua</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola akun orang tua dan keterkaitannya dengan siswa.</p>
    </div>

    @if(session('status'))
        <p class="mb-4 text-sm text-green-600 dark:text-green-400">{{ session('status') }}</p>
    @endif

    <div class="mb-4">
        @if(auth()->user()->hasPermission('create-orang-tuas'))
            <a href="{{ route('data-pengguna.orang-tua.create') }}"><x-button type="primary">Tambah Orang Tua</x-button></a>
        @endif
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 overflow-x-auto">
        <table id="dt-orang-tua" class="min-w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                    <th class="py-2 pr-4">Nama</th>
                    <th class="py-2 pr-4">Email</th>
                    <th class="py-2 pr-4">Telepon</th>
                    <th class="py-2 pr-4">Anak</th>
                    <th class="py-2 pr-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orangTuas as $ot)
                    <tr class="border-b border-gray-100 dark:border-gray-700">
                        <td class="py-2 pr-4">{{ $ot->nama }}</td>
                        <td class="py-2 pr-4">{{ $ot->user->email }}</td>
                        <td class="py-2 pr-4">{{ $ot->telepon ?: '—' }}</td>
                        <td class="py-2 pr-4">{{ $ot->siswas->pluck('nama')->join(', ') ?: '—' }}</td>
                        <td class="py-2 pr-4 text-right whitespace-nowrap">
                            @if(auth()->user()->hasPermission('edit-orang-tuas'))
                                <a href="{{ route('data-pengguna.orang-tua.edit', $ot) }}" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Edit</a>
                            @endif
                            @if(auth()->user()->hasPermission('delete-orang-tuas'))
                                <form method="POST" action="{{ route('data-pengguna.orang-tua.destroy', $ot) }}" class="inline" onsubmit="return confirm('Hapus orang tua ini?')">
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
    <x-datatables-client table-id="dt-orang-tua" />
</x-layouts.app>
