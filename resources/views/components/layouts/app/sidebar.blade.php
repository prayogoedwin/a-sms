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

                            @if(auth()->user()->hasPermission('view-pegawais') || auth()->user()->hasPermission('view-gurus') || auth()->user()->hasPermission('view-siswas'))
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

                            @if(auth()->user()->hasPermission('view-jadwal-mengajar') || auth()->user()->hasPermission('view-jadwal-mengajar-semua') || auth()->user()->hasPermission('view-rekap-wali') || auth()->user()->hasPermission('view-jadwal-siswa') || auth()->user()->hasPermission('view-nilai-siswa') || auth()->user()->hasPermission('view-absensi-siswa'))
                                <x-layouts.sidebar-two-level-link-parent title="Akademik" icon="fas-clipboard-check"
                                    :active="request()->routeIs('akademik*')">
                                    @if(auth()->user()->hasPermission('view-jadwal-mengajar') || auth()->user()->hasPermission('view-jadwal-mengajar-semua'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('akademik.jadwal-guru') }}" icon='fas-calendar-days'
                                            :active="request()->routeIs('akademik.jadwal-guru*') || request()->routeIs('akademik.nilai*') || request()->routeIs('akademik.absensi*') || request()->routeIs('akademik.rekap-absensi*')">
                                            Jadwal Mengajar
                                        </x-layouts.sidebar-two-level-link>
                                    @endif
                                    @if(auth()->user()->hasPermission('view-rekap-wali'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('akademik.rekap-wali') }}" icon='fas-clipboard-list'
                                            :active="request()->routeIs('akademik.rekap-wali*')">Rekap Wali Kelas</x-layouts.sidebar-two-level-link>
                                    @endif
                                    @if(auth()->user()->hasPermission('view-jadwal-siswa'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('akademik.siswa.jadwal') }}" icon='fas-table'
                                            :active="request()->routeIs('akademik.siswa.jadwal')">Jadwal kelas</x-layouts.sidebar-two-level-link>
                                    @endif
                                    @if(auth()->user()->hasPermission('view-nilai-siswa'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('akademik.siswa.nilai') }}" icon='fas-star'
                                            :active="request()->routeIs('akademik.siswa.nilai')">Nilai</x-layouts.sidebar-two-level-link>
                                    @endif
                                    @if(auth()->user()->hasPermission('view-absensi-siswa'))
                                        <x-layouts.sidebar-two-level-link href="{{ route('akademik.siswa.absensi') }}" icon='fas-user-check'
                                            :active="request()->routeIs('akademik.siswa.absensi')">Absensi</x-layouts.sidebar-two-level-link>
                                    @endif
                                </x-layouts.sidebar-two-level-link-parent>
                            @endif
                        </ul>
                    </nav>
                </div>
            </aside>
