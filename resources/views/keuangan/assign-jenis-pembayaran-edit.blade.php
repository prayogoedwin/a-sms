<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Assign Jenis Pembayaran</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">
            {{ $siswa->nama }}
            @if($siswa->kelas)
                — {{ $siswa->kelas->tingkat->nama }} {{ $siswa->kelas->nama_kelas }}
            @endif
        </p>
    </div>

    @if(session('status'))
        <p class="mb-4 text-sm text-green-600 dark:text-green-400">{{ session('status') }}</p>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 max-w-3xl">
        @if($optionalJenis->isEmpty())
            <p class="text-gray-500">Tidak ada jenis pembayaran opsional yang bisa di-assign.</p>
        @else
            <form method="POST" action="{{ route('keuangan.assign-jenis-pembayaran.update', $siswa) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Centang jenis pembayaran opsional yang berlaku untuk siswa ini. Kosongkan nominal override untuk memakai tarif default.
                </p>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left border-b">
                                <th class="py-2 pr-4">Aktif</th>
                                <th class="py-2 pr-4">Jenis</th>
                                <th class="py-2 pr-4">Frekuensi</th>
                                <th class="py-2 pr-4">Override Nominal (opsional)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($optionalJenis as $i => $jenis)
                                @php
                                    $assignment = $siswa->jenisPembayarans->firstWhere('jenis_pembayaran_id', $jenis->id);
                                    $isActive = old("items.{$i}.aktif", $assignment?->aktif ?? false);
                                    $override = old("items.{$i}.nominal_override", $assignment?->nominal_override);
                                @endphp
                                <tr class="border-b border-gray-100 dark:border-gray-700">
                                    <td class="py-2 pr-4">
                                        <input type="hidden" name="items[{{ $i }}][jenis_pembayaran_id]" value="{{ $jenis->id }}">
                                        <input type="checkbox" name="items[{{ $i }}][aktif]" value="1" @checked($isActive) class="rounded">
                                    </td>
                                    <td class="py-2 pr-4 font-medium">{{ $jenis->nama }}</td>
                                    <td class="py-2 pr-4 capitalize">{{ $jenis->frekuensi }}</td>
                                    <td class="py-2 pr-4">
                                        <input type="number" name="items[{{ $i }}][nominal_override]" min="0" step="1"
                                            value="{{ $override }}"
                                            placeholder="Pakai tarif default"
                                            class="w-full px-3 py-1 rounded-lg bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex gap-2">
                    @if(auth()->user()->hasPermission('edit-jenis-pembayarans'))
                        <x-button type="primary">Simpan</x-button>
                    @endif
                    <a href="{{ route('keuangan.assign-jenis-pembayaran.index') }}"><x-button type="secondary">Kembali</x-button></a>
                </div>
            </form>
        @endif
    </div>
</x-layouts.app>
