<x-layouts.app>
    <div class="mb-6"><h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Tambah Tahun Ajaran</h1></div>
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 max-w-2xl">
        <form method="POST" action="{{ route('master-data.tahun-ajaran.store') }}" class="space-y-3">
            @csrf
            <x-forms.input label="Nama (contoh 2026/2027)" name="nama" value="{{ old('nama') }}" placeholder="2026/2027" required />
            <x-forms.input label="Tanggal mulai (opsional)" name="tanggal_mulai" type="date" value="{{ old('tanggal_mulai') }}" />
            <x-forms.input label="Tanggal selesai (opsional)" name="tanggal_selesai" type="date" value="{{ old('tanggal_selesai') }}" />
            <div class="flex gap-2">
                <x-button type="primary">Simpan</x-button>
                <a href="{{ route('master-data.tahun-ajaran.index') }}"><x-button type="secondary">Batal</x-button></a>
            </div>
        </form>
    </div>
</x-layouts.app>
