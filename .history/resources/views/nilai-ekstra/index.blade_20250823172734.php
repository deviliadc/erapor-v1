{{-- filepath: d:\DEVI\DRAFT\erapor-v1\resources\views\nilai-ekstra\index.blade.php --}}
<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    <div x-data="{
        editMode: false,
        activeEkstra: '{{ $ekstraId }}',
    }" class="bg-white dark:bg-white/[0.03] p-4 rounded-2xl">

        <!-- Filter Tahun Semester, Kelas, Periode -->
    <form method="GET" class="mb-4 flex flex-wrap gap-4">
    {{-- Tahun Semester --}}
    <div class="flex-1 min-w-0">
        <x-form.select
            label="Tahun Semester"
            name="tahun_semester_id"
            :options="$daftarTahunSemester->mapWithKeys(fn($ts) => [
                $ts->id => ($ts->tahunAjaran->tahun ?? '-') . ' - ' . ucfirst($ts->semester)
            ])"
            :selected="$tahunSemesterId"
            placeholder="Pilih Tahun Semester"
            searchable
            required
            onchange="this.form.submit()"
        />
    </div>

    {{-- Kelas --}}
    <div class="flex-1 min-w-0">
        <x-form.select
            label="Kelas"
            name="kelas_id"
            :options="['' => '-- Pilih Kelas --'] +
                $daftarKelas->mapWithKeys(fn($kls) => [$kls->id => $kls->nama])->toArray()"
            {{-- :options="$daftarKelas->mapWithKeys(fn($kls) => [$kls->id => $kls->nama])" --}}
            :selected="$kelasId"
            placeholder="Pilih Kelas"
            searchable
            required
            onchange="this.form.submit()"
        />
    </div>
</form>


        <!-- Tab Ekstra Tanpa Reload -->
        <div class="mb-4">
            <nav class="flex space-x-2 border-b mb-4 overflow-x-auto whitespace-nowrap">
                @foreach ($daftarEkstra as $ekstra)
                    <button type="button" @click="activeEkstra = '{{ $ekstra->id }}'"
                        :class="activeEkstra === '{{ $ekstra->id }}' ?
                            'border-b-2 border-brand-500 font-semibold text-brand-600' :
                            'text-gray-500 border-transparent hover:text-gray-700'"
                        class="px-4 py-2 text-sm transition">
                        {{ $ekstra->nama }}
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

        @foreach ($daftarEkstra as $ekstra)
            <div x-show="activeEkstra === '{{ $ekstra->id }}'" x-cloak>
                <form action="{{ role_route('nilai-ekstra.update-batch') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tahun_semester_id" value="{{ $selectedTahunSemester->id }}">
                    {{-- <input type="hidden" name="periode" value="{{ $periode }}"> --}}
                    <input type="hidden" name="periode" value="akhir">
                    <input type="hidden" name="ekstra_id" value="{{ $ekstra->id }}">
                    <input type="hidden" name="kelas_id" value="{{ $kelasId }}">

                    <div class="w-full overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                                <table class="min-w-[1200px] w-full text-sm text-center table-auto whitespace-nowrap">
                            <thead class="text-gray-700 bg-gray-100 dark:bg-gray-800 dark:text-gray-200">
                                <tr>
                                    <th class="px-3 py-2 bg-gray dark:bg-gray-900 text-left align-middle" rowspan="2">No</th>
                                    <th class="px-3 py-2 bg-gray dark:bg-gray-900 text-left align-middle" rowspan="2">Nama</th>
                                    <th class="px-3 py-2 bg-gray dark:bg-gray-900 text-center"
                                        colspan="{{ $daftarParameter[$ekstra->id]->count() }}">
                                        Parameter Ekstra</th>
                                    <th class="px-3 py-2 bg-gray dark:bg-gray-900 text-center align-middle" rowspan="2">Rata-rata</th>
                                    <th class="px-3 py-2 bg-gray dark:bg-gray-900 text-center align-middle" rowspan="2">Deskripsi</th>
                                </tr>
                                <tr>
                                    @foreach ($daftarParameter[$ekstra->id] as $param)
                                        <th class="px-3 py-2 bg-gray dark:bg-gray-900 text-center">{{ $param->parameter }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($siswaKelas as $ks)
                                    @php
                                        $nilaiDetail = $nilaiMap[$ekstra->id][$ks->id]['predikat_param'] ?? [];
                                        $avg = collect($nilaiDetail)->filter(fn($v) => is_numeric($v))->avg();
                                        $avg = $avg !== null ? ceil($avg) : null;
                                    @endphp
                                    <tr>
                                        <td class="px-3 py-2">{{ $loop->iteration }}</td>
                                        <td class="px-3 py-2">{{ $ks->siswa->nama ?? '-' }}</td>

                                        {{-- Tampilkan nilai parameter --}}
                                        @foreach ($daftarParameter[$ekstra->id] as $param)
                                            @php
                                                $nilaiPredikat = $nilaiDetail[$param->id] ?? null;
                                            @endphp
                                            <td class="px-3 py-2 text-center">
                                                <template x-if="editMode">
                                                    <select
                                                        name="nilai[{{ $ks->id }}][predikat][{{ $param->id }}]"
                                                        class="border rounded w-14 text-center">
                                                        <option value=""
                                                            {{ $nilaiPredikat === null || $nilaiPredikat === '' ? 'selected' : '' }}>
                                                            -</option>
                                                        @for ($i = 0; $i <= 4; $i++)
                                                            <option value="{{ $i }}"
                                                                {{ (string) $nilaiPredikat === (string) $i ? 'selected' : '' }}>
                                                                {{ $i }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </template>
                                                <template x-if="!editMode">
                                                    <span>{{ $nilaiPredikat ?? '-' }}</span>
                                                </template>
                                            </td>
                                        @endforeach

                                        {{-- Nilai Rata-rata --}}
                                        <td class="px-3 py-2 text-center font-bold">
                                            {{ $avg ?? '-' }}
                                        </td>

                                        {{-- Deskripsi --}}
                                        <td class="px-3 py-2 text-left" style="max-width: 250px;">
                                            {{ $nilaiMap[$ekstra->id][$ks->id]['deskripsi'] ?? '-' }}
                                        </td>

                                        {{-- Hidden Inputs --}}
                                        <input type="hidden" name="nilai[{{ $ks->id }}][kelas_siswa_id]"
                                            value="{{ $ks->id }}">
                                        <input type="hidden" name="nilai[{{ $ks->id }}][ekstra_id]"
                                            value="{{ $ekstra->id }}">
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ 3 + $daftarParameter[$ekstra->id]->count() }}"
                                            class="text-center py-6 text-gray-500">
                                            Tidak ada data nilai ekstra.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-end mt-6" x-show="editMode">
                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow hover:bg-brand-600">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        @endforeach
    </div>
</x-app-layout>
