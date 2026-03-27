<x-layouts.app>
    <div class="mb-6"><h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Edit Tingkat</h1></div>
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 max-w-2xl">
        <form method="POST" action="{{ route('master-data.tingkat.update', $tingkat) }}" class="space-y-3">
            @csrf
            @method('PUT')
            <x-forms.input label="Nama Tingkat" name="nama" value="{{ old('nama', $tingkat->nama) }}" required />
            <x-forms.input label="Urutan" name="urutan" type="number" value="{{ old('urutan', $tingkat->urutan) }}" />
            <div class="flex gap-2">
                <x-button type="primary">Update</x-button>
                <a href="{{ route('master-data.tingkat.index') }}"><x-button type="secondary">Batal</x-button></a>
            </div>
        </form>
    </div>
</x-layouts.app>
