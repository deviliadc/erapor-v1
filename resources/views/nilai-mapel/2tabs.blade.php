@php
    $tpList = $tujuanPembelajaranList[$mapel->id] ?? collect();
    $lmList = $lingkupMateriList[$mapel->id] ?? collect();
@endphp

<div class="w-full overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
    <table class="min-w-[1200px] w-full text-sm text-center table-auto whitespace-nowrap">
        <thead class="text-gray-700 bg-gray-100 dark:bg-gray-800 dark:text-gray-200">
            <tr>
                <th rowspan="2" class="px-3 py-2 bg-gray dark:bg-gray-900">No Absen</th>
                <th rowspan="2" class="px-3 py-2 bg-gray dark:bg-gray-900">Nama Siswa</th>
                <th colspan="{{ max($tpList->count(), 1) }}" class="px-3 py-2 bg-success-50 text-success-700 dark:bg-success-900 dark:text-success-200">Formatif</th>
                <th rowspan="2" class="px-3 py-2 bg-success-100 text-success-800 dark:bg-success-800 dark:text-success-100">NA</th>
                <th colspan="{{ max($lmList->count(), 1) }}" class="px-3 py-2 bg-warning-50 text-warning-700 dark:bg-warning-900 dark:text-warning-200">Sumatif</th>
                <th rowspan="2" class="px-3 py-2 bg-warning-100 text-warning-800 dark:bg-warning-800 dark:text-warning-100">NA</th>
                <th colspan="2" class="px-3 py-2 bg-brand-50 text-brand-700 dark:bg-brand-900 dark:text-brand-200">Tengah Semester</th>
                <th rowspan="2" class="px-3 py-2 bg-brand-100 text-brand-800 dark:bg-brand-800 dark:text-brand-100">NA</th>
                <th colspan="2" class="px-3 py-2 bg-error-50 text-error-700 dark:bg-error-900 dark:text-error-200">Akhir Semester</th>
                <th rowspan="2" class="px-3 py-2 bg-error-100 text-error-800 dark:bg-error-800 dark:text-error-100">NA</th>
            </tr>
            <tr>
                @forelse ($tpList as $tp)
                    <th class="px-2 py-1 bg-success-100 text-success-800 dark:bg-success-800 dark:text-success-100">{{ $tp->subbab ?? '-' }}</th>
                @empty
                    <th class="px-2 py-1 bg-success-100 text-success-800 dark:bg-success-800 dark:text-success-100">-</th>
                @endforelse
                @forelse ($lmList as $bab)
                    <th class="px-2 py-1 bg-warning-100 text-warning-800 dark:bg-warning-800 dark:text-warning-100">{{ is_object($bab) ? $bab->nama : $bab }}</th>
                @empty
                    <th class="px-2 py-1 bg-warning-100 text-warning-800 dark:bg-warning-800 dark:text-warning-100">-</th>
                @endforelse
                <th class="px-2 py-1 bg-brand-100 text-brand-800 dark:bg-brand-800 dark:text-brand-100">Non Tes</th>
                <th class="px-2 py-1 bg-brand-100 text-brand-800 dark:bg-brand-800 dark:text-brand-100">Tes</th>
                <th class="px-2 py-1 bg-error-100 text-error-800 dark:bg-error-800 dark:text-error-100">Non Tes</th>
                <th class="px-2 py-1 bg-error-100 text-error-800 dark:bg-error-800 dark:text-error-100">Tes</th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-900">
            @forelse ($siswaList as $item)
                @php
                    $siswa = $item->siswa;
                    $noAbsen = $item->no_absen;
                    $nilaiFormatif = collect();
                    foreach ($tpList as $tp) {
                        $nilai = old("formatif.$siswa->id.$tp->id") ?? ($nilaiMapel['formatif'][$siswa->id][$tp->id] ?? null);
                        $nilaiFormatif->push(is_numeric($nilai) ? (float) $nilai : null);
                    }
                    $nilaiSumatif = collect();
                    foreach ($lmList as $lm) {
                        $babKey = is_object($lm) ? $lm->id : $lm;
                        $nilai = old("sumatif.$siswa->id.$babKey") ?? ($nilaiMapel['sumatif'][$siswa->id][$babKey] ?? null);
                        $nilaiSumatif->push(is_numeric($nilai) ? (float) $nilai : null);
                    }
                    $tesUTS = old("uts.$siswa->id.tes") ?? ($nilaiMapel['uts'][$siswa->id]['tes'] ?? null);
                    $nonTesUTS = old("uts.$siswa->id.non_tes") ?? ($nilaiMapel['uts'][$siswa->id]['non_tes'] ?? null);
                    $tesUAS = old("uas.$siswa->id.tes") ?? ($nilaiMapel['uas'][$siswa->id]['tes'] ?? null);
                    $nonTesUAS = old("uas.$siswa->id.non_tes") ?? ($nilaiMapel['uas'][$siswa->id]['non_tes'] ?? null);
                    $naFormatif = $nilaiFormatif->filter()->avg();
                    $naSumatif = $nilaiSumatif->filter()->avg();
                    $naUTS = collect([$naFormatif, $naSumatif, $tesUTS, $nonTesUTS])->filter()->avg();
                    $naUAS = collect([$naFormatif, $naSumatif, $tesUTS, $nonTesUTS, $tesUAS, $nonTesUAS])->filter()->avg();
                @endphp
                <tr>
                    <td class="text-left px-3 py-2 font-semibold">{{ $noAbsen }}</td>
                    <td class="text-left px-3 py-2">{{ $siswa->nama }}</td>
                    {{-- Formatif --}}
                    @if ($tpList->isNotEmpty())
                        @foreach ($tpList as $tp)
                            @php
                                $val = old("formatif.$siswa->id.$tp->id") ?? ($nilaiMapel['formatif'][$siswa->id][$tp->id] ?? null);
                            @endphp
                            <td>
                                <input
                                    x-show="$root.editMode"
                                    type="number"
                                    name="formatif[{{ $siswa->id }}][{{ $tp->id }}]"
                                    value="{{ $val }}"
                                    class="input-nilai w-16 text-sm border rounded"
                                />
                                <span x-show="!$root.editMode">
                                    {{ is_null($val) || $val === '' ? '-' : $val }}
                                </span>
                            </td>
                        @endforeach
                    @else
                        <td class="bg-success-50">-</td>
                    @endif
                    {{-- NA Formatif --}}
                    <td class="bg-success-50">{{ $naFormatif !== null ? round($naFormatif, 2) : '-' }}</td>
                    {{-- Sumatif --}}
                    @if ($lmList->isNotEmpty())
                        @foreach ($lmList as $bab)
                            @php
                                $babKey = is_object($bab) ? $bab->id : $bab;
                                $val = old("sumatif.$siswa->id.$babKey") ?? ($nilaiMapel['sumatif'][$siswa->id][$babKey] ?? null);
                            @endphp
                            <td>
                                <input
                                    x-show="$root.editMode"
                                    type="number"
                                    name="sumatif[{{ $siswa->id }}][{{ $babKey }}]"
                                    value="{{ $val }}"
                                    class="input-nilai w-16 text-sm border rounded"
                                />
                                <span x-show="!$root.editMode">
                                    {{ is_null($val) || $val === '' ? '-' : $val }}
                                </span>
                            </td>
                        @endforeach
                    @else
                        <td class="bg-warning-50">-</td>
                    @endif
                    {{-- NA Sumatif --}}
                    <td class="bg-warning-50">{{ $naSumatif !== null ? round($naSumatif, 2) : '-' }}</td>
                    {{-- UTS --}}
                    <td>
                        <input
                            x-show="$root.editMode"
                            type="number"
                            name="uts[{{ $siswa->id }}][non_tes]"
                            value="{{ $nonTesUTS }}"
                            class="input-nilai w-16 text-sm border rounded"
                        />
                        <span x-show="!$root.editMode">
                            {{ is_null($nonTesUTS) || $nonTesUTS === '' ? '-' : $nonTesUTS }}
                        </span>
                    </td>
                    <td>
                        <input
                            x-show="$root.editMode"
                            type="number"
                            name="uts[{{ $siswa->id }}][tes]"
                            value="{{ $tesUTS }}"
                            class="input-nilai w-16 text-sm border rounded"
                        />
                        <span x-show="!$root.editMode">
                            {{ is_null($tesUTS) || $tesUTS === '' ? '-' : $tesUTS }}
                        </span>
                    </td>
                    <td class="bg-brand-100">{{ $naUTS !== null ? round($naUTS, 2) : '-' }}</td>
                    {{-- UAS --}}
                    <td>
                        <input
                            x-show="$root.editMode"
                            type="number"
                            name="uas[{{ $siswa->id }}][non_tes]"
                            value="{{ $nonTesUAS }}"
                            class="input-nilai w-16 text-sm border rounded"
                        />
                        <span x-show="!$root.editMode">
                            {{ is_null($nonTesUAS) || $nonTesUAS === '' ? '-' : $nonTesUAS }}
                        </span>
                    </td>
                    <td>
                        <input
                            x-show="$root.editMode"
                            type="number"
                            name="uas[{{ $siswa->id }}][tes]"
                            value="{{ $tesUAS }}"
                            class="input-nilai w-16 text-sm border rounded"
                        />
                        <span x-show="!$root.editMode">
                            {{ is_null($tesUAS) || $tesUAS === '' ? '-' : $tesUAS }}
                        </span>
                    </td>
                    <td class="bg-error-100">{{ $naUAS !== null ? round($naUAS, 2) : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center py-4 text-gray-500">Tidak ada siswa.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
