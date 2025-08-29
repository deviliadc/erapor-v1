<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    {{-- <div x-data="{
        activeTab: '{{ old('active_tab', request('active_tab', $mapel->first()?->mapel->id ?? '')) }}',
        editMode: false,
        periode: '{{ request('periode', $periode) }}'
    }" class="rounded-2xl bg-white dark:border-gray-800 dark:bg-white/[0.03] p-4"> --}}
<div x-data="{
    activeTab: '{{ old('active_tab', request('active_tab', $mapel->first()?->mapel?->id ?? '')) }}',
    editMode: false,
    periode: '{{ request('periode', $periode) }}',
    kelasId: '{{ request('kelas_id') }}',
    semester: '{{ request('semester') }}'
}" class="rounded-2xl bg-white dark:border-gray-800 dark:bg-white/[0.03] p-4">
        {{-- Tahun Ajaran Aktif --}}
        {{-- Filter Form --}}
        <form method="GET" class="mb-4 flex gap-4 items-end">
            <div class="flex-1">
                <x-form.select label="Tahun Semester"
                name="tahun_semester_id"
                    :options="$daftarTahunSemester->mapWithKeys(
                    fn($ts) => [
                        $ts->id => ($ts->tahunAjaran->tahun ?? '-') . ' - ' . ucfirst($ts->semester),
                    ],
                )"
                    :selected="request('tahun_semester_id', $tahunAktif->id)"
                    placeholder="Pilih Tahun Semester"
                    searchable
                    required
                    onchange="this.form.submit()" />
            </div>

            <div class="flex-1">
                <x-form.select label="Kelas" name="kelas_id"
                    :options="['' => '-- Pilih Kelas --'] +
                    $daftarKelas->mapWithKeys(fn($kls) => [$kls->id => $kls->nama])->toArray()"
                    :selected="request('kelas_id')"
                    placeholder="Pilih Kelas" searchable required onchange="this.form.submit()" />
            </div>

            <div class="flex-1">
                <x-form.select label="Periode" name="periode" :options="['tengah' => 'Tengah Semester', 'akhir' => 'Akhir Semester']" :selected="$periode"
                    placeholder="Pilih Periode" searchable required onchange="this.form.submit()" />
            </div>
        </form>


        {{-- Kondisi Data --}}
        @if (!$kelasDipilih)
            <div class="text-center text-gray-500 py-8">
                Silakan pilih kelas terlebih dahulu untuk menampilkan mapel yang tersedia.
            </div>
        {{-- @elseif ($mapel->isEmpty())
            <div class="text-center text-gray-500 py-8">
                Tidak ada mata pelajaran untuk kelas <span class="font-semibold">{{ $kelasDipilih->nama }}</span>.
                Silakan tambahkan mata pelajaran terlebih dahulu.
            </div> --}}
        @else
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">
                Mapel Kelas {{ $kelasDipilih->nama }}
            </h2>

            {{-- Form Simpan Nilai --}}
            <form action="{{ role_route('nilai-mapel.update-batch') }}" method="POST">
                @csrf
                <input type="hidden" name="active_tab" :value="activeTab">
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
                {{-- <div
                    class="flex space-x-2 border-b mb-4 overflow-x-auto whitespace-nowrap scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                    @foreach ($mapel as $gk)
                        @if ($gk->mapel)
                            <a href="?kelas_id={{ $kelasDipilih->id }}&active_tab={{ $gk->mapel->id }}&tahun_semester_id={{ $tahunAktif->id }}&periode={{ $periode }}"
                                @click.prevent="activeTab = '{{ $gk->mapel->id }}'"
                                :class="{ 'border-b-2 border-brand-500 font-semibold text-brand-600': activeTab === '{{ $gk->mapel->id }}' }"
                                class="px-4 py-2 text-sm hover:text-brand-600 transition">
                                {{ $gk->mapel->nama }}
                            </a>
                        @endif
                    @endforeach
                </div> --}}
                {{-- Tab Mapel --}}
@if($mapel->isNotEmpty())
    <div
        class="flex space-x-2 border-b mb-4 overflow-x-auto whitespace-nowrap scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
        @foreach ($mapel as $gk)
            @if ($gk->mapel)
                <a href="?kelas_id={{ $kelasDipilih->id }}&active_tab={{ $gk->mapel->id }}&tahun_semester_id={{ $tahunAktif->id }}&periode={{ $periode }}"
                    @click.prevent="activeTab = '{{ $gk->mapel->id }}'"
                    :class="{ 'border-b-2 border-brand-500 font-semibold text-brand-600': activeTab === '{{ $gk->mapel->id }}' }"
                    class="px-4 py-2 text-sm hover:text-brand-600 transition">
                    {{ $gk->mapel->nama }}
                </a>
            @endif
        @endforeach
    </div>
