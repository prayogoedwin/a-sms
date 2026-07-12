<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Ajukan Pembayaran</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $siswa->nama }}</p>
    </div>

    <x-portal.anak-selector :anak-list="$anakList" :siswa="$siswa" />

    @if($errors->any())
        <div class="mb-4 text-sm text-red-600 dark:text-red-400">
            @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 max-w-3xl">
        <form method="POST" action="{{ route('portal.pengajuan.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" name="anak_id" value="{{ $siswa->id }}">

            <x-forms.input label="Tanggal Transfer" name="tanggal_transfer" type="date" value="{{ old('tanggal_transfer', date('Y-m-d')) }}" required />
            <x-forms.input label="Keterangan" name="keterangan" value="{{ old('keterangan') }}" />
            <div>
                <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bukti Transfer</label>
                <input type="file" name="bukti" accept=".jpg,.jpeg,.png,.pdf" required class="w-full text-sm">
            </div>

            <div>
                <h2 class="font-medium mb-2">Item Tagihan</h2>
                @if($tagihanDetails->isEmpty())
                    <p class="text-sm text-gray-500">Tidak ada tagihan yang belum lunas.</p>
                @else
                    <div class="space-y-2">
                        @foreach($tagihanDetails as $i => $d)
                            @php $sisa = (float) $d->nominal - (float) $d->nominal_terbayar; @endphp
                            <div class="flex flex-wrap items-center gap-3 p-3 rounded border border-gray-200 dark:border-gray-600">
                                <label class="flex items-center gap-2 flex-1 min-w-[200px]">
                                    <input type="checkbox" name="items[{{ $i }}][selected]" value="1" class="item-check rounded">
                                    <span class="text-sm">{{ $d->jenisPembayaran->nama }} — {{ $ks->labelBulan($d->tagihanBulanan->bulan) }} {{ $d->tagihanBulanan->tahun }} (sisa: {{ $ks->formatRupiah($sisa) }})</span>
                                </label>
                                <input type="hidden" name="items[{{ $i }}][detail_id]" value="{{ $d->id }}">
                                <input type="number" name="items[{{ $i }}][nominal]" min="0" max="{{ $sisa }}" step="1000" placeholder="Nominal" class="w-36 px-2 py-1 rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 text-sm">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="flex gap-2">
                <x-button type="primary">Kirim Pengajuan</x-button>
                <a href="{{ route('portal.pengajuan.index', ['anak_id' => $siswa->id]) }}"><x-button type="secondary">Batal</x-button></a>
            </div>
        </form>
    </div>
</x-layouts.app>
