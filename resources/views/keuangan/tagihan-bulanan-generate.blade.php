<x-layouts.app>
    <div class="mb-6"><h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Generate Tagihan Bulanan</h1></div>
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 max-w-2xl">
        <form method="POST" action="{{ route('keuangan.tagihan-bulanan.generate') }}" class="space-y-3">
            @csrf
            <div>
                <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun Ajaran</label>
                <select name="tahun_ajaran_id" class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600" required>
                    @foreach($tahunAjarans as $ta)
                        <option value="{{ $ta->id }}" @selected(old('tahun_ajaran_id') == $ta->id)>{{ $ta->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bulan</label>
                <select name="bulan" class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600" required>
                    @foreach(\App\Services\KeuanganService::BULAN_NAMA as $num => $nama)
                        <option value="{{ $num }}" @selected(old('bulan', now()->month) == $num)>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <x-forms.input label="Tahun" name="tahun" type="number" value="{{ old('tahun', now()->year) }}" required />
            <div>
                <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kelas (opsional)</label>
                <select name="kelas_id" class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600">
                    <option value="">Semua kelas</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" @selected(old('kelas_id') == $kelas->id)>{{ $kelas->tingkat->nama }} {{ $kelas->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <p class="text-sm text-gray-500">Sistem akan membuat tagihan untuk siswa yang belum memiliki tagihan di periode ini.</p>
            <div class="flex gap-2">
                <x-button type="primary">Generate</x-button>
                <a href="{{ route('keuangan.tagihan-bulanan.index') }}"><x-button type="secondary">Batal</x-button></a>
            </div>
        </form>
    </div>
</x-layouts.app>
