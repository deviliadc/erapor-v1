<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    <div x-data="{ editMode: false, activeKelas: '{{ $kelasId }}' }" class="bg-white dark:bg-white/[0.03] p-4 rounded-2xl">
        <!-- Filter Tahun Semester & Proyek -->
        {{-- <form method="GET" class="mb-4 flex flex-wrap gap-4">
            <div>
                <label for="tahun_semester_id" class="block text-sm font-medium">Tahun Semester</label>
                <select name="tahun_semester_id" id="tahun_semester_id" onchange="this.form.submit()"
                    class="mt-1 rounded border px-3 py-2">
                    @foreach ($tahunSemesterList as $ts)
                        <option value="{{ $ts->id }}" {{ $ts->id == $tahunSemesterId ? 'selected' : '' }}>
                            {{ $ts->tahun }} - Semester {{ $ts->semester }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="proyek_id" class="block text-sm font-medium">Proyek</label>
                <select name="proyek_id" id="proyek_id" onchange="this.form.submit()"
                    class="mt-1 rounded border px-3 py-2">
                    <option value="">Pilih Proyek</option>
                    @foreach ($proyekList as $proyek)
                        <option value="{{ $proyek->id }}" {{ $proyek->id == $proyekId ? 'selected' : '' }}>
                            {{ $proyek->nama_proyek }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form> --}}
         <form method="GET" class="mb-4 flex flex-wrap gap-4">
            <x-form.select
        label="Tahun Semester"
        name="tahun_semester_id"
        :options="$tahunSemesterList->mapWithKeys(fn($ts) => [
            $ts->id => ($ts->tahunAjaran->tahun ?? '-') . ' - ' . ucfirst($ts->semester)
        ])"
        :selected="$tahunSemesterId"
        placeholder="Pilih Tahun Semester"
        searchable
        required
        onchange="this.form.submit()"
    />

            <x-form.select
                label="Proyek"
                name="proyek_id"
                                :options="$proyekList->mapWithKeys(fn($p) => [$p->id => $p->nama_proyek])"
                :selected="$proyekId"
                placeholder="Pilih Proyek"
                searchable
                required
                onchange="this.form.submit()"
            />

            {{-- <x-form.select
                label="Kelas"
                name="kelas_id"
                :options="$kelasList->mapWithKeys(fn($kls) => [$kls->id => $kls->nama])"
                :selected="$kelasId"
                placeholder="Pilih Kelas"
                searchable
                required
                onchange="this.form.submit()"
            /> --}}
        </form>

        <!-- Tab Kelas -->
        <div class="mb-4">
            <nav class="flex space-x-2 border-b mb-4 overflow-x-auto whitespace-nowrap">
                @foreach ($kelasList as $kls)
                    <button type="button" @click="activeKelas = '{{ $kls->id }}'"
                        :class="activeKelas === '{{ $kls->id }}'
                            ? 'border-b-2 border-brand-500 font-semibold text-brand-600'
                            : 'text-gray-500 border-transparent hover:text-gray-700'"
                        class="px-4 py-2 text-sm transition">
                        Kelas {{ $kls->nama }}
                    </button>
                @endforeach
            </nav>
        </div>

        <!-- Tombol Edit -->
        <div class="flex justify-end gap-2 mb-4">
            <button type="button" @click="editMode = !editMode"
                class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-blue-light-500 shadow hover:bg-blue-light-600">
                <span x-show="!editMode">Edit Nilai</span>
                <span x-show="editMode">Batal Edit</span>
            </button>
        </div>

        @if ($dimensiList->isEmpty())
    <div class="text-center text-gray-500 py-10">
        Detail proyek P5 belum ditambahkan.
    </div>
