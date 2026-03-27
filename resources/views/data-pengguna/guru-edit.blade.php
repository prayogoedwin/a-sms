<x-layouts.app>
    <div class="mb-6"><h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Edit Guru</h1></div>
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 max-w-2xl">
        <form method="POST" action="{{ route('data-pengguna.guru.update', $guru) }}" class="space-y-3">
            @csrf
            @method('PUT')
            <x-forms.input label="Nama User" name="name" value="{{ old('name', $guru->user->name) }}" required />
            <x-forms.input label="Email" name="email" type="email" value="{{ old('email', $guru->user->email) }}" required />
            <x-forms.input label="Password (opsional)" name="password" type="password" />
            <x-forms.input label="Konfirmasi Password" name="password_confirmation" type="password" />
            <x-forms.input label="Nama Guru" name="nama" value="{{ old('nama', $guru->nama) }}" required />
            <x-forms.input label="NIP" name="nip" value="{{ old('nip', $guru->nip) }}" />
            <div class="flex gap-2">
                <x-button type="primary">Update</x-button>
                <a href="{{ route('data-pengguna.guru.index') }}"><x-button type="secondary">Batal</x-button></a>
            </div>
        </form>
    </div>
</x-layouts.app>
