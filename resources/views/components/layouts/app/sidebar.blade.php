            <aside :class="{ 'w-full md:w-64': sidebarOpen, 'w-0 md:w-16 hidden md:block': !sidebarOpen }"
                class="bg-sidebar text-sidebar-foreground border-r border-gray-200 dark:border-gray-700 sidebar-transition overflow-hidden">
                <!-- Sidebar Content -->
                <div class="h-full flex flex-col">
                    <!-- Sidebar Menu -->
                    <nav class="flex-1 overflow-y-auto custom-scrollbar py-4">
                        <ul class="space-y-1 px-2">
                            <x-layouts.sidebar-link href="{{ route('dashboard') }}" icon='fas-house'
                                :active="request()->routeIs('dashboard*')">Dashboard</x-layouts.sidebar-link>

                            @if(auth()->user()->hasPermission('view-users') || auth()->user()->hasPermission('view-roles') || auth()->user()->hasPermission('view-permissions'))
                                <x-layouts.sidebar-two-level-link-parent title="Manajemen Akses" icon="fas-user-shield"
                                    :active="request()->routeIs('users*') || request()->routeIs('roles*') || request()->routeIs('permissions*')">
                                    @if(auth()->user()->hasPermission('view-users'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('users.index') }}" icon='fas-user'
                                            :active="request()->routeIs('users*')">Pengguna</x-layouts.sidebar-two-level-link>
                                    @endif
                                    @if(auth()->user()->hasPermission('view-roles'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('roles.index') }}" icon='fas-shield'
                                            :active="request()->routeIs('roles*')">Peran</x-layouts.sidebar-two-level-link>
                                    @endif
                                    @if(auth()->user()->hasPermission('view-permissions'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('permissions.index') }}" icon='fas-key'
                                            :active="request()->routeIs('permissions*')">Izin Akses</x-layouts.sidebar-two-level-link>
                                    @endif
                                </x-layouts.sidebar-two-level-link-parent>
                            @endif

                            @if(auth()->user()->hasPermission('view-pegawais') || auth()->user()->hasPermission('view-gurus') || auth()->user()->hasPermission('view-siswas') || auth()->user()->hasPermission('view-orang-tuas'))
                                <x-layouts.sidebar-two-level-link-parent title="Pengguna" icon="fas-users"
                                    :active="request()->routeIs('data-pengguna*')">
                                    @if(auth()->user()->hasPermission('view-pegawais'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('data-pengguna.pegawai.index') }}" icon='fas-id-card'
                                            :active="request()->routeIs('data-pengguna.pegawai*')">Pegawai</x-layouts.sidebar-two-level-link>
                                    @endif
                                    @if(auth()->user()->hasPermission('view-gurus'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('data-pengguna.guru.index') }}" icon='fas-chalkboard-teacher'
                                            :active="request()->routeIs('data-pengguna.guru*')">Guru</x-layouts.sidebar-two-level-link>
                                    @endif
                                    @if(auth()->user()->hasPermission('view-siswas'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('data-pengguna.siswa.index') }}" icon='fas-user-graduate'
                                            :active="request()->routeIs('data-pengguna.siswa*')">Siswa</x-layouts.sidebar-two-level-link>
                                    @endif
                                    @if(auth()->user()->hasPermission('view-orang-tuas'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('data-pengguna.orang-tua.index') }}" icon='fas-people-roof'
                                            :active="request()->routeIs('data-pengguna.orang-tua*')">Orang Tua</x-layouts.sidebar-two-level-link>
                                    @endif
                                </x-layouts.sidebar-two-level-link-parent>
                            @endif

                            @if(auth()->user()->hasPermission('view-tingkats') || auth()->user()->hasPermission('view-kelas') || auth()->user()->hasPermission('view-mata-pelajarans') || auth()->user()->hasPermission('view-tahun-ajarans'))
                                <x-layouts.sidebar-two-level-link-parent title="Master Data" icon="fas-database"
                                    :active="request()->routeIs('master-data*')">
                                    @if(auth()->user()->hasPermission('view-tingkats'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('master-data.tingkat.index') }}" icon='fas-layer-group'
                                            :active="request()->routeIs('master-data.tingkat*')">Tingkat</x-layouts.sidebar-two-level-link>
                                    @endif
                                    @if(auth()->user()->hasPermission('view-kelas'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('master-data.kelas.index') }}" icon='fas-school'
                                            :active="request()->routeIs('master-data.kelas*')">Kelas</x-layouts.sidebar-two-level-link>
                                    @endif
                                    @if(auth()->user()->hasPermission('view-mata-pelajarans'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('master-data.mapel.index') }}" icon='fas-book'
                                            :active="request()->routeIs('master-data.mapel*')">Mata Pelajaran</x-layouts.sidebar-two-level-link>
                                    @endif
                                    @if(auth()->user()->hasPermission('view-tahun-ajarans'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('master-data.tahun-ajaran.index') }}" icon='fas-calendar-days'
                                            :active="request()->routeIs('master-data.tahun-ajaran*')">Tahun Ajaran</x-layouts.sidebar-two-level-link>
                                    @endif
                                </x-layouts.sidebar-two-level-link-parent>
                            @endif

                            @if(auth()->user()->hasPermission('view-penjadwalan'))
                                <x-layouts.sidebar-link href="{{ route('master-data.penjadwalan.index') }}" icon='fas-calendar-plus'
                                    :active="request()->routeIs('master-data.penjadwalan*')">Penjadwalan</x-layouts.sidebar-link>
                            @endif

                            @php
                                $u = auth()->user();
                                $akademikStaff = $u->hasPermission('view-jadwal-mengajar')
                                    || $u->hasPermission('view-jadwal-mengajar-semua')
                                    || $u->hasPermission('view-rekap-wali');
                                $akademikSiswa = $u->hasRole('siswa')
                                    && (
                                        $u->hasPermission('view-jadwal-siswa')
                                        || $u->hasPermission('view-nilai-siswa')
                                        || $u->hasPermission('view-absensi-siswa')
                                    );
                            @endphp
                            @if($akademikStaff || $akademikSiswa)
                                <x-layouts.sidebar-two-level-link-parent title="Akademik" icon="fas-clipboard-check"
                                    :active="request()->routeIs('akademik*')">
                                    @if($u->hasPermission('view-jadwal-mengajar') || $u->hasPermission('view-jadwal-mengajar-semua'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('akademik.jadwal-guru') }}" icon='fas-calendar-days'
                                            :active="request()->routeIs('akademik.jadwal-guru*') || request()->routeIs('akademik.nilai*') || request()->routeIs('akademik.absensi*') || request()->routeIs('akademik.rekap-absensi*')">
                                            Jadwal Mengajar
                                        </x-layouts.sidebar-two-level-link>
                                    @endif
                                    @if($u->hasPermission('view-rekap-wali'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('akademik.rekap-wali') }}" icon='fas-clipboard-list'
                                            :active="request()->routeIs('akademik.rekap-wali*')">Rekap Wali Kelas</x-layouts.sidebar-two-level-link>
                                    @endif
                                    @if($u->hasRole('siswa') && $u->hasPermission('view-jadwal-siswa'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('akademik.siswa.jadwal') }}" icon='fas-table'
                                            :active="request()->routeIs('akademik.siswa.jadwal')">Jadwal kelas</x-layouts.sidebar-two-level-link>
                                    @endif
                                    @if($u->hasRole('siswa') && $u->hasPermission('view-nilai-siswa'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('akademik.siswa.nilai') }}" icon='fas-star'
                                            :active="request()->routeIs('akademik.siswa.nilai')">Nilai</x-layouts.sidebar-two-level-link>
                                    @endif
                                    @if($u->hasRole('siswa') && $u->hasPermission('view-absensi-siswa'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('akademik.siswa.absensi') }}" icon='fas-user-check'
                                            :active="request()->routeIs('akademik.siswa.absensi')">Absensi</x-layouts.sidebar-two-level-link>
                                    @endif
                                </x-layouts.sidebar-two-level-link-parent>
                            @endif

                            @php
                                $keuanganAccess = auth()->user()->hasPermission('view-tagihan-bulanans')
                                    || auth()->user()->hasPermission('view-jenis-pembayarans')
                                    || auth()->user()->hasPermission('view-laporan-keuangan');
                            @endphp
                            @if($keuanganAccess)
                                <x-layouts.sidebar-two-level-link-parent title="Keuangan" icon="fas-coins"
                                    :active="request()->routeIs('keuangan*')">
                                    @if(auth()->user()->hasPermission('view-jenis-pembayarans'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('keuangan.jenis-pembayaran.index') }}" icon='fas-list'
                                            :active="request()->routeIs('keuangan.jenis-pembayaran*')">Jenis Pembayaran</x-layouts.sidebar-two-level-link>
                                        <x-layouts.sidebar-two-level-link href="{{ route('keuangan.assign-jenis-pembayaran.index') }}" icon='fas-user-check'
                                            :active="request()->routeIs('keuangan.assign-jenis-pembayaran*')">Assign Siswa</x-layouts.sidebar-two-level-link>
                                    @endif
                                    @if(auth()->user()->hasPermission('view-tarif-pembayarans'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('keuangan.tarif-pembayaran.index') }}" icon='fas-tags'
                                            :active="request()->routeIs('keuangan.tarif-pembayaran*')">Tarif Pembayaran</x-layouts.sidebar-two-level-link>
                                    @endif
                                    @if(auth()->user()->hasPermission('view-tagihan-bulanans'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('keuangan.tagihan-bulanan.index') }}" icon='fas-file-invoice'
                                            :active="request()->routeIs('keuangan.tagihan-bulanan*')">Tagihan Bulanan</x-layouts.sidebar-two-level-link>
                                    @endif
                                    @if(auth()->user()->hasPermission('view-pembayarans'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('keuangan.pembayaran.index') }}" icon='fas-money-bill-wave'
                                            :active="request()->routeIs('keuangan.pembayaran*')">Pembayaran</x-layouts.sidebar-two-level-link>
                                    @endif
                                    @if(auth()->user()->hasPermission('view-pengajuan-pembayaran'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('keuangan.verifikasi-pengajuan.index') }}" icon='fas-clipboard-check'
                                            :active="request()->routeIs('keuangan.verifikasi-pengajuan*')">Verifikasi Pengajuan</x-layouts.sidebar-two-level-link>
                                    @endif
                                    @if(auth()->user()->hasPermission('view-laporan-keuangan'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('keuangan.laporan.index') }}" icon='fas-chart-pie'
                                            :active="request()->routeIs('keuangan.laporan*')">Laporan</x-layouts.sidebar-two-level-link>
                                    @endif
                                </x-layouts.sidebar-two-level-link-parent>
                            @endif

                            @php
                                $portalAccess = auth()->user()->hasPermission('view-profil-anak')
                                    || auth()->user()->hasPermission('view-tagihan-anak');
                            @endphp
                            @if($portalAccess)
                                <x-layouts.sidebar-two-level-link-parent title="Portal Orang Tua" icon="fas-house-user"
                                    :active="request()->routeIs('portal*')">
                                    @if(auth()->user()->hasPermission('view-profil-anak'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('portal.profil') }}" icon='fas-id-card'
                                            :active="request()->routeIs('portal.profil')">Profil Anak</x-layouts.sidebar-two-level-link>
                                    @endif
                                    @if(auth()->user()->hasPermission('view-jadwal-anak'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('portal.jadwal') }}" icon='fas-calendar'
                                            :active="request()->routeIs('portal.jadwal')">Jadwal</x-layouts.sidebar-two-level-link>
                                    @endif
                                    @if(auth()->user()->hasPermission('view-nilai-anak'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('portal.nilai') }}" icon='fas-star'
                                            :active="request()->routeIs('portal.nilai')">Nilai</x-layouts.sidebar-two-level-link>
                                    @endif
                                    @if(auth()->user()->hasPermission('view-absensi-anak'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('portal.absensi') }}" icon='fas-user-check'
                                            :active="request()->routeIs('portal.absensi')">Absensi</x-layouts.sidebar-two-level-link>
                                    @endif
                                    @if(auth()->user()->hasPermission('view-tagihan-anak'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('portal.tagihan') }}" icon='fas-file-invoice'
                                            :active="request()->routeIs('portal.tagihan')">Tagihan</x-layouts.sidebar-two-level-link>
                                    @endif
                                    @if(auth()->user()->hasPermission('view-pembayaran-anak'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('portal.pembayaran') }}" icon='fas-money-bill'
                                            :active="request()->routeIs('portal.pembayaran')">Pembayaran</x-layouts.sidebar-two-level-link>
                                    @endif
                                    @if(auth()->user()->hasPermission('view-pengajuan-sendiri'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('portal.pengajuan.index') }}" icon='fas-paper-plane'
                                            :active="request()->routeIs('portal.pengajuan*')">Pengajuan Bayar</x-layouts.sidebar-two-level-link>
                                    @endif
                                </x-layouts.sidebar-two-level-link-parent>
                            @endif
                        </ul>
                    </nav>
                </div>
            </aside>
