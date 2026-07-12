@php $ks = app(\App\Services\KeuanganService::class); @endphp
<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Assign Jenis Pembayaran Siswa</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola jenis pembayaran opsional (mis. ekskul) per siswa.</p>
    </div>

    @if($optionalJenis->isEmpty())
        <p class="mb-4 text-sm text-yellow-700 dark:text-yellow-300 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
            Belum ada jenis pembayaran opsional. Buat jenis dengan opsi <strong>Wajib = tidak</strong> di master Jenis Pembayaran.
        </p>
    @endif

    <form method="GET" class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 mb-4 grid grid-cols-1 md:grid-cols-3 gap-3">
        <div>
            <label class="text-xs text-gray-500">Kelas</label>
            <select name="kelas_id" class="w-full px-3 py-1.5 rounded-lg text-sm bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600">
                <option value="">Semua</option>
                @foreach($kelasList as $kelas)
                    <option value="{{ $kelas->id }}" @selected(request('kelas_id') == $kelas->id)>{{ $kelas->tingkat->nama }} {{ $kelas->nama_kelas }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs text-gray-500">Cari nama / NIS</label>
            <input type="text" name="q" value="{{ request('q') }}" class="w-full px-3 py-1.5 rounded-lg text-sm bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600" placeholder="Nama atau NIS">
        </div>
        <div class="flex items-end"><x-button type="secondary">Filter</x-button></div>
    </form>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 overflow-x-auto">
        <table id="dt-assign" class="min-w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                    <th class="py-2 pr-4">Siswa</th>
                    <th class="py-2 pr-4">NIS</th>
                    <th class="py-2 pr-4">Kelas</th>
                    <th class="py-2 pr-4">Jenis Opsional Aktif</th>
                    <th class="py-2 pr-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswas as $siswa)
                    @php
                        $aktif = $siswa->jenisPembayarans->where('aktif', true)->map(fn ($j) => $j->jenisPembayaran?->nama)->filter()->implode(', ');
                    @endphp
                    <tr class="border-b border-gray-100 dark:border-gray-700">
                        <td class="py-2 pr-4">{{ $siswa->nama }}</td>
                        <td class="py-2 pr-4">{{ $siswa->nis ?: '—' }}</td>
                        <td class="py-2 pr-4">{{ $siswa->kelas ? $siswa->kelas->tingkat->nama . ' ' . $siswa->kelas->nama_kelas : '—' }}</td>
                        <td class="py-2 pr-4">{{ $aktif ?: '—' }}</td>
                        <td class="py-2 pr-4 text-right">
                            <a href="{{ route('keuangan.assign-jenis-pembayaran.edit', $siswa) }}" class="text-blue-600 dark:text-blue-400 hover:underline">Assign</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <x-datatables-client table-id="dt-assign" />
</x-layouts.app>
