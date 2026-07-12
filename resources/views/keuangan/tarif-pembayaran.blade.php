<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Tarif Pembayaran</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Nominal pembayaran per jenis, tingkat, dan tahun ajaran.</p>
    </div>

    @if(session('status'))
        <p class="mb-4 text-sm text-green-600 dark:text-green-400">{{ session('status') }}</p>
    @endif

    <div class="mb-4">
        @if(auth()->user()->hasPermission('create-tarif-pembayarans'))
            <a href="{{ route('keuangan.tarif-pembayaran.create') }}"><x-button type="primary">Tambah Tarif</x-button></a>
        @endif
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                    <th class="py-2 pr-4">Jenis</th>
                    <th class="py-2 pr-4">Tingkat</th>
                    <th class="py-2 pr-4">Tahun Ajaran</th>
                    <th class="py-2 pr-4">Nominal</th>
                    <th class="py-2 pr-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tarifs as $tarif)
                    <tr class="border-b border-gray-100 dark:border-gray-700">
                        <td class="py-2 pr-4">{{ $tarif->jenisPembayaran->nama }}</td>
                        <td class="py-2 pr-4">{{ $tarif->tingkat->nama }}</td>
                        <td class="py-2 pr-4">{{ $tarif->tahunAjaran->nama }}</td>
                        <td class="py-2 pr-4">{{ app(\App\Services\KeuanganService::class)->formatRupiah($tarif->nominal) }}</td>
                        <td class="py-2 pr-4 text-right">
                            @if(auth()->user()->hasPermission('edit-tarif-pembayarans'))
                                <a href="{{ route('keuangan.tarif-pembayaran.edit', $tarif) }}" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Edit</a>
                            @endif
                            @if(auth()->user()->hasPermission('delete-tarif-pembayarans'))
                                <form method="POST" action="{{ route('keuangan.tarif-pembayaran.destroy', $tarif) }}" class="inline" onsubmit="return confirm('Hapus tarif ini?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600 dark:text-red-400 hover:underline">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-4 text-gray-500">Belum ada data tarif.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
