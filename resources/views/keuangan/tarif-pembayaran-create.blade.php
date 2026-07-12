<x-layouts.app>
    <div class="mb-6"><h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Tambah Tarif Pembayaran</h1></div>
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 max-w-2xl">
        <form method="POST" action="{{ route('keuangan.tarif-pembayaran.store') }}" class="space-y-3">
            @csrf
            <div>
                <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Pembayaran</label>
                <select name="jenis_pembayaran_id" class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600" required>
                    <option value="">— Pilih —</option>
                    @foreach($jenisPembayarans as $jenis)
                        <option value="{{ $jenis->id }}" @selected(old('jenis_pembayaran_id') == $jenis->id)>{{ $jenis->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tingkat</label>
                <select name="tingkat_id" class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600" required>
                    <option value="">— Pilih —</option>
                    @foreach($tingkats as $tingkat)
                        <option value="{{ $tingkat->id }}" @selected(old('tingkat_id') == $tingkat->id)>{{ $tingkat->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun Ajaran</label>
                <select name="tahun_ajaran_id" class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600" required>
                    <option value="">— Pilih —</option>
                    @foreach($tahunAjarans as $ta)
                        <option value="{{ $ta->id }}" @selected(old('tahun_ajaran_id') == $ta->id)>{{ $ta->nama }}</option>
                    @endforeach
                </select>
            </div>
            <x-forms.input label="Nominal (Rp)" name="nominal" type="number" min="0" step="1" value="{{ old('nominal') }}" required />
            <div class="flex gap-2">
                <x-button type="primary">Simpan</x-button>
                <a href="{{ route('keuangan.tarif-pembayaran.index') }}"><x-button type="secondary">Batal</x-button></a>
            </div>
        </form>
    </div>
</x-layouts.app>
