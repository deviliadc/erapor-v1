<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    <div class="rounded-2xl bg-white dark:border-gray-800 dark:bg-white/[0.03] p-4">
        {{-- Dropdown Pilih Tahun Semester dan Kelas --}}
        <form method="GET" class="mb-4 flex flex-wrap gap-4">
            <div>
                <label for="tahun_semester_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Tahun Semester
                </label>
                <select name="tahun_semester_id" id="tahun_semester_id" onchange="this.form.submit()"
                    class="mt-1 rounded border px-3 py-2">
                    @foreach ($daftarTahunSemester as $ts)
                        <option value="{{ $ts->id }}"
                            {{ $ts->id == request('tahun_semester_id', $tahunAktif->id) ? 'selected' : '' }}>
                            {{ $ts->tahun }} - {{ $ts->semester }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="kelas_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Kelas
                </label>
                <select name="kelas_id" id="kelas_id" onchange="this.form.submit()"
                    class="mt-1 rounded border px-3 py-2">
                    <option value="">Pilih Kelas</option>
                    @foreach ($daftarKelas as $kls)
                        <option value="{{ $kls->id }}" {{ request('kelas_id') == $kls->id ? 'selected' : '' }}>
                            {{ $kls->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="periode" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Periode
                </label>
                <select name="periode" id="periode" onchange="this.form.submit()"
                    class="mt-1 rounded border px-3 py-2">
                    <option value="tengah" {{ $periode == 'tengah' ? 'selected' : '' }}>Tengah Semester</option>
                    <option value="akhir" {{ $periode == 'akhir' ? 'selected' : '' }}>Akhir Semester</option>
                </select>
            </div>
        </form>

        @if (!$kelasDipilih)
            <div class="text-center text-gray-500 py-8">
                Silakan pilih kelas terlebih dahulu untuk menampilkan mapel yang tersedia.
            </div>
        @elseif ($mapel->isEmpty())
            <div class="text-center text-gray-500 py-8">
                Tidak ada mata pelajaran yang bisa ditampilkan. Silahkan tambahkan mata pelajaran untuk kelas ini.
            </div>
        @else
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">
                Mapel Kelas {{ $kelasDipilih->nama }}
            </h2>

            <div x-data="{ activeTab: '{{ $activeTab ?: $mapel->first()?->mapel->id ?? '' }}', editMode: false }">
                <form action="{{ route('nilai-mapel.bulk-store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="mapel_id" x-model="activeTab">
                    <input type="hidden" name="kelas_id" value="{{ $kelasDipilih->id }}">
                    <input type="hidden" name="tahun_semester_id" value="{{ $tahunAktif->id }}">
                    {{-- <input type="hidden" name="periode" value="{{ request('periode', 'tengah') }}"> --}}
                    <input type="hidden" name="periode" value="{{ $periode }}">

                    {{-- Tombol Edit --}}
                    <div class="flex justify-end gap-2 mb-4">
                        <button type="button" @click="editMode = !editMode"
                            class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                            <span x-show="!editMode">Edit Nilai</span>
                            <span x-show="editMode">Batal Edit</span>
                        </button>
                    </div>

                    {{-- Tabs Mapel --}}
                    <div
                        class="flex space-x-2 border-b mb-4 overflow-x-auto whitespace-nowrap scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                        @foreach ($mapel as $gk)
                            @if ($gk->mapel)
                                <a href="?kelas_id={{ $kelasDipilih->id }}&mapel={{ $gk->mapel->id }}&tahun_semester_id={{ $tahunAktif->id }}"
                                    @click.prevent="activeTab = '{{ $gk->mapel->id }}'"
                                    :class="{ 'border-b-2 border-brand-500 font-semibold text-brand-600': activeTab === '{{ $gk->mapel->id }}' }"
                                    class="px-4 py-2 text-sm hover:text-brand-600 transition">
                                    {{ $gk->mapel->nama }}
                                </a>
                            @endif
                        @endforeach
                    </div>

                    {{-- Konten per Tab Mapel --}}
                    @foreach ($mapel as $gk)
                        @if ($gk->mapel)
                            @php
                                // $tpList = $tujuanPembelajaranList[$gk->mapel->id] ?? collect();
                                // $lmList = $lingkupMateriList[$gk->mapel->id] ?? collect();
                                // $nilaiMapelTab = $nilaiMapel[$gk->mapel->id] ?? [];
                                // Ambil lingkup materi sesuai periode
    $lmList = $gk->lingkupMateri->where('periode', $periode)->values();

    // Ambil tujuan pembelajaran dari lingkup materi periode yang sesuai
    $tpList = $lmList->flatMap(function ($lm) {
        return $lm->tujuanPembelajaran;
    })->values();

    $tujuanPembelajaranList[$gk->mapel->id] = $tpList;
    $lingkupMateriList[$gk->mapel->id] = $lmList;
                            @endphp
                            <div x-show="activeTab === '{{ $gk->mapel->id }}'" x-cloak>
                                <div
                                    class="w-full overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                                    <table
                                        class="min-w-[1200px] w-full text-sm text-center table-auto whitespace-nowrap">
                                        <thead class="text-gray-700 bg-gray-100 dark:bg-gray-800 dark:text-gray-200">
                                            <tr>
                                                <th rowspan="2" class="px-3 py-2 bg-gray dark:bg-gray-900">No Absen
                                                </th>
                                                <th rowspan="2" class="px-3 py-2 bg-gray dark:bg-gray-900">Nama Siswa
                                                </th>
                                                <th colspan="{{ max($tpList->count(), 1) }}"
                                                    class="px-3 py-2 bg-success-50 text-success-700 dark:bg-success-900 dark:text-success-200">
                                                    Formatif</th>
                                                <th rowspan="2"
                                                    class="px-3 py-2 bg-success-100 text-success-800 dark:bg-success-800 dark:text-success-100">
                                                    NA</th>
                                                <th colspan="{{ max($lmList->count(), 1) }}"
                                                    class="px-3 py-2 bg-warning-50 text-warning-700 dark:bg-warning-900 dark:text-warning-200">
                                                    Sumatif</th>
                                                <th rowspan="2"
                                                    class="px-3 py-2 bg-warning-100 text-warning-800 dark:bg-warning-800 dark:text-warning-100">
                                                    NA</th>
                                                <th colspan="2"
                                                    class="px-3 py-2 bg-brand-50 text-brand-700 dark:bg-brand-900 dark:text-brand-200">
                                                    Tengah Semester</th>
                                                <th rowspan="2"
                                                    class="px-3 py-2 bg-brand-100 text-brand-800 dark:bg-brand-800 dark:text-brand-100">
                                                    NA</th>
                                                @if ($periode == 'akhir')
                                                    <th colspan="2"
                                                        class="px-3 py-2 bg-error-50 text-error-700 dark:bg-error-900 dark:text-error-200">
                                                        Akhir Semester</th>
                                                    <th rowspan="2"
                                                        class="px-3 py-2 bg-error-100 text-error-800 dark:bg-error-800 dark:text-error-100">
                                                        NA</th>
                                                @endif
                                            </tr>
                                            <tr>
                                                @forelse ($tpList as $tp)
                                                    <th
                                                        class="px-2 py-1 bg-success-100 text-success-800 dark:bg-success-800 dark:text-success-100">
                                                        {{ $tp->subbab ?? '-' }}</th>
                                                @empty
                                                    <th
                                                        class="px-2 py-1 bg-success-100 text-success-800 dark:bg-success-800 dark:text-success-100">
                                                        -</th>
                                                @endforelse
                                                @forelse ($lmList as $bab)
                                                    <th
                                                        class="px-2 py-1 bg-warning-100 text-warning-800 dark:bg-warning-800 dark:text-warning-100">
                                                        {{ is_object($bab) ? $bab->nama : $bab }}</th>
                                                @empty
                                                    <th
                                                        class="px-2 py-1 bg-warning-100 text-warning-800 dark:bg-warning-800 dark:text-warning-100">
                                                        -</th>
                                                @endforelse
                                                <th
                                                    class="px-2 py-1 bg-brand-100 text-brand-800 dark:bg-brand-800 dark:text-brand-100">
                                                    Non Tes</th>
                                                <th
                                                    class="px-2 py-1 bg-brand-100 text-brand-800 dark:bg-brand-800 dark:text-brand-100">
                                                    Tes</th>
                                                @if ($periode == 'akhir')
                                                    <th
                                                        class="px-2 py-1 bg-error-100 text-error-800 dark:bg-error-800 dark:text-error-100">
                                                        Non Tes</th>
                                                    <th
                                                        class="px-2 py-1 bg-error-100 text-error-800 dark:bg-error-800 dark:text-error-100">
                                                        Tes</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-900">
                                            @forelse ($siswaList as $item)
                                                @php
                                                    $ksId = $item->id;
                                                    $siswa = $item->siswa;
                                                    $noAbsen = $item->no_absen;
                                                    $nilaiFormatif = collect();
                                                    foreach ($tpList as $tp) {
                                                        $nilai =
                                                            old("formatif.$siswa->id.$tp->id") ??
                                                            ($nilaiMapelTab['formatif'][$siswa->id][$tp->id] ?? null);
                                                        $nilaiFormatif->push(
                                                            is_numeric($nilai) ? (float) $nilai : null,
                                                        );
                                                    }
                                                    $nilaiSumatif = collect();
                                                    foreach ($lmList as $lm) {
                                                        $babKey = is_object($lm) ? $lm->id : $lm;
                                                        $nilai =
                                                            old("sumatif.$siswa->id.$babKey") ??
                                                            ($nilaiMapelTab['sumatif'][$siswa->id][$babKey] ?? null);
                                                        $nilaiSumatif->push(is_numeric($nilai) ? (float) $nilai : null);
                                                    }
                                                    $tesUTS =
                                                        old("uts.$siswa->id.tes") ??
                                                        ($nilaiMapelTab['uts'][$siswa->id]['tes'] ?? null);
                                                    $nonTesUTS =
                                                        old("uts.$siswa->id.non_tes") ??
                                                        ($nilaiMapelTab['uts'][$siswa->id]['non_tes'] ?? null);
                                                    $tesUAS =
                                                        old("uas.$siswa->id.tes") ??
                                                        ($nilaiMapelTab['uas'][$siswa->id]['tes'] ?? null);
                                                    $nonTesUAS =
                                                        old("uas.$siswa->id.non_tes") ??
                                                        ($nilaiMapelTab['uas'][$siswa->id]['non_tes'] ?? null);
                                                    $naFormatif = $nilaiFormatif->filter()->avg();
                                                    $naSumatif = $nilaiSumatif->filter()->avg();
                                                    $naUTS = collect([$naFormatif, $naSumatif, $tesUTS, $nonTesUTS])
                                                        ->filter()
                                                        ->avg();
                                                    $naUAS = collect([
                                                        $naFormatif,
                                                        $naSumatif,
                                                        $tesUTS,
                                                        $nonTesUTS,
                                                        $tesUAS,
                                                        $nonTesUAS,
                                                    ])
                                                        ->filter()
                                                        ->avg();
                                                @endphp
                                                <tr>
                                                    <td class="text-left px-3 py-2 font-semibold">{{ $item->no_absen }}
                                                    </td>
                                                    <td class="text-left px-3 py-2">{{ $siswa->nama }}</td>
                                                    {{-- Formatif --}}
                                                    @if ($tpList->isNotEmpty())
                                                        @foreach ($tpList as $tp)
                                                            @php
                                                                $val = old("nilai.$ksId.tp_$tp->id") ?? ($nilaiMapelTab['formatif'][$ksId][$tp->id] ?? null);
                                                            @endphp
                                                            <td>
                                                                <input x-show="editMode" type="number"
                                                                    name="nilai[{{ $ksId }}][tp_{{ $tp->id }}]"
                                                                    value="{{ $val }}"
                                                                    class="input-nilai w-16 text-sm border rounded" />
                                                                <span x-show="!editMode">
                                                                    {{ is_null($val) || $val === '' ? '-' : $val }}
                                                                </span>
                                                            </td>
                                                        @endforeach
                                                    @else
                                                        <td class="bg-success-50">-</td>
                                                    @endif
                                                    {{-- NA Formatif --}}
                                                    <td class="bg-success-50">
                                                        {{ $naFormatif !== null ? round($naFormatif, 2) : '-' }}</td>
                                                    {{-- Sumatif --}}
                                                    @if ($lmList->isNotEmpty())
                                                        @foreach ($lmList as $bab)
                                                            @php
                                                                $babKey = is_object($bab) ? $bab->id : $bab;
                                                                // $val =
                                                                //     old("sumatif.$siswa->id.$babKey") ??
                                                                //     ($nilaiMapelTab['sumatif'][$siswa->id][$babKey] ??
                                                                //         null);
                                                                $val = old("nilai.$ksId.sumatif_$babKey") ?? ($nilaiMapelTab['sumatif'][$ksId][$babKey] ?? null);
                                                            @endphp
                                                            <td>
                                                                <input x-show="editMode" type="number"
                                                                    name="nilai[{{ $ksId }}][sumatif_{{ $babKey }}]"
                                                                    value="{{ $val }}"
                                                                    class="input-nilai w-16 text-sm border rounded" />
                                                                <span x-show="!editMode">
                                                                    {{ is_null($val) || $val === '' ? '-' : $val }}
                                                                </span>
                                                            </td>
                                                        @endforeach
                                                    @else
                                                        <td class="bg-warning-50">-</td>
                                                    @endif
                                                    {{-- NA Sumatif --}}
                                                    <td class="bg-warning-50">
                                                        {{ $naSumatif !== null ? round($naSumatif, 2) : '-' }}</td>
                                                    {{-- UTS --}}
                                                    <td>
                                                        <input x-show="editMode" type="number"
                                                            name="nilai[{{ $ksId }}][uts_nontes]"
                                                            value="{{ $nonTesUTS }}"
                                                            class="input-nilai w-16 text-sm border rounded" />
                                                        <span x-show="!editMode">
                                                            {{ is_null($nonTesUTS) || $nonTesUTS === '' ? '-' : $nonTesUTS }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <input x-show="editMode" type="number"
                                                            name="nilai[{{ $ksId }}][uts_tes]"
                                                            value="{{ $tesUTS }}"
                                                            class="input-nilai w-16 text-sm border rounded" />
                                                        <span x-show="!editMode">
                                                            {{ is_null($tesUTS) || $tesUTS === '' ? '-' : $tesUTS }}
                                                        </span>
                                                    </td>
                                                    <td class="bg-brand-100">
                                                        {{ $naUTS !== null ? round($naUTS, 2) : '-' }}</td>
                                                    {{-- UAS --}}
                                                    @if ($periode == 'akhir')
                                                        <td>
                                                            <input x-show="editMode" type="number"
                                                                name="nilai[{{ $ksId }}][uas_nontes]"
                                                                value="{{ $nonTesUAS }}"
                                                                class="input-nilai w-16 text-sm border rounded" />
                                                            <span x-show="!editMode">
                                                                {{ is_null($nonTesUAS) || $nonTesUAS === '' ? '-' : $nonTesUAS }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <input x-show="editMode" type="number"
                                                                name="nilai[{{ $ksId }}][uas_tes]"
                                                                value="{{ $tesUAS }}"
                                                                class="input-nilai w-16 text-sm border rounded" />
                                                            <span x-show="!editMode">
                                                                {{ is_null($tesUAS) || $tesUAS === '' ? '-' : $tesUAS }}
                                                            </span>
                                                        </td>
                                                    <td class="bg-error-100">
                                                        {{ $naUAS !== null ? round($naUAS, 2) : '-' }}</td>
                                                    @endif
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="100%" class="text-center py-4 text-gray-500">Tidak
                                                        ada siswa.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    {{-- Tombol Simpan --}}
                    <div class="pt-5 flex justify-end mt-4" x-show="editMode" x-cloak>
                        <button type="submit"
                            class="px-4 py-3 gap-2 text-sm font-medium text-white rounded-lg bg-success-500 shadow-theme-xs hover:bg-success-600">
                            Simpan Nilai
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</x-app-layout>
