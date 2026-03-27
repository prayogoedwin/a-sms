@php
    $roleDisplayNames = [
        'super-admin' => 'Super Admin',
        'admin-sistem' => 'Admin Sistem',
        'pimpinan' => 'Pimpinan',
        'pegawai' => 'Pegawai',
        'guru' => 'Guru',
        'siswa' => 'Siswa',
    ];
    $user = auth()->user();
    $user->load('roles');
    $roleLabels = $user->roles
        ->sortBy('name')
        ->map(fn ($r) => $roleDisplayNames[$r->name] ?? \Illuminate\Support\Str::title(str_replace('-', ' ', $r->name)))
        ->implode(' · ');
@endphp

<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Dashboard') }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">
            Selamat datang, <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $user->name }}</span>.
            @if ($roleLabels !== '')
                <span class="block sm:inline sm:ml-1 mt-1 sm:mt-0">
                    Anda masuk sebagai <span class="font-medium text-gray-800 dark:text-gray-200">{{ $roleLabels }}</span>.
                </span>
            @else
                <span class="block sm:inline sm:ml-1 mt-1 sm:mt-0 text-amber-700 dark:text-amber-300">
                    Peran akun belum ditetapkan.
                </span>
            @endif
        </p>
    </div>

    <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6"
        x-data="{
            dateStr: '',
            timeStr: '',
            init() {
                this.tick();
                setInterval(() => this.tick(), 1000);
            },
            tick() {
                const now = new Date();
                this.dateStr = now.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric',
                });
                this.timeStr = now.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false,
                });
            },
        }"
    >
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-200 dark:border-gray-600 pb-4 mb-4">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Hari ini</p>
                <p class="text-lg font-semibold text-gray-800 dark:text-gray-100 capitalize" x-text="dateStr"></p>
            </div>
            <div class="text-left sm:text-right">
                <p class="text-sm text-gray-500 dark:text-gray-400">Jam</p>
                <p class="text-2xl font-mono font-semibold text-blue-600 dark:text-blue-400 tabular-nums" x-text="timeStr">
                </p>
            </div>
        </div>

        <div>
            <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wide mb-2">
                Tentang {{ config('app.name') }}
            </h2>
            <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                {{ config('app.name') }} adalah aplikasi manajemen sekolah untuk mengelola pengguna dan hak akses,
                master data (tingkat, kelas, mata pelajaran, tahun ajaran), penjadwalan jadwal mengajar, serta aktivitas
                akademik seperti input nilai, absensi, dan rekap terkait. Gunakan menu di sisi kiri sesuai izin akun Anda.
            </p>
        </div>
    </div>
</x-layouts.app>
