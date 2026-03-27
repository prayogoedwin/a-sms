<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Data Pengguna Sekolah</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Buat data pegawai, guru, dan siswa sekaligus user login.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <h2 class="font-semibold mb-3">Tambah Pegawai</h2>
            <form method="POST" action="{{ route('data-pengguna.pegawai.store') }}" class="space-y-2">
                @csrf
                <input name="name" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" placeholder="Nama User">
                <input name="email" type="email" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" placeholder="Email">
                <input name="password" type="password" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" placeholder="Password">
                <input name="password_confirmation" type="password" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" placeholder="Konfirmasi Password">
                <input name="nama" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" placeholder="Nama Pegawai">
                <input name="nip" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" placeholder="NIP (opsional)">
                <x-button type="primary">Simpan Pegawai</x-button>
            </form>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <h2 class="font-semibold mb-3">Tambah Guru</h2>
            <form method="POST" action="{{ route('data-pengguna.guru.store') }}" class="space-y-2">
                @csrf
                <input name="name" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" placeholder="Nama User">
                <input name="email" type="email" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" placeholder="Email">
                <input name="password" type="password" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" placeholder="Password">
                <input name="password_confirmation" type="password" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" placeholder="Konfirmasi Password">
                <input name="nama" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" placeholder="Nama Guru">
                <input name="nip" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" placeholder="NIP (opsional)">
                <x-button type="primary">Simpan Guru</x-button>
            </form>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <h2 class="font-semibold mb-3">Tambah Siswa</h2>
            <form method="POST" action="{{ route('data-pengguna.siswa.store') }}" class="space-y-2">
                @csrf
                <input name="name" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" placeholder="Nama User">
                <input name="email" type="email" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" placeholder="Email">
                <input name="password" type="password" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" placeholder="Password">
                <input name="password_confirmation" type="password" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" placeholder="Konfirmasi Password">
                <input name="nama" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" placeholder="Nama Siswa">
                <input name="nis" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" placeholder="NIS (opsional)">
                <x-button type="primary">Simpan Siswa</x-button>
            </form>
        </div>
    </div>
</x-layouts.app>
