<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Profil Anak</h1>
    </div>

    <x-portal.anak-selector :anak-list="$anakList" :siswa="$siswa" />

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 max-w-3xl">
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div><dt class="text-gray-500">Nama</dt><dd class="font-medium">{{ $siswa->nama }}</dd></div>
            <div><dt class="text-gray-500">NIS</dt><dd>{{ $siswa->nis ?: '—' }}</dd></div>
            <div><dt class="text-gray-500">NISN</dt><dd>{{ $siswa->nisn ?: '—' }}</dd></div>
            <div><dt class="text-gray-500">Kelas</dt><dd>{{ $siswa->kelas ? $siswa->kelas->tingkat->nama . ' ' . $siswa->kelas->nama_kelas : '—' }}</dd></div>
            <div><dt class="text-gray-500">Jenis Kelamin</dt><dd>{{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : ($siswa->jenis_kelamin === 'P' ? 'Perempuan' : '—') }}</dd></div>
            <div><dt class="text-gray-500">Tempat, Tanggal Lahir</dt><dd>{{ $siswa->tempat_lahir ?: '—' }}{{ $siswa->tanggal_lahir ? ', ' . $siswa->tanggal_lahir->format('d/m/Y') : '' }}</dd></div>
            <div><dt class="text-gray-500">Agama</dt><dd>{{ $siswa->agama ?: '—' }}</dd></div>
            <div><dt class="text-gray-500">Status</dt><dd class="capitalize">{{ $siswa->status ?? 'aktif' }}</dd></div>
            <div class="md:col-span-2"><dt class="text-gray-500">Alamat</dt><dd>{{ $siswa->alamat ?: '—' }}</dd></div>
            <div><dt class="text-gray-500">Nama Ayah</dt><dd>{{ $siswa->nama_ayah ?: '—' }}</dd></div>
            <div><dt class="text-gray-500">Nama Ibu</dt><dd>{{ $siswa->nama_ibu ?: '—' }}</dd></div>
            <div><dt class="text-gray-500">Nama Wali</dt><dd>{{ $siswa->nama_wali ?: '—' }}</dd></div>
        </dl>
    </div>
</x-layouts.app>
