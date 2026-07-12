<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Edit Orang Tua</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 max-w-2xl">
        @if($orangTua->siswas->isNotEmpty())
            <div class="mb-4 p-3 rounded bg-gray-50 dark:bg-gray-700/50 text-sm">
                <strong>Anak terhubung:</strong>
                {{ $orangTua->siswas->map(fn($s) => $s->nama . ' (' . ucfirst($s->pivot->hubungan) . ')')->join(', ') }}
            </div>
        @endif

        <form method="POST" action="{{ route('data-pengguna.orang-tua.update', $orangTua) }}" class="space-y-3">
            @csrf
            @method('PUT')
            <x-forms.input label="Nama User" name="name" value="{{ old('name', $orangTua->user->name) }}" required />
            <x-forms.input label="Email" name="email" type="email" value="{{ old('email', $orangTua->user->email) }}" required />
            <x-forms.input label="Password (opsional)" name="password" type="password" />
            <x-forms.input label="Konfirmasi Password" name="password_confirmation" type="password" />
            <x-forms.input label="Nama Orang Tua" name="nama" value="{{ old('nama', $orangTua->nama) }}" required />
            <x-forms.input label="NIK" name="nik" value="{{ old('nik', $orangTua->nik) }}" />
            <x-forms.input label="Telepon" name="telepon" value="{{ old('telepon', $orangTua->telepon) }}" />
            <x-forms.input label="Pekerjaan" name="pekerjaan" value="{{ old('pekerjaan', $orangTua->pekerjaan) }}" />
            <div>
                <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat</label>
                <textarea name="alamat" class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600">{{ old('alamat', $orangTua->alamat) }}</textarea>
            </div>
            <div class="flex gap-2">
                <x-button type="primary">Update</x-button>
                <a href="{{ route('data-pengguna.orang-tua.index') }}"><x-button type="secondary">Batal</x-button></a>
            </div>
        </form>
    </div>
</x-layouts.app>
