@php $ks = app(\App\Services\KeuanganService::class); @endphp
<x-layouts.app>
    <div class="mb-6"><h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Input Pembayaran</h1></div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 max-w-3xl mb-6 overflow-visible">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <x-forms.select-search
                name="siswa_id"
                label="Pilih Siswa"
                placeholder="Cari nama, NIS, atau kelas..."
                :submit-on-change="true"
                class="flex-1 min-w-[280px]"
            >
                <option value="">— Pilih siswa —</option>
                @foreach($siswas as $s)
                    <option value="{{ $s->id }}" @selected(request('siswa_id') == $s->id)>
                        {{ $s->nama }}{{ $s->nis ? ' · NIS ' . $s->nis : '' }}{{ $s->kelas ? ' · ' . $s->kelas->tingkat->nama . ' ' . $s->kelas->nama_kelas : '' }}
                    </option>
                @endforeach
            </x-forms.select-search>
        </form>
    </div>

    @if($siswa)
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 max-w-3xl">
            <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                Tagihan belum lunas untuk <strong>{{ $siswa->nama }}</strong>
            </p>

            @if($tagihanDetails->isEmpty())
                <p class="text-gray-500">Tidak ada tagihan yang perlu dibayar.</p>
            @else
                <form method="POST" action="{{ route('keuangan.pembayaran.store') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
                    <x-forms.input label="Tanggal Pembayaran" name="tanggal" type="date" value="{{ old('tanggal', now()->toDateString()) }}" required />
                    <div>
                        <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Metode</label>
                        <select name="metode" class="w-full px-4 py-1.5 rounded-lg bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600" required>
                            <option value="tunai" @selected(old('metode') === 'tunai')>Tunai</option>
                            <option value="transfer" @selected(old('metode') === 'transfer')>Transfer</option>
                        </select>
                    </div>
                    <x-forms.input label="Keterangan (opsional)" name="keterangan" value="{{ old('keterangan') }}" />

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left border-b">
                                    <th class="py-2 pr-2">Bayar</th>
                                    <th class="py-2 pr-2">Jenis</th>
                                    <th class="py-2 pr-2">Periode</th>
                                    <th class="py-2 pr-2">Sisa</th>
                                    <th class="py-2 pr-2">Nominal Bayar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tagihanDetails as $i => $detail)
                                    @php $sisa = $detail->nominal - $detail->nominal_terbayar; @endphp
                                    <tr class="border-b border-gray-100 dark:border-gray-700">
                                        <td class="py-2 pr-2">
                                            <input type="checkbox" name="items[{{ $i }}][enabled]" value="1" class="item-check rounded">
                                        </td>
                                        <td class="py-2 pr-2">{{ $detail->jenisPembayaran->nama }}</td>
                                        <td class="py-2 pr-2">{{ $ks::BULAN_NAMA[$detail->tagihanBulanan->bulan] }} {{ $detail->tagihanBulanan->tahun }}</td>
                                        <td class="py-2 pr-2">{{ $ks->formatRupiah($sisa) }}</td>
                                        <td class="py-2 pr-2">
                                            <input type="hidden" name="items[{{ $i }}][detail_id]" value="{{ $detail->id }}">
                                            <input type="number" name="items[{{ $i }}][nominal]" min="0" max="{{ $sisa }}" step="1" value="{{ $sisa }}"
                                                class="item-nominal w-full px-2 py-1 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700" disabled>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="flex gap-2">
                        <x-button type="primary">Simpan Pembayaran</x-button>
                        <a href="{{ route('keuangan.pembayaran.index') }}"><x-button type="secondary">Batal</x-button></a>
                    </div>
                </form>

                <script>
                    document.querySelectorAll('.item-check').forEach((cb, idx) => {
                        cb.addEventListener('change', () => {
                            const input = document.querySelectorAll('.item-nominal')[idx];
                            input.disabled = !cb.checked;
                            if (!cb.checked) input.value = input.max;
                        });
                    });
                    document.querySelector('form').addEventListener('submit', (e) => {
                        const checked = document.querySelectorAll('.item-check:checked');
                        if (checked.length === 0) {
                            e.preventDefault();
                            alert('Pilih minimal satu item tagihan.');
                        }
                        document.querySelectorAll('.item-nominal').forEach((input, idx) => {
                            if (!document.querySelectorAll('.item-check')[idx].checked) {
                                input.removeAttribute('name');
                            }
                        });
                    });
                </script>
            @endif
        </div>
    @endif
</x-layouts.app>
