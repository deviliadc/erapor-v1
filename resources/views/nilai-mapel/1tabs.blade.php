@php
    $babList = $babList[$mapel->id] ?? collect();
    $subbabList = $subbabList[$mapel->id] ?? collect();
@endphp

<div class="w-full overflow-auto rounded-lg border border-gray-200 dark:border-gray-700">
    <table class="min-w-max w-full text-sm text-center table-auto">
        <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
            {{-- Baris 1 --}}
            <tr>
                <th rowspan="2" class="px-3 py-2">No Absen</th>
                <th rowspan="2" class="px-3 py-2">Nama Siswa</th>
                <th colspan="{{ max($subbabList->count(), 1) }}" class="px-3 py-2 bg-green-200">Formatif</th>
                <th rowspan="2" class="px-3 py-2 bg-green-300">NA</th>
                <th colspan="{{ max($babList->count(), 1) }}" class="px-3 py-2 bg-pink-200">Sumatif</th>
                <th rowspan="2" class="px-3 py-2 bg-pink-300">NA</th>
                <th colspan="2" class="px-3 py-2 bg-orange-200">Tengah Semester</th>
                <th rowspan="2" class="px-3 py-2 bg-orange-300">NA</th>
                <th colspan="2" class="px-3 py-2 bg-blue-200">Akhir Semester</th>
                <th rowspan="2" class="px-3 py-2 bg-blue-300">NA</th>
            </tr>

            {{-- Baris 2 --}}
            <tr>
                @forelse ($subbabList as $subbab)
                    <th class="bg-green-100 px-2 py-1">{{ $subbab->judul }}</th>
                @empty
                    <th class="bg-green-100 px-2 py-1">-</th>
                @endforelse

                @forelse ($babList as $bab)
                    <th class="bg-pink-100 px-2 py-1">{{ $bab->judul }}</th>
                @empty
                    <th class="bg-pink-100 px-2 py-1">-</th>
                @endforelse

                <th class="bg-orange-100 px-2 py-1">Non Tes</th>
                <th class="bg-orange-100 px-2 py-1">Tes</th>
                <th class="bg-blue-100 px-2 py-1">Non Tes</th>
                <th class="bg-blue-100 px-2 py-1">Tes</th>
            </tr>
        </thead>

        <tbody class="bg-white dark:bg-gray-900">
            @forelse ($siswaList as $item)
                @php
                    $siswa = $item->siswa;
                    $noAbsen = $item->no_absen;
                    $naFormatif = 0;
                    $naSumatif = 0;
                    $countFormatif = 0;
                    $countSumatif = 0;
                @endphp
                <tr>
                    <td class="text-left px-3 py-2 font-semibold whitespace-nowrap">{{ $noAbsen }}</td>
                    <td class="text-left px-3 py-2 whitespace-nowrap">{{ $siswa->nama }}</td>

                    {{-- Formatif --}}
                    @foreach ($subbabList as $subbab)
                        @php
                            $nilai = $nilaiMapel
                                ->where('siswa_id', $siswa->id)
                                ->where('tujuan_pembelajaran_id', $subbab->id)
                                ->first()?->nilai;

                            if (is_numeric($nilai)) {
                                $naFormatif += $nilai;
                                $countFormatif++;
                            }
                        @endphp
                        <td class="bg-green-50 px-2 py-1">
                            <div x-show="editMode">
                                <input type="number" step="0.01" min="0" max="100"
                                    name="nilai[{{ $siswa->id }}][formatif][{{ $subbab->id }}]"
                                    class="w-16 text-center border rounded"
                                    value="{{ old("nilai.$siswa->id.formatif.$subbab->id", $nilai ?? '') }}">
                            </div>
                            <div x-show="!editMode">
                                <span>{{ $nilai ?? '-' }}</span>
                            </div>
                        </td>
                    @endforeach

                    {{-- NA Formatif --}}
                    <td class="bg-green-100 font-semibold">
                        {{ $countFormatif ? number_format($naFormatif / $countFormatif, 2) : '-' }}
                    </td>

                    {{-- Sumatif --}}
                    @foreach ($babList as $bab)
                        @php
                            $nilai = $nilaiMapel
                                ->where('siswa_id', $siswa->id)
                                ->where('mapel_bab_id', $bab->id)
                                ->first()?->nilai;

                            if (is_numeric($nilai)) {
                                $naSumatif += $nilai;
                                $countSumatif++;
                            }
                        @endphp
                        <td class="bg-pink-50 px-2 py-1">
                            <div x-show="editMode">
                                <input type="number" step="0.01" min="0" max="100"
                                    name="nilai[{{ $siswa->id }}][sumatif][{{ $bab->id }}]"
                                    class="w-16 text-center border rounded"
                                    value="{{ old("nilai.$siswa->id.sumatif.$bab->id", $nilai ?? '') }}">
                            </div>
                            <div x-show="!editMode">
                                <span>{{ $nilai ?? '-' }}</span>
                            </div>
                        </td>
                    @endforeach

                    {{-- NA Sumatif --}}
                    <td class="bg-pink-100 font-semibold">
                        {{ $countSumatif ? number_format($naSumatif / $countSumatif, 2) : '-' }}
                    </td>

                    {{-- UTS --}}
                    @php
                        $utsNonTes = $nilaiMapel->firstWhere(fn($n) => $n->siswa_id == $siswa->id && $n->jenis === 'uts-nontes')?->nilai;
                        $utsTes = $nilaiMapel->firstWhere(fn($n) => $n->siswa_id == $siswa->id && $n->jenis === 'uts-tes')?->nilai;
                        $naUts = collect([$utsNonTes, $utsTes])->filter()->avg();
                    @endphp

                    @foreach (['nontes' => $utsNonTes, 'tes' => $utsTes] as $key => $nilai)
                        <td class="bg-orange-50 px-2 py-1">
                            <div x-show="editMode">
                                <input type="number" step="0.01" min="0" max="100"
                                    name="nilai[{{ $siswa->id }}][uts][{{ $key }}]"
                                    class="w-16 text-center border rounded"
                                    value="{{ old("nilai.$siswa->id.uts.$key", $nilai ?? '') }}">
                            </div>
                            <div x-show="!editMode">
                                <span>{{ $nilai ?? '-' }}</span>
                            </div>
                        </td>
                    @endforeach
                    <td class="bg-orange-100 font-semibold">{{ $naUts !== null ? number_format($naUts, 2) : '-' }}</td>

                    {{-- UAS --}}
                    @php
                        $uasNonTes = $nilaiMapel->firstWhere(fn($n) => $n->siswa_id == $siswa->id && $n->jenis === 'uas-nontes')?->nilai;
                        $uasTes = $nilaiMapel->firstWhere(fn($n) => $n->siswa_id == $siswa->id && $n->jenis === 'uas-tes')?->nilai;
                        $naUas = collect([$uasNonTes, $uasTes])->filter()->avg();
                    @endphp

                    @foreach (['nontes' => $uasNonTes, 'tes' => $uasTes] as $key => $nilai)
                        <td class="bg-blue-50 px-2 py-1">
                            <div x-show="editMode">
                                <input type="number" step="0.01" min="0" max="100"
                                    name="nilai[{{ $siswa->id }}][uas][{{ $key }}]"
                                    class="w-16 text-center border rounded"
                                    value="{{ old("nilai.$siswa->id.uas.$key", $nilai ?? '') }}">
                            </div>
                            <div x-show="!editMode">
                                <span>{{ $nilai ?? '-' }}</span>
                            </div>
                        </td>
                    @endforeach
                    <td class="bg-blue-100 font-semibold">{{ $naUas !== null ? number_format($naUas, 2) : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="100" class="text-center text-gray-400 py-4">Data siswa tidak tersedia.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
