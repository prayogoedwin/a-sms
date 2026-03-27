<x-layouts.app>
    <div class="mb-6"><h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Tambah Jadwal</h1></div>
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 max-w-2xl">
        <form method="POST" action="{{ route('master-data.jadwal.store') }}" class="space-y-3">
            @csrf
            <div>
                <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kelas</label>
                <select name="kelas_id" class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600" required>
                    <option value="">Pilih Kelas</option>
                    @foreach($kelas as $item)
                        <option value="{{ $item->id }}" @selected(old('kelas_id') == $item->id)>{{ $item->tingkat->nama }} {{ $item->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mata Pelajaran</label>
                <select name="mata_pelajaran_id" class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600" required>
                    <option value="">Pilih Mata Pelajaran</option>
                    @foreach($mataPelajarans as $mapel)
                        <option value="{{ $mapel->id }}" @selected(old('mata_pelajaran_id') == $mapel->id)>{{ $mapel->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Guru</label>
                <select name="guru_id" class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600" required>
                    <option value="">Pilih Guru</option>
                    @foreach($gurus as $guru)
                        <option value="{{ $guru->id }}" @selected(old('guru_id') == $guru->id)>{{ $guru->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hari</label>
                    <select name="hari" class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600">
                        @foreach(['senin','selasa','rabu','kamis','jumat','sabtu'] as $hari)
                            <option value="{{ $hari }}" @selected(old('hari') === $hari)>{{ ucfirst($hari) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Semester</label>
                    <select name="semester" class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600">
                        <option value="ganjil" @selected(old('semester') === 'ganjil')>Ganjil</option>
                        <option value="genap" @selected(old('semester') === 'genap')>Genap</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <x-forms.input label="Jam Mulai" name="jam_mulai" type="time" value="{{ old('jam_mulai') }}" required />
                <x-forms.input label="Jam Selesai" name="jam_selesai" type="time" value="{{ old('jam_selesai') }}" required />
            </div>
            <div>
                <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun ajaran</label>
                <select name="tahun_ajaran_id" class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600" required>
                    <option value="">Pilih tahun ajaran</option>
                    @foreach($tahunAjarans as $ta)
                        <option value="{{ $ta->id }}" @selected(old('tahun_ajaran_id') == $ta->id)>{{ $ta->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <x-button type="primary">Simpan</x-button>
                <a href="{{ route('master-data.penjadwalan.index') }}"><x-button type="secondary">Batal</x-button></a>
            </div>
        </form>
    </div>
</x-layouts.app>
