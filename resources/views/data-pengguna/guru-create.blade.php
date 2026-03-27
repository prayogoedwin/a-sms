<x-layouts.app>
    <div class="mb-6"><h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Tambah Guru</h1></div>
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 max-w-2xl">
        <form method="POST" action="{{ route('data-pengguna.guru.store') }}" class="space-y-3">
            @csrf
            <x-forms.input label="Nama User" name="name" value="{{ old('name') }}" required />
            <x-forms.input label="Email" name="email" type="email" value="{{ old('email') }}" required />
            <x-forms.input label="Password" name="password" type="password" required />
            <x-forms.input label="Konfirmasi Password" name="password_confirmation" type="password" required />
            <x-forms.input label="Nama Guru" name="nama" value="{{ old('nama') }}" required />
            <x-forms.input label="NIP" name="nip" value="{{ old('nip') }}" />
            <div class="flex gap-2">
                <x-button type="primary">Simpan</x-button>
                <a href="{{ route('data-pengguna.guru.index') }}"><x-button type="secondary">Batal</x-button></a>
            </div>
        </form>
    </div>
</x-layouts.app>
