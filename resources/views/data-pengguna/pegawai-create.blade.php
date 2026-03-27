<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Tambah Pegawai</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 max-w-2xl">
        <form method="POST" action="{{ route('data-pengguna.pegawai.store') }}" class="space-y-3">
            @csrf
            <x-forms.input label="Nama User" name="name" value="{{ old('name') }}" required />
            <x-forms.input label="Email" name="email" type="email" value="{{ old('email') }}" required />
            <x-forms.input label="Password" name="password" type="password" required />
            <x-forms.input label="Konfirmasi Password" name="password_confirmation" type="password" required />
            <x-forms.input label="Nama Pegawai" name="nama" value="{{ old('nama') }}" required />
            <x-forms.input label="NIP" name="nip" value="{{ old('nip') }}" />
            <x-forms.input label="Telepon" name="telepon" value="{{ old('telepon') }}" />
            <div>
                <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat</label>
                <textarea name="alamat" class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600">{{ old('alamat') }}</textarea>
            </div>
            <div class="flex gap-2">
                <x-button type="primary">Simpan</x-button>
                <a href="{{ route('data-pengguna.pegawai.index') }}"><x-button type="secondary">Batal</x-button></a>
            </div>
        </form>
    </div>
</x-layouts.app>
