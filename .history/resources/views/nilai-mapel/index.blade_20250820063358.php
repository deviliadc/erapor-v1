<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    <div x-data="{
        activeTab: '{{ old('active_tab', request('mapel', $mapel->first()?->mapel->id ?? '')) }}',
        editMode: false,
        periode: '{{ request('periode', $periode) }}'
    }" class="rounded-2xl bg-white dark:border-gray-800 dark:bg-white/[0.03] p-4">

        {{-- Dropdown Pilih Tahun Semester, Kelas, Periode --}}
        {{-- <form method="GET" class="mb-4 flex flex-wrap gap-4">
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
                <select name="periode" id="periode" x-model="periode" onchange="this.form.submit()"
                    class="mt-1 rounded border px-3 py-2">
                    <option value="tengah" {{ $periode == 'tengah' ? 'selected' : '' }}>Tengah Semester</option>
                    <option value="akhir" {{ $periode == 'akhir' ? 'selected' : '' }}>Akhir Semester</option>
                </select>
            </div>
        </form> --}}

                <form method="GET" class="mb-4 flex flex-wrap gap-4">
            <x-form.select
                label="Tahun Semester"
                name="tahun_semester_id"
                {{-- :options="$daftarTahunSemester->mapWithKeys(fn($ts) => [
                    $ts->id => ($ts->tahun . ' - ' . ucfirst($ts->semester))
                ])" --}}
                :options="$daftarTahunSemester->mapWithKeys(fn($ts) => [
            $ts->id => ($ts->tahunAjaran->tahun ?? '-') . ' - ' . ucfirst($ts->semester)
        ])"
                :selected="request('tahun_semester_id', $tahunAktif->id)"
                placeholder="Pilih Tahun Semester"
                searchable
                required
                onchange="this.form.submit()"
            />

            <x-form.select
                label="Kelas"
                name="kelas_id"
                :options="$daftarKelas->mapWithKeys(fn($kls) => [$kls->id => $kls->nama])"
                :selected="request('kelas_id')"
                placeholder="Pilih Kelas"
                searchable
                required
                onchange="this.form.submit()"
            />

            <x-form.select
                label="Periode"
                name="periode"
                :options="['tengah' => 'Tengah Semester', 'akhir' => 'Akhir Semester']"
                :selected="$periode"
                placeholder="Pilih Periode"
                searchable
                required
                onchange="this.form.submit()"
            />
        </form>

        @if (!$kelasDipilih)
            <div class="text-center text-gray-500 py-8">
                Silakan pilih kelas terlebih dahulu untuk menampilkan mapel yang tersedia.
            </div>
        @elseif ($mapel->isEmpty())
            <div class="text-center text-gray-500 py-8">
                Tidak ada mata pelajaran yang bisa ditampilkan. Silakan tambahkan mata pelajaran untuk kelas ini.
            </div>
        @else
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">
                Mapel Kelas {{ $kelasDipilih->nama }}
            </h2>

            <form action="{{ role_route('nilai-mapel.update-batch') }}" method="POST">
                @csrf
                <input type="hidden" name="active_tab" :value="activeTab">
                {{-- <input type="hidden" name="mapel_id" x-model="activeTab"> --}}
                                <input type="hidden" name="mapel_id" :value="activeTab">
                <input type="hidden" name="kelas_id" value="{{ $kelasDipilih->id }}">
                <input type="hidden" name="tahun_semester_id" value="{{ $tahunAktif->id }}">
                <input type="hidden" name="periode" :value="periode">

                <div class="flex justify-end gap-2 mb-4">
                    <button type="button" @click="editMode = !editMode"
                        class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-blue-light-500 shadow hover:bg-blue-light-600">
                        <span x-show="!editMode">Edit Nilai</span>
                        <span x-show="editMode">Batal Edit</span>
                    </button>
                </div>

                {{-- Tab Mapel --}}
                <div
                    class="flex space-x-2 border-b mb-4 overflow-x-auto whitespace-nowrap scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                    @foreach ($mapel as $gk)
                        @if ($gk->mapel)
                            <a href="?kelas_id={{ $kelasDipilih->id }}&mapel={{ $gk->mapel->id }}&tahun_semester_id={{ $tahunAktif->id }}&periode={{ $periode }}"
                                @click.prevent="activeTab = '{{ $gk->mapel->id }}'"
                                :class="{ 'border-b-2 border-brand-500 font-semibold text-brand-600': activeTab === '{{ $gk->mapel->id }}' }"
                                class="px-4 py-2 text-sm hover:text-brand-600 transition">
                                {{ $gk->mapel->nama }}
                            </a>
                        @endif
                    @endforeach
                </div>
                @foreach ($mapel as $gk)
                    @if ($gk->mapel)
                        @php
                            $nilaiMapelTab = $nilaiMapel[$gk->mapel->id] ?? [];
                            $lmList = $lingkupMateriList[$gk->mapel->id] ?? collect();
                            $tpList = $tujuanPembelajaranList[$gk->mapel->id] ?? collect();
                        @endphp
                        <div x-show="activeTab === '{{ $gk->mapel->id }}'" x-cloak>
                            <div class="w-full overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                                <table class="min-w-[1200px] w-full text-sm text-center table-auto whitespace-nowrap">
                                    <thead class="text-gray-700 bg-gray-100 dark:bg-gray-800 dark:text-gray-200">
                                        <tr>
                                            <th rowspan="2" class="px-3 py-2 bg-gray dark:bg-gray-900">No Absen</th>
                                            <th rowspan="2" class="px-3 py-2 bg-gray dark:bg-gray-900">Nama Siswa
                                            </th>
                                            <th colspan="{{ max($tpList->count(), 1) }}"
                                                class="px-3 py-2 bg-success-50">Formatif</th>
                                            <th rowspan="2" class="px-3 py-2 bg-success-50">NA</th>
                                            <th colspan="{{ max($lmList->count(), 1) }}"
                                                class="px-3 py-2 bg-warning-50">Sumatif</th>
                                            <th rowspan="2" class="px-3 py-2 bg-warning-50">NA</th>
                                            <th colspan="2" class="px-3 py-2 bg-brand-50">Tengah Semester</th>
                                            <th rowspan="2" class="px-3 py-2 bg-brand-50">NA</th>
                                            @if ($periode == 'akhir')
                                                <th colspan="2" class="px-2 py-1 bg-error-50">Akhir Semester</th>
                                                <th rowspan="2" class="px-2 py-1 bg-error-50">NA</th>
                                            @endif
                                            <th rowspan="2" class="px-3 py-2 bg-blue-light-50">
                                                {{ $periode == 'tengah' ? 'Nilai Tengah Semester' : 'Nilai Akhir Semester' }}
                                            </th>
                                        </tr>
                                        <tr>
                                            @forelse ($tpList as $tp)
                                                <th class="px-2 py-1 bg-success-50">{{ $tp->subbab ?? '-' }}</th>
                                            @empty
                                                <th class="px-2 py-1 bg-success-50">-</th>
                                            @endforelse
                                            @forelse ($lmList as $bab)
                                                <th class="px-2 py-1 bg-warning-50">
                                                    {{ is_object($bab) ? $bab->nama : $bab }}</th>
                                            @empty
                                                <th class="px-2 py-1 bg-warning-50">-</th>
                                            @endforelse
                                            <th class="px-2 py-1 bg-brand-50">Non Tes</th>
                                            <th class="px-2 py-1 bg-brand-50">Tes</th>
                                            @if ($periode == 'akhir')
                                                <th class="px-2 py-1 bg-error-50">Non Tes</th>
                                                <th class="px-2 py-1 bg-error-50">Tes</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-900">
                                        @forelse ($siswaList as $item)
                                            @php
                                                $ksId = $item->id;
                                                $nilai = $rekapNilai[$gk->mapel->id][$ksId] ?? [];
                                            @endphp
                                            <tr>
                                                <td class="text-left px-3 py-2 font-semibold">{{ $item->no_absen }}</td>
                                                <td class="text-left px-3 py-2">{{ $item->siswa->nama }}</td>
                                                {{-- Formatif --}}
                                                @if ($tpList->isNotEmpty())
                                                    @foreach ($tpList as $tp)
                                                        {{-- @php
                                                            $val = old("nilai.$ksId.formatif_$tp->id") ?? ($nilaiMapelTab['formatif'][$ksId][$tp->id] ?? null);
                                                        @endphp
                                                        <td>
                                                            <input x-show="editMode" type="number"
                                                                name="nilai[{{ $ksId }}][formatif_{{ $tp->id }}]"
                                                                value="{{ $val }}"
                                                                class="input-nilai w-16 text-sm border rounded" />
                                                            <span x-show="!editMode">
                                                                {{ is_null($val) || $val === '' ? '-' : $val }}
                                                            </span>
                                                        </td> --}}
                                                    @endforeach
                                                @else
                                                    <td>-</td>
                                                @endif
                                                {{-- NA Formatif --}}
                                                <td class="bg-success-50">
                                                    {{ isset($nilai['na_formatif']) ? round($nilai['na_formatif'], 2) : '-' }}
                                                </td>
                                                {{-- Sumatif --}}
                                                @if ($lmList->isNotEmpty())
                                                    @foreach ($lmList as $bab)
                                                        @php
                                                            $babKey = is_object($bab) ? $bab->id : $bab;
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
                                                    <td>-</td>
                                                @endif
                                                {{-- NA Sumatif --}}
                                                <td class="bg-warning-50">
                                                    {{ isset($nilai['na_sumatif']) ? round($nilai['na_sumatif'], 2) : '-' }}
                                                </td>
                                                {{-- UTS --}}
                                                <td>
                                                    @if ($periode == 'tengah')
                                                        <input x-show="editMode" type="number"
                                                            name="nilai[{{ $ksId }}][uts_nontes]"
                                                            value="{{ old("nilai.$ksId.uts_nontes") ?? ($nilaiMapelTab['uts'][$ksId]['non_tes'] ?? '') }}"
                                                            class="input-nilai w-16 text-sm border rounded" />
                                                        <span x-show="!editMode">
                                                            {{ $nilaiMapelTab['uts'][$ksId]['non_tes'] ?? '-' }}
                                                        </span>
                                                    @else
                                                        {{-- Periode akhir: hanya tampil, tidak ada input name --}}
                                                        <span>{{ $nilaiMapelTab['uts'][$ksId]['non_tes'] ?? '-' }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($periode == 'tengah')
                                                        <input x-show="editMode" type="number"
                                                            name="nilai[{{ $ksId }}][uts_tes]"
                                                            value="{{ old("nilai.$ksId.uts_tes") ?? ($nilaiMapelTab['uts'][$ksId]['tes'] ?? '') }}"
                                                            class="input-nilai w-16 text-sm border rounded" />
                                                        <span x-show="!editMode">
                                                            {{ $nilaiMapelTab['uts'][$ksId]['tes'] ?? '-' }}
                                                        </span>
                                                    @else
                                                        <span>{{ $nilaiMapelTab['uts'][$ksId]['tes'] ?? '-' }}</span>
                                                    @endif
                                                </td>
                                                {{-- NA UTS --}}
                                                <td class="bg-brand-50">
                                                    {{ isset($nilai['na_uts']) ? round($nilai['na_uts'], 2) : '-' }}
                                                </td>
                                                {{-- UAS --}}
                                                @if ($periode == 'akhir')
                                                    <td>
                                                        <input x-show="editMode" type="number"
                                                            name="nilai[{{ $ksId }}][uas_nontes]"
                                                            value="{{ old("nilai.$ksId.uas_nontes") ?? ($nilaiMapelTab['uas'][$ksId]['non_tes'] ?? '') }}"
                                                            class="input-nilai w-16 text-sm border rounded" />
                                                        <span x-show="!editMode">
                                                            {{ $nilaiMapelTab['uas'][$ksId]['non_tes'] ?? '-' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <input x-show="editMode" type="number"
                                                            name="nilai[{{ $ksId }}][uas_tes]"
                                                            value="{{ old("nilai.$ksId.uas_tes") ?? ($nilaiMapelTab['uas'][$ksId]['tes'] ?? '') }}"
                                                            class="input-nilai w-16 text-sm border rounded" />
                                                        <span x-show="!editMode">
                                                            {{ $nilaiMapelTab['uas'][$ksId]['tes'] ?? '-' }}
                                                        </span>
                                                    </td>
                                                    {{-- NA UAS --}}
                                                    <td class="bg-error-50">
                                                        {{ isset($nilai['na_uas']) ? round($nilai['na_uas'], 2) : '-' }}
                                                    </td>
                                                @endif
                                                {{-- Nilai Akhir --}}
                                                <td class="bg-blue-light-50 font-bold">
                                                    {{ isset($nilai['nilai_akhir']) && $nilai['nilai_akhir'] !== null ? round($nilai['nilai_akhir'], 2) : '-' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="100%" class="text-center py-4 text-gray-500">
                                                    Tidak ada siswa.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                @endforeach
                <div class="pt-5 flex justify-end mt-4" x-show="editMode" x-cloak>
                    <button type="submit"
                        class="px-4 py-3 gap-2 text-sm font-medium text-white rounded-lg bg-success-500 shadow-theme-xs hover:bg-success-600">
                        Simpan Nilai
                    </button>
                </div>
            </form>
        @endif
    </div>
</x-app-layout>