@else
        <!-- Tabel per Kelas -->
        @foreach ($kelasList as $kls)
            <div x-show="activeKelas === '{{ $kls->id }}'" x-cloak>
                <form action="{{ role_route('nilai-p5.update-batch') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tahun_semester_id" value="{{ $tahunSemesterId }}">
                    <input type="hidden" name="proyek_id" value="{{ $proyekId }}">
                    <input type="hidden" name="kelas_id" value="{{ $kls->id }}">
                    <input type="hidden" name="periode" value="{{ $periode }}">

                    <div class="w-full overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                                <table class="min-w-[1200px] w-full text-sm text-center table-auto whitespace-nowrap">
                            <thead class="text-gray-700 bg-gray-100 dark:bg-gray-800 dark:text-gray-200">
                                <tr>
                                    <th class="px-3 py-2 text-left align-middle bg-gray dark:bg-gray-900" rowspan="2">No</th>
                                    <th class="px-3 py-2 text-left align-middle bg-gray dark:bg-gray-900" rowspan="2">Nama</th>
                                    <th class="px-3 py-2 text-left align-middle bg-gray dark:bg-gray-900" rowspan="2">Catatan</th>
                                    @foreach ($dimensiList as $dimensi)
                                        <th class="px-3 py-2 text-center bg-gray dark:bg-gray-900" colspan="{{ max($subelemenByDimensi[$dimensi->id]->count(), 1) }}">
                                            {{ $dimensi->nama_dimensi }}
                                        </th>
                                    @endforeach
                                </tr>
                                <tr>
                                    @foreach ($dimensiList as $dimensi)
                                        @if ($subelemenByDimensi[$dimensi->id]->isNotEmpty())
                                            @foreach ($subelemenByDimensi[$dimensi->id] as $subelemen)
                                                @php
                                                    $faseIdKelas = $faseIdByKelas[$kls->id] ?? $faseId;
                                                    $capaian = $subelemen->capaianFase->firstWhere('fase_id', $faseIdKelas)?->capaian ?? '-';
                                                @endphp
                                                <th class="px-3 py-2 text-center text-xs font-normal italic max-w-xs truncate">
                                                    {!! nl2br(e(Str::limit($capaian, 100))) !!}
                                                </th>
                                            @endforeach
                                        @else
                                            <th class="px-3 py-2 text-center">-</th>
                                        @endif
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($siswaByKelas[$kls->id] ?? [] as $i => $ks)
                                    <tr>
                                        <td class="px-3 py-2">{{ $i + 1 }}</td>
                                        <td class="px-3 py-2">{{ $ks['nama'] }}</td>
                                        <td class="px-3 py-2">
                                            <div x-show="editMode" class="block">
                                                <input type="text" name="nilai[{{ $ks['id'] }}][catatan]"
                                                    value="{{ $nilaiMap[$ks['id']]['catatan'] ?? '' }}"
                                                    class="border rounded w-full px-2 py-1 text-xs"
                                                    placeholder="Catatan akhir rapor">
                                            </div>
                                            <div x-show="!editMode" class="block">
                                                <span>{{ $nilaiMap[$ks['id']]['catatan'] ?? '-' }}</span>
                                            </div>
                                        </td>
                                        {{-- Hidden Input --}}
                                        <input type="hidden" name="nilai[{{ $ks['id'] }}][kelas_siswa_id]" value="{{ $ks['id'] }}">
                                        @foreach ($dimensiList as $dimensi)
                                            @if ($subelemenByDimensi[$dimensi->id]->isNotEmpty())
                                                @foreach ($subelemenByDimensi[$dimensi->id] as $subelemen)
                                                    @php
                                                        $nilai = $nilaiMap[$ks['id']][$subelemen->id]['predikat'] ?? '';
                                                    @endphp
                                                    <td class="px-3 py-2 text-center">
                                                        <div x-show="editMode" class="block">
                                                            <select name="nilai[{{ $ks['id'] }}][{{ $subelemen->id }}][predikat]" class="border rounded w-32 text-center text-xs">
                                                                <option value="" {{ $nilai === '' ? 'selected' : '' }}>-</option>
                                                                <option value="Sangat Baik" {{ $nilai === 'Sangat Baik' ? 'selected' : '' }}>Sangat Baik</option>
                                                                <option value="Baik" {{ $nilai === 'Baik' ? 'selected' : '' }}>Baik</option>
                                                                <option value="Cukup" {{ $nilai === 'Cukup' ? 'selected' : '' }}>Cukup</option>
                                                                <option value="Perlu Bimbingan" {{ $nilai === 'Perlu Bimbingan' ? 'selected' : '' }}>Perlu Bimbingan</option>
                                                            </select>
                                                        </div>
                                                        <div x-show="!editMode" class="block">
                                                            <span>{{ $nilai ?: '-' }}</span>
                                                        </div>
                                                    </td>
                                                @endforeach
                                            @else
                                                <td class="px-3 py-2 text-center">-</td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="999" class="text-center py-6 text-gray-500">
                                            Tidak ada data siswa.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-end mt-6" x-show="editMode">
                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow hover:bg-brand-600">
                            Simpan Nilai
                        </button>
                    </div>
                </form>
            </div>
        @endforeach
        @endif
    </div>
</x-app-layout>
