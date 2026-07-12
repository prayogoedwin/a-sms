<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Tambah Orang Tua</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 max-w-2xl" x-data="{ mode: 'baru' }">
        <div class="flex gap-2 mb-6">
            <button type="button" @click="mode = 'baru'" :class="mode === 'baru' ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700'" class="px-3 py-1 rounded text-sm">Buat akun baru</button>
            <button type="button" @click="mode = 'existing'" :class="mode === 'existing' ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700'" class="px-3 py-1 rounded text-sm">Hubungkan ke siswa (existing)</button>
        </div>

        <form method="POST" action="{{ route('data-pengguna.orang-tua.store') }}" class="space-y-3">
            @csrf
            <input type="hidden" name="mode" :value="mode">

            <div x-show="mode === 'existing'" class="space-y-3">
                <div>
                    <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Orang Tua</label>
                    <select name="orang_tua_id" class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600">
                        <option value="">Pilih orang tua</option>
                        @foreach(\App\Models\OrangTua::with('user')->orderBy('nama')->get() as $ot)
                            <option value="{{ $ot->id }}">{{ $ot->nama }} ({{ $ot->user->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Siswa</label>
                    <select name="siswa_ids[]" multiple class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 min-h-[6rem]">
                        @foreach($siswas as $s)
                            <option value="{{ $s->id }}">{{ $s->nama }} @if($s->kelas)({{ $s->kelas->tingkat->nama }} {{ $s->kelas->nama_kelas }})@endif</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Tahan Ctrl/Cmd untuk pilih banyak.</p>
                </div>
                <div>
                    <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hubungan</label>
                    <select name="hubungan" class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600">
                        <option value="ayah">Ayah</option>
                        <option value="ibu">Ibu</option>
                        <option value="wali">Wali</option>
                    </select>
                </div>
            </div>

            <div x-show="mode === 'baru'" class="space-y-3">
                <x-forms.input label="Nama User" name="name" value="{{ old('name') }}" required />
                <x-forms.input label="Email" name="email" type="email" value="{{ old('email') }}" required />
                <x-forms.input label="Password" name="password" type="password" required />
                <x-forms.input label="Konfirmasi Password" name="password_confirmation" type="password" required />
                <x-forms.input label="Nama Orang Tua" name="nama" value="{{ old('nama') }}" required />
                <x-forms.input label="NIK" name="nik" value="{{ old('nik') }}" />
                <x-forms.input label="Telepon" name="telepon" value="{{ old('telepon') }}" />
                <x-forms.input label="Pekerjaan" name="pekerjaan" value="{{ old('pekerjaan') }}" />
                <div>
                    <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat</label>
                    <textarea name="alamat" class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600">{{ old('alamat') }}</textarea>
                </div>
                <div>
                    <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hubungkan ke siswa (opsional)</label>
                    <select name="siswa_ids[]" multiple class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 min-h-[6rem]">
                        @foreach($siswas as $s)
                            <option value="{{ $s->id }}">{{ $s->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hubungan ke siswa</label>
                    <select name="hubungan" class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600">
                        <option value="ayah">Ayah</option>
                        <option value="ibu">Ibu</option>
                        <option value="wali">Wali</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-2 pt-2">
                <x-button type="primary">Simpan</x-button>
                <a href="{{ route('data-pengguna.orang-tua.index') }}"><x-button type="secondary">Batal</x-button></a>
            </div>
        </form>
    </div>
</x-layouts.app>
