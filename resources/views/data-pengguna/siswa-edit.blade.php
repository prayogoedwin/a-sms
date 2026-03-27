<x-layouts.app>
    <div class="mb-6"><h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Edit Siswa</h1></div>
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 max-w-2xl">
        <form method="POST" action="{{ route('data-pengguna.siswa.update', $siswa) }}" class="space-y-3">
            @csrf
            @method('PUT')
            <x-forms.input label="Nama User" name="name" value="{{ old('name', $siswa->user->name) }}" required />
            <x-forms.input label="Email" name="email" type="email" value="{{ old('email', $siswa->user->email) }}" required />
            <x-forms.input label="Password (opsional)" name="password" type="password" />
            <x-forms.input label="Konfirmasi Password" name="password_confirmation" type="password" />
            <x-forms.input label="Nama Siswa" name="nama" value="{{ old('nama', $siswa->nama) }}" required />
            <x-forms.input label="NIS" name="nis" value="{{ old('nis', $siswa->nis) }}" />
            <div>
                <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kelas</label>
                <select name="kelas_id" class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600">
                    <option value="">Pilih Kelas (opsional)</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" @selected(old('kelas_id', $siswa->kelas_id) == $kelas->id)>{{ $kelas->tingkat->nama }} {{ $kelas->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <x-button type="primary">Update</x-button>
                <a href="{{ route('data-pengguna.siswa.index') }}"><x-button type="secondary">Batal</x-button></a>
            </div>
        </form>
    </div>
</x-layouts.app>
