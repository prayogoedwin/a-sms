<x-layouts.app>
    <div class="mb-6"><h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Edit Tahun Ajaran</h1></div>
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 max-w-2xl">
        <form method="POST" action="{{ route('master-data.tahun-ajaran.update', $tahunAjaran) }}" class="space-y-3">
            @csrf
            @method('PUT')
            <x-forms.input label="Nama" name="nama" value="{{ old('nama', $tahunAjaran->nama) }}" required />
            <x-forms.input label="Tanggal mulai (opsional)" name="tanggal_mulai" type="date" value="{{ old('tanggal_mulai', $tahunAjaran->tanggal_mulai?->format('Y-m-d')) }}" />
            <x-forms.input label="Tanggal selesai (opsional)" name="tanggal_selesai" type="date" value="{{ old('tanggal_selesai', $tahunAjaran->tanggal_selesai?->format('Y-m-d')) }}" />
            <div class="flex gap-2">
                <x-button type="primary">Update</x-button>
                <a href="{{ route('master-data.tahun-ajaran.index') }}"><x-button type="secondary">Batal</x-button></a>
            </div>
        </form>
    </div>
</x-layouts.app>
