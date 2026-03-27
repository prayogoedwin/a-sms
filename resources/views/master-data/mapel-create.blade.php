<x-layouts.app>
    <div class="mb-6"><h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Tambah Mata Pelajaran</h1></div>
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 max-w-2xl">
        <form method="POST" action="{{ route('master-data.mapel.store') }}" class="space-y-3">
            @csrf
            <x-forms.input label="Kode" name="kode" value="{{ old('kode') }}" />
            <x-forms.input label="Nama Mata Pelajaran" name="nama" value="{{ old('nama') }}" required />
            <div class="flex gap-2">
                <x-button type="primary">Simpan</x-button>
                <a href="{{ route('master-data.mapel.index') }}"><x-button type="secondary">Batal</x-button></a>
            </div>
        </form>
    </div>
</x-layouts.app>
