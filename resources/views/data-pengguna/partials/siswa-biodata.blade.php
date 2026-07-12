@php $s = $siswa ?? null; @endphp
<div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-3">Biodata Siswa</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
            <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Kelamin</label>
            <select name="jenis_kelamin" class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600">
                <option value="">—</option>
                <option value="L" @selected(old('jenis_kelamin', $s?->jenis_kelamin) === 'L')>Laki-laki</option>
                <option value="P" @selected(old('jenis_kelamin', $s?->jenis_kelamin) === 'P')>Perempuan</option>
            </select>
        </div>
        <div>
            <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
            <select name="status" class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600">
                @foreach(['aktif' => 'Aktif', 'lulus' => 'Lulus', 'pindah' => 'Pindah', 'keluar' => 'Keluar'] as $val => $label)
                    <option value="{{ $val }}" @selected(old('status', $s?->status ?? 'aktif') === $val)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <x-forms.input label="Tempat Lahir" name="tempat_lahir" value="{{ old('tempat_lahir', $s?->tempat_lahir) }}" />
        <x-forms.input label="Tanggal Lahir" name="tanggal_lahir" type="date" value="{{ old('tanggal_lahir', $s?->tanggal_lahir?->format('Y-m-d')) }}" />
        <x-forms.input label="NISN" name="nisn" value="{{ old('nisn', $s?->nisn) }}" />
        <x-forms.input label="NIK" name="nik" value="{{ old('nik', $s?->nik) }}" />
        <x-forms.input label="Agama" name="agama" value="{{ old('agama', $s?->agama) }}" />
        <x-forms.input label="Telepon" name="telepon" value="{{ old('telepon', $s?->telepon) }}" />
        <div class="md:col-span-2">
            <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat</label>
            <textarea name="alamat" class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600">{{ old('alamat', $s?->alamat) }}</textarea>
        </div>
    </div>
</div>
<div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-3">Data Orang Tua (teks)</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <x-forms.input label="Nama Ayah" name="nama_ayah" value="{{ old('nama_ayah', $s?->nama_ayah) }}" />
        <x-forms.input label="Nama Ibu" name="nama_ibu" value="{{ old('nama_ibu', $s?->nama_ibu) }}" />
        <x-forms.input label="Nama Wali" name="nama_wali" value="{{ old('nama_wali', $s?->nama_wali) }}" />
    </div>
    @if($s)
        <div class="mt-3 flex flex-wrap gap-3 text-sm text-gray-600 dark:text-gray-400">
            <span>Akun ayah: {{ $s->is_ayah_memiliki_akun ? '✓ Terhubung' : '—' }}</span>
            <span>Akun ibu: {{ $s->is_ibu_memiliki_akun ? '✓ Terhubung' : '—' }}</span>
            <span>Akun wali: {{ $s->is_wali_memiliki_akun ? '✓ Terhubung' : '—' }}</span>
        </div>
    @endif
</div>