@else
    <div class="text-gray-500 italic mb-4">
        Tidak ada mata pelajaran untuk kelas
        <span class="font-semibold">{{ $kelasDipilih->nama }}</span>.
        Silakan tambahkan mata pelajaran terlebih dahulu.
    </div>
@endif


                {{-- Konten Tab --}}
                @foreach ($mapel as $gk)
                    @if ($gk->mapel)
                        @php
                            $mapelId = $gk->mapel->id;
                            $nilaiMapelTab = $nilaiMapel[$mapelId] ?? [];
                            $lmList = $lingkupMateriList[$mapelId] ?? collect();
                            $tpList = $tujuanPembelajaranList[$mapelId] ?? collect();
                        @endphp

                        <div x-show="activeTab === '{{ $mapelId }}'" x-cloak>
                            <div class="w-full overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                                <table class="min-w-[1200px] w-full text-sm text-center table-auto whitespace-nowrap">
                                    <thead class="text-gray-700 bg-gray-100 dark:bg-gray-800 dark:text-gray-200">
                                        <tr>
                                            <th rowspan="2" class="px-3 py-2">No Absen</th>
                                            <th rowspan="2" class="px-3 py-2">Nama Siswa</th>
                                            <th colspan="{{ $tpList->count(), 1 }}" class="bg-success-50">Formatif
                                            </th>
                                            <th rowspan="2" class="bg-success-50">NA</th>
                                            <th colspan="{{ $lmList->count(), 1 }}" class="bg-warning-50">Sumatif
                                            </th>
                                            <th rowspan="2" class="bg-warning-50">NA</th>
                                            <th colspan="2" class="bg-brand-50">Tengah Semester</th>
                                            <th rowspan="2" class="bg-brand-50">NA</th>
                                            @if ($periode == 'akhir')
                                                <th colspan="2" class="bg-error-50">Akhir Semester</th>
                                                <th rowspan="2" class="bg-error-50">NA</th>
                                            @endif
                                            <th rowspan="2" class="bg-blue-light-50">
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
                                                    {{-- <th class="px-2 py-1 bg-warning-50">
                                                        {{ is_object($bab) ? $bab->bab : $bab }}</th> --}}
                                                        <th class="px-2 py-1 bg-warning-50">
                                                            {{ is_object($bab) ? $bab->nama : $bab }}
                                                        </th>
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
                                                $nilai = $rekapNilai[$mapelId][$ksId] ?? [];
                                            @endphp
                                            <tr>
                                                <td class="px-3 py-2 font-semibold">{{ $item->no_absen }}</td>
                                                <td class="px-3 py-2 text-left">{{ $item->siswa->nama }}</td>

                                                {{-- Formatif --}}
                                                @if ($tpList->isNotEmpty())
                                                    @foreach ($tpList as $tp)
                                                        @php
                                                            $val =
                                                                old("nilai.$mapelId.$ksId.formatif_$tp->id") ??
                                                                ($nilaiMapelTab['formatif'][$ksId][$tp->id] ?? null);
                                                        @endphp
                                                        <td>
                                                            <input x-show="editMode" type="number"
                                                                name="nilai[{{ $mapelId }}][{{ $ksId }}][formatif_{{ $tp->id }}]"
                                                                value="{{ $val }}"
                                                                class="input-nilai w-16 text-sm border rounded" />
                                                            <span
                                                                x-show="!editMode">{{ $val === null || $val === '' ? '-' : $val }}</span>
                                                        </td>
                                                    @endforeach
                                                @else
                                                    <td>-</td>
                                                @endif
                                                {{-- NA Formatif --}}
                                                <td class="bg-success-50">{{ $nilai['na_formatif'] ?? '-' }}</td>

                                                {{-- Sumatif --}}
                                                @if ($lmList->isNotEmpty())
                                                    @foreach ($lmList as $bab)
                                                        @php
                                                            $babKey = is_object($bab) ? $bab->id : $bab;
                                                            $val =
                                                                old("nilai.$mapelId.$ksId.sumatif_$babKey") ??
                                                                ($nilaiMapelTab['sumatif'][$ksId][$babKey] ?? null);
                                                        @endphp
                                                        <td>
                                                            <input x-show="editMode" type="number"
                                                                name="nilai[{{ $mapelId }}][{{ $ksId }}][sumatif_{{ $babKey }}]"
                                                                value="{{ $val }}"
                                                                class="input-nilai w-16 text-sm border rounded" />
                                                            <span
                                                                x-show="!editMode">{{ $val === null || $val === '' ? '-' : $val }}</span>
                                                        </td>
                                                    @endforeach
                                                @else
                                                    <td>-</td>
                                                @endif

                                                {{-- NA Sumatif --}}
                                                <td class="bg-warning-50">{{ $nilai['na_sumatif'] ?? '-' }}</td>

                                                {{-- UTS --}}
                                                {{-- <td>
                                                    @php
                                                        $valUtsNonTes =
                                                            old("nilai.$mapelId.$ksId.uts_nontes") ??
                                                            ($nilaiMapelTab['uts'][$ksId]['non_tes'] ?? null);
                                                    @endphp
                                                    <input x-show="editMode && periode==='tengah'" type="number"
                                                        name="nilai[{{ $mapelId }}][{{ $ksId }}][uts_nontes]"
                                                        value="{{ $valUtsNonTes }}"
                                                        class="input-nilai w-16 text-sm border rounded" />
                                                    <span
                                                        x-show="!editMode || periode!=='tengah'">{{ $valUtsNonTes ?? '-' }}</span>
                                                </td>
                                                <td>
                                                    @php
                                                        $valUtsTes =
                                                            old("nilai.$mapelId.$ksId.uts_tes") ??
                                                            ($nilaiMapelTab['uts'][$ksId]['tes'] ?? null);
                                                    @endphp
                                                    <input x-show="editMode && periode==='tengah'" type="number"
                                                        name="nilai[{{ $mapelId }}][{{ $ksId }}][uts_tes]"
                                                        value="{{ $valUtsTes }}"
                                                        class="input-nilai w-16 text-sm border rounded" />
                                                    <span
                                                        x-show="!editMode || periode!=='tengah'">{{ $valUtsTes ?? '-' }}</span>
                                                </td> --}}
                                                <td>
                                                    @php
                                                        $valUtsNonTes =
                                                            old("nilai.$mapelId.$ksId.uts_nontes") ??
                                                            ($nilaiMapelTab['uts'][$ksId]['non_tes'] ?? null);
                                                    @endphp
                                                    @if ($periode == 'tengah')
                                                        <input x-show="editMode" type="number"
                                                            name="nilai[{{ $mapelId }}][{{ $ksId }}][uts_nontes]"
                                                            value="{{ $valUtsNonTes }}"
                                                            class="input-nilai w-16 text-sm border rounded" />
                                                        <span
                                                            x-show="!editMode">{{ $valUtsNonTes === null || $valUtsNonTes === '' ? '-' : $valUtsNonTes }}</span>
                                                    @else
                                                        {{-- periode akhir → tampilkan readonly --}}
                                                        <span>{{ $valUtsNonTes === null || $valUtsNonTes === '' ? '-' : $valUtsNonTes }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $valUtsTes =
                                                            old("nilai.$mapelId.$ksId.uts_tes") ??
                                                            ($nilaiMapelTab['uts'][$ksId]['tes'] ?? null);
                                                    @endphp
                                                    @if ($periode == 'tengah')
                                                        <input x-show="editMode" type="number"
                                                            name="nilai[{{ $mapelId }}][{{ $ksId }}][uts_tes]"
                                                            value="{{ $valUtsTes }}"
                                                            class="input-nilai w-16 text-sm border rounded" />
                                                        <span
                                                            x-show="!editMode">{{ $valUtsTes === null || $valUtsTes === '' ? '-' : $valUtsTes }}</span>
                                                    @else
                                                        {{-- periode akhir → tampilkan readonly --}}
                                                        <span>{{ $valUtsTes === null || $valUtsTes === '' ? '-' : $valUtsTes }}</span>
                                                    @endif
                                                </td>
                                                <td class="bg-brand-50">{{ $nilai['na_uts'] ?? '-' }}</td>

                                                {{-- UAS --}}
                                                @if ($periode == 'akhir')
                                                    <td>
                                                        @php
                                                            $valUasNonTes =
                                                                old("nilai.$mapelId.$ksId.uas_nontes") ??
                                                                ($nilaiMapelTab['uas'][$ksId]['non_tes'] ?? null);
                                                        @endphp
                                                        <input x-show="editMode" type="number"
                                                            name="nilai[{{ $mapelId }}][{{ $ksId }}][uas_nontes]"
                                                            value="{{ $valUasNonTes }}"
                                                            class="input-nilai w-16 text-sm border rounded" />
                                                        <span x-show="!editMode">{{ $valUasNonTes ?? '-' }}</span>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $valUasTes =
                                                                old("nilai.$mapelId.$ksId.uas_tes") ??
                                                                ($nilaiMapelTab['uas'][$ksId]['tes'] ?? null);
                                                        @endphp
                                                        <input x-show="editMode" type="number"
                                                            name="nilai[{{ $mapelId }}][{{ $ksId }}][uas_tes]"
                                                            value="{{ $valUasTes }}"
                                                            class="input-nilai w-16 text-sm border rounded" />
                                                        <span x-show="!editMode">{{ $valUasTes ?? '-' }}</span>
                                                    </td>
                                                    <td class="bg-error-50">{{ $nilai['na_uas'] ?? '-' }}</td>
                                                @endif

                                                {{-- Nilai Akhir --}}
                                                <td class="bg-blue-light-50 font-bold">
                                                    {{ $nilai['nilai_akhir'] ?? '-' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="100%" class="text-center py-4 text-gray-500">Tidak ada
                                                    siswa.</td>
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
