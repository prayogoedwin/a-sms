<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Master Data Sekolah</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola tingkat, kelas, mata pelajaran, dan jadwal.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            <h2 class="font-semibold mb-3">Tambah Tingkat</h2>
            <form method="POST" action="{{ route('master-data.tingkat.store') }}" class="space-y-2">
                @csrf
                <input name="nama" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" placeholder="Nama tingkat (contoh: 1)">
                <input name="urutan" type="number" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" placeholder="Urutan">
                <x-button type="primary">Simpan Tingkat</x-button>
            </form>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            <h2 class="font-semibold mb-3">Tambah Mata Pelajaran</h2>
            <form method="POST" action="{{ route('master-data.mapel.store') }}" class="space-y-2">
                @csrf
                <input name="kode" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" placeholder="Kode (opsional)">
                <input name="nama" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" placeholder="Nama mata pelajaran">
                <x-button type="primary">Simpan Mata Pelajaran</x-button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            <h2 class="font-semibold mb-3">Tambah Kelas</h2>
            <form method="POST" action="{{ route('master-data.kelas.store') }}" class="space-y-2">
                @csrf
                <select name="tingkat_id" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                    <option value="">Pilih Tingkat</option>
                    @foreach($tingkats as $tingkat)
                        <option value="{{ $tingkat->id }}">{{ $tingkat->nama }}</option>
                    @endforeach
                </select>
                <input name="nama_kelas" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" placeholder="Nama kelas">
                <select name="wali_kelas_guru_id" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                    <option value="">Pilih Wali Kelas (opsional)</option>
                    @foreach($gurus as $guru)
                        <option value="{{ $guru->id }}">{{ $guru->nama }}</option>
                    @endforeach
                </select>
                <x-button type="primary">Simpan Kelas</x-button>
            </form>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            <h2 class="font-semibold mb-3">Tambah Jadwal</h2>
            <form method="POST" action="{{ route('master-data.jadwal.store') }}" class="space-y-2">
                @csrf
                <select name="kelas_id" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                    <option value="">Pilih Kelas</option>
                    @foreach($kelas as $item)
                        <option value="{{ $item->id }}">{{ $item->tingkat->nama }} {{ $item->nama_kelas }}</option>
                    @endforeach
                </select>
                <select name="mata_pelajaran_id" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                    <option value="">Pilih Mata Pelajaran</option>
                    @foreach($mataPelajarans as $mapel)
                        <option value="{{ $mapel->id }}">{{ $mapel->nama }}</option>
                    @endforeach
                </select>
                <select name="guru_id" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                    <option value="">Pilih Guru</option>
                    @foreach($gurus as $guru)
                        <option value="{{ $guru->id }}">{{ $guru->nama }}</option>
                    @endforeach
                </select>
                <div class="grid grid-cols-2 gap-2">
                    <select name="hari" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                        @foreach(['senin','selasa','rabu','kamis','jumat','sabtu'] as $hari)
                            <option value="{{ $hari }}">{{ ucfirst($hari) }}</option>
                        @endforeach
                    </select>
                    <select name="semester" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                        <option value="ganjil">Ganjil</option>
                        <option value="genap">Genap</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <input type="time" name="jam_mulai" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                    <input type="time" name="jam_selesai" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                </div>
                <input name="tahun_ajaran" class="w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600" placeholder="Tahun ajaran, contoh 2025/2026">
                <x-button type="primary">Simpan Jadwal</x-button>
            </form>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
        <h2 class="font-semibold mb-3">Data Jadwal Terbaru</h2>
        <div class="space-y-2 text-sm">
            @forelse($jadwals as $item)
                <div class="border-b border-gray-100 dark:border-gray-700 pb-2">
                    {{ ucfirst($item->hari) }} | {{ $item->kelas->tingkat->nama }} {{ $item->kelas->nama_kelas }} |
                    {{ $item->mataPelajaran->nama }} | {{ $item->guru->nama }} | {{ $item->jam_mulai }}-{{ $item->jam_selesai }}
                </div>
            @empty
                <p class="text-gray-500">Belum ada jadwal.</p>
            @endforelse
        </div>
    </div>
</x-layouts.app>
