<x-layouts.app>
    <div class="mb-6"><h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Tambah Jenis Pembayaran</h1></div>
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 max-w-2xl">
        <form method="POST" action="{{ route('keuangan.jenis-pembayaran.store') }}" class="space-y-3">
            @csrf
            <x-forms.input label="Kode" name="kode" value="{{ old('kode') }}" placeholder="SPP" required />
            <x-forms.input label="Nama" name="nama" value="{{ old('nama') }}" required />
            <div>
                <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Frekuensi</label>
                <select name="frekuensi" class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600" required>
                    <option value="bulanan" @selected(old('frekuensi') === 'bulanan')>Bulanan</option>
                    <option value="tahunan" @selected(old('frekuensi') === 'tahunan')>Tahunan</option>
                    <option value="sekali" @selected(old('frekuensi') === 'sekali')>Sekali</option>
                </select>
            </div>
            <div>
                <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bulan Berlaku (untuk tahunan)</label>
                <select name="bulan_berlaku" class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600">
                    <option value="">— Pilih bulan —</option>
                    @foreach(\App\Services\KeuanganService::BULAN_NAMA as $num => $nama)
                        <option value="{{ $num }}" @selected(old('bulan_berlaku') == $num)>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                <input type="checkbox" name="wajib" value="1" @checked(old('wajib', true)) class="rounded">
                Wajib untuk semua siswa
            </label>
            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                <input type="checkbox" name="aktif" value="1" @checked(old('aktif', true)) class="rounded">
                Aktif
            </label>
            <div class="flex gap-2">
                <x-button type="primary">Simpan</x-button>
                <a href="{{ route('keuangan.jenis-pembayaran.index') }}"><x-button type="secondary">Batal</x-button></a>
            </div>
        </form>
    </div>
</x-layouts.app>
