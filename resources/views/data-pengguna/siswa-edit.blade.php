@php $ots = app(\App\Services\OrangTuaService::class); @endphp
<x-layouts.app>
    <div class="mb-6"><h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Edit Siswa</h1></div>

    @if(session('status'))
        <p class="mb-4 text-sm text-green-600 dark:text-green-400">{{ session('status') }}</p>
    @endif
    @if(session('error'))
        <p class="mb-4 text-sm text-red-600 dark:text-red-400">{{ session('error') }}</p>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 max-w-3xl mb-6">
        <form method="POST" action="{{ route('data-pengguna.siswa.update', $siswa) }}" class="space-y-3">
            @csrf
            @method('PUT')
            <x-forms.input label="Nama User" name="name" value="{{ old('name', $siswa->user->name) }}" required />
            <x-forms.input label="Email" name="email" type="email" value="{{ old('email', $siswa->user->email) }}" required />
            <x-forms.input label="Password (opsional)" name="password" type="password" />
            <x-forms.input label="Konfirmasi Password" name="password_confirmation" type="password" />
            <x-forms.input label="Nama Siswa" name="nama" value="{{ old('nama', $siswa->nama) }}" required />
            <x-forms.input label="NIS" name="nis" value="{{ old('nis', $siswa->nis) }}" />
            <div>
                <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kelas</label>
                <select name="kelas_id" class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600">
                    <option value="">Pilih Kelas (opsional)</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" @selected(old('kelas_id', $siswa->kelas_id) == $kelas->id)>{{ $kelas->tingkat->nama }} {{ $kelas->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            @include('data-pengguna.partials.siswa-biodata', ['siswa' => $siswa])
            <div class="flex gap-2 pt-2">
                <x-button type="primary">Update</x-button>
                @if(auth()->user()->hasPermission('edit-jenis-pembayarans'))
                    <a href="{{ route('keuangan.assign-jenis-pembayaran.edit', $siswa) }}"><x-button type="secondary">Assign Pembayaran</x-button></a>
                @endif
                <a href="{{ route('data-pengguna.siswa.index') }}"><x-button type="secondary">Batal</x-button></a>
            </div>
        </form>
    </div>

    @if(auth()->user()->hasPermission('edit-siswas'))
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 max-w-3xl" x-data="{ mode: 'existing' }">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Akun Orang Tua Terhubung</h2>

            @if($siswa->orangTuas->isNotEmpty())
                <table class="min-w-full text-sm mb-6">
                    <thead>
                        <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2 pr-4">Nama</th>
                            <th class="py-2 pr-4">Email</th>
                            <th class="py-2 pr-4">Hubungan</th>
                            <th class="py-2 pr-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siswa->orangTuas as $ot)
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-2 pr-4">{{ $ot->nama }}</td>
                                <td class="py-2 pr-4">{{ $ot->user->email }}</td>
                                <td class="py-2 pr-4 capitalize">{{ $ots->labelHubungan($ot->pivot->hubungan) }}{{ $ot->pivot->is_penanggung_jawab ? ' (PJ)' : '' }}</td>
                                <td class="py-2 pr-4 text-right">
                                    <form method="POST" action="{{ route('data-pengguna.siswa.unlink-orang-tua', [$siswa, $ot]) }}" class="inline" onsubmit="return confirm('Lepas orang tua ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Lepas</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Belum ada akun orang tua terhubung.</p>
            @endif

            <h3 class="font-medium text-gray-800 dark:text-gray-100 mb-3">Hubungkan Orang Tua</h3>
            <div class="flex gap-2 mb-4">
                <button type="button" @click="mode = 'existing'" :class="mode === 'existing' ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700'" class="px-3 py-1 rounded text-sm">Pakai akun existing</button>
                <button type="button" @click="mode = 'baru'" :class="mode === 'baru' ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700'" class="px-3 py-1 rounded text-sm">Buat akun baru</button>
            </div>

            <form method="POST" action="{{ route('data-pengguna.siswa.link-orang-tua', $siswa) }}" class="space-y-3">
                @csrf
                <input type="hidden" name="mode" :value="mode">

                <div x-show="mode === 'existing'">
                    <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Orang Tua</label>
                    <select name="orang_tua_id" class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600">
                        <option value="">Pilih orang tua</option>
                        @foreach($orangTuas as $ot)
                            <option value="{{ $ot->id }}">{{ $ot->nama }} ({{ $ot->user->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div x-show="mode === 'baru'" class="space-y-3">
                    <x-forms.input label="Nama User" name="name" value="{{ old('name') }}" />
                    <x-forms.input label="Email" name="email" type="email" value="{{ old('email') }}" />
                    <x-forms.input label="Password" name="password" type="password" />
                    <x-forms.input label="Konfirmasi Password" name="password_confirmation" type="password" />
                    <x-forms.input label="Nama Orang Tua" name="nama" value="{{ old('nama') }}" />
                    <x-forms.input label="Telepon" name="telepon" value="{{ old('telepon') }}" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hubungan</label>
                        <select name="hubungan" class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600" required>
                            <option value="ayah">Ayah</option>
                            <option value="ibu">Ibu</option>
                            <option value="wali">Wali</option>
                        </select>
                    </div>
                    <label class="flex items-center gap-2 mt-6 text-sm text-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="is_penanggung_jawab" value="1" class="rounded">
                        Penanggung jawab
                    </label>
                </div>

                <x-button type="primary">Hubungkan</x-button>
            </form>
        </div>
    @endif
</x-layouts.app>
