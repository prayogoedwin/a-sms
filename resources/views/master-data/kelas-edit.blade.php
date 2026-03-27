<x-layouts.app>
    <div class="mb-6"><h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Edit Kelas</h1></div>
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 max-w-2xl">
        <form method="POST" action="{{ route('master-data.kelas.update', $kelas) }}" class="space-y-3">
            @csrf
            @method('PUT')
            <div>
                <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tingkat</label>
                <select name="tingkat_id" class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600" required>
                    <option value="">Pilih Tingkat</option>
                    @foreach($tingkats as $tingkat)
                        <option value="{{ $tingkat->id }}" @selected(old('tingkat_id', $kelas->tingkat_id) == $tingkat->id)>{{ $tingkat->nama }}</option>
                    @endforeach
                </select>
            </div>
            <x-forms.input label="Nama Kelas" name="nama_kelas" value="{{ old('nama_kelas', $kelas->nama_kelas) }}" required />
            <div>
                <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Wali Kelas</label>
                <select name="wali_kelas_guru_id" class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600">
                    <option value="">Pilih Wali Kelas (opsional)</option>
                    @foreach($gurus as $guru)
                        <option value="{{ $guru->id }}" @selected(old('wali_kelas_guru_id', $kelas->wali_kelas_guru_id) == $guru->id)>{{ $guru->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <x-button type="primary">Update</x-button>
                <a href="{{ route('master-data.kelas.index') }}"><x-button type="secondary">Batal</x-button></a>
            </div>
        </form>
    </div>
</x-layouts.app>
