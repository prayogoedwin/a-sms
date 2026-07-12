@props(['anakList', 'siswa'])

@if($anakList->count() > 1)
    <form method="GET" action="{{ url()->current() }}" class="mb-4 flex flex-wrap items-end gap-3">
        @foreach(request()->except('anak_id') as $key => $val)
            @if(is_array($val))
                @foreach($val as $v)
                    <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                @endforeach
            @else
                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
            @endif
        @endforeach
        <div>
            <label class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Pilih anak</label>
            <select name="anak_id" onchange="this.form.submit()" class="rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 min-w-[14rem]">
                @foreach($anakList as $anak)
                    <option value="{{ $anak->id }}" @selected($siswa->id === $anak->id)>
                        {{ $anak->nama }}
                        @if($anak->kelas) ({{ $anak->kelas->tingkat->nama }} {{ $anak->kelas->nama_kelas }}) @endif
                    </option>
                @endforeach
            </select>
        </div>
    </form>
@elseif(auth()->user()->hasRole('super-admin'))
    <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        Mode super admin — menampilkan data: <strong>{{ $siswa->nama }}</strong>
        @if($anakList->count() > 1) ({{ $anakList->count() }} siswa tersedia) @endif
    </p>
@endif
