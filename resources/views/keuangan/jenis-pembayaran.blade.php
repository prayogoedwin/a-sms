<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Jenis Pembayaran</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Master jenis pembayaran sekolah (SPP, kegiatan, dll.).</p>
    </div>

    @if(session('status'))
        <p class="mb-4 text-sm text-green-600 dark:text-green-400">{{ session('status') }}</p>
    @endif
    @if(session('error'))
        <p class="mb-4 text-sm text-red-600 dark:text-red-400">{{ session('error') }}</p>
    @endif

    <div class="mb-4">
        @if(auth()->user()->hasPermission('create-jenis-pembayarans'))
            <a href="{{ route('keuangan.jenis-pembayaran.create') }}"><x-button type="primary">Tambah Jenis</x-button></a>
        @endif
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                    <th class="py-2 pr-4">Kode</th>
                    <th class="py-2 pr-4">Nama</th>
                    <th class="py-2 pr-4">Frekuensi</th>
                    <th class="py-2 pr-4">Wajib</th>
                    <th class="py-2 pr-4">Bulan Berlaku</th>
                    <th class="py-2 pr-4">Status</th>
                    <th class="py-2 pr-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jenisPembayarans as $jenis)
                    <tr class="border-b border-gray-100 dark:border-gray-700">
                        <td class="py-2 pr-4 font-mono">{{ $jenis->kode }}</td>
                        <td class="py-2 pr-4">{{ $jenis->nama }}</td>
                        <td class="py-2 pr-4 capitalize">{{ $jenis->frekuensi }}</td>
                        <td class="py-2 pr-4">{{ $jenis->wajib ? 'Ya' : 'Tidak' }}</td>
                        <td class="py-2 pr-4">{{ $jenis->bulan_berlaku ? \App\Services\KeuanganService::BULAN_NAMA[$jenis->bulan_berlaku] : '—' }}</td>
                        <td class="py-2 pr-4">{{ $jenis->aktif ? 'Aktif' : 'Nonaktif' }}</td>
                        <td class="py-2 pr-4 text-right">
                            @if(auth()->user()->hasPermission('edit-jenis-pembayarans'))
                                <a href="{{ route('keuangan.jenis-pembayaran.edit', $jenis) }}" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Edit</a>
                            @endif
                            @if(auth()->user()->hasPermission('delete-jenis-pembayarans'))
                                <form method="POST" action="{{ route('keuangan.jenis-pembayaran.destroy', $jenis) }}" class="inline" onsubmit="return confirm('Hapus jenis pembayaran ini?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600 dark:text-red-400 hover:underline">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-4 text-gray-500">Belum ada data jenis pembayaran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
