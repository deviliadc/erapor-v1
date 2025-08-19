<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    <div x-data="{
        editMode: false,
        activeKelas: '{{ $selectedKelasId ?? (count($kelasList) ? $kelasList[0]->id : '') }}'
    }" class="bg-white dark:bg-white/[0.03] p-4 rounded-2xl">

        <!-- Filter Tahun Semester & Periode -->
        <form method="GET" class="mb-4 flex flex-wrap gap-4">
            <div>
                <label for="tahun_semester_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Tahun Semester
                </label>
                <select name="tahun_semester_id" id="tahun_semester_id" onchange="this.form.submit()"
                    class="mt-1 rounded border px-3 py-2">
                    <option value="">Pilih Tahun Semester</option>
                    @foreach ($tahunSemesterList as $ts)
                        <option value="{{ $ts->id }}"
                            {{ $ts->id == ($selectedTahunSemester?->id ?? '') ? 'selected' : '' }}>
                            {{ $ts->tahun }} - {{ Str::ucfirst($ts->semester) }}
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

        <!-- Tab Kelas -->
        {{-- <div class="border-b border-gray-200 dark:border-gray-800">
            @foreach ($kelasList as $kls)
                <button type="button" @click="activeKelas = '{{ $kls->id }}'"
                    :class="activeKelas === '{{ $kls->id }}' ? 'bg-blue-600 text-white border-blue-600' :
                        'bg-gray-100 text-gray-700 border-gray-300'"
                    class="px-4 py-2 rounded-t-md border font-semibold focus:outline-none transition">
                    Kelas {{ $kls->nama }}
                </button>
            @endforeach
        </div> --}}
        <!-- Tab Kelas -->
        <div class="border-b border-gray-200 dark:border-gray-800 mb-4">
            <nav
                class="-mb-px flex space-x-2 overflow-x-auto [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-gray-200 dark:[&::-webkit-scrollbar-thumb]:bg-gray-600 dark:[&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar]:h-1.5">
                @foreach ($kelasList as $kls)
                    <button type="button"
                        x-bind:class="activeKelas === '{{ $kls->id }}'
                            ?
                            'inline-flex items-center border-b-2 px-2.5 py-2 text-sm font-medium transition-colors duration-200 ease-in-out text-brand-500 dark:text-brand-400 border-brand-500 dark:border-brand-400' :
                            'inline-flex items-center border-b-2 px-2.5 py-2 text-sm font-medium transition-colors duration-200 ease-in-out bg-transparent text-gray-500 border-transparent hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                        @click="activeKelas = '{{ $kls->id }}'">
                        Kelas {{ $kls->nama }}
                    </button>
                @endforeach
            </nav>
        </div>

        <!-- Tombol Edit -->
        <div class="mb-4">
            <button type="button" @click="editMode = !editMode"
                class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-blue-light-500 shadow-theme-xs hover:bg-blue-light-600">
                <span x-show="!editMode">Edit Absensi</span>
                <span x-show="editMode">Batal Edit</span>
            </button>
        </div>

        <!-- Form Absensi -->
        @if ($selectedTahunSemester)
            <form action="{{ role_route('rekap-absensi.update-batch') }}" method="POST">
                @csrf
                <input type="hidden" name="kelas_id" x-bind:value="activeKelas">
                <input type="hidden" name="tahun_semester_id" value="{{ $selectedTahunSemester->id }}">
                <input type="hidden" name="periode" value="{{ $periode }}">

                @foreach ($kelasList as $kls)
                    <div x-show="activeKelas === '{{ $kls->id }}'" x-cloak>
                        <div class="overflow-x-auto">
                            <table class="w-full table-auto text-sm border border-gray-300 dark:border-gray-600">
                                <thead class="bg-gray-100 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-3 py-2 text-left">No</th>
                                        <th class="px-3 py-2 text-left">Nama</th>
                                        <th class="px-3 py-2 text-center">Sakit</th>
                                        <th class="px-3 py-2 text-center">Izin</th>
                                        <th class="px-3 py-2 text-center">Alfa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($rekapListByKelas[$kls->id]) && count($rekapListByKelas[$kls->id]))
                                        @foreach ($rekapListByKelas[$kls->id] as $rekap)
                                            @php $absensi = $rekap->rekapAbsensi; @endphp
                                            <tr class="border-t border-gray-200 dark:border-gray-600">
                                                <td class="px-3 py-2"> {{ $rekap->no_absen ?? $loop->iteration }}</td>
                                                <td class="px-3 py-2">{{ $rekap->siswa->nama }}</td>
                                                <td class="px-3 py-2 text-center">
                                                    <template x-if="editMode">
                                                        <input type="number"
                                                            name="rekap[{{ $rekap->id }}][sakit]"
                                                            value="{{ $absensi->total_sakit ?? 0 }}"
                                                            class="w-16 border-gray-300 dark:bg-gray-800 dark:border-gray-600 rounded-md text-center border rounded" />
                                                    </template>
                                                    <template x-if="!editMode">
                                                        <span>{{ $absensi->total_sakit ?? 0 }}</span>
                                                    </template>
                                                </td>
                                                <td class="px-3 py-2 text-center">
                                                    <template x-if="editMode">
                                                        <input type="number"
                                                            name="rekap[{{ $rekap->id }}][izin]"
                                                            value="{{ $absensi->total_izin ?? 0 }}"
                                                            class="w-16 border-gray-300 dark:bg-gray-800 dark:border-gray-600 rounded-md text-center border rounded" />
                                                    </template>
                                                    <template x-if="!editMode">
                                                        <span>{{ $absensi->total_izin ?? 0 }}</span>
                                                    </template>
                                                </td>
                                                <td class="px-3 py-2 text-center">
                                                    <template x-if="editMode">
                                                        <input type="number"
                                                            name="rekap[{{ $rekap->id }}][alfa]"
                                                            value="{{ $absensi->total_alfa ?? 0 }}"
                                                            class="w-16 border-gray-300 dark:bg-gray-800 dark:border-gray-600 rounded-md text-center border rounded" />
                                                    </template>
                                                    <template x-if="!editMode">
                                                        <span>{{ $absensi->total_alfa ?? 0 }}</span>
                                                    </template>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center py-6 text-gray-500">
                                                Tidak ada data yang bisa ditampilkan.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach

                <div class="flex justify-end mt-6" x-show="editMode">
                    {{-- Tombol Submit --}}
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                        Simpan
                    </button>
                </div>
            </form>
        @endif
    </div>
</x-app-layout>
