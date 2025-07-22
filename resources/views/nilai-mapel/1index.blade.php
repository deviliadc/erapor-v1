@php
    $activeTab = request('mapel', '');
@endphp

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
                    <option value="tengah" {{ request('periode', 'tengah') == 'tengah' ? 'selected' : '' }}>Tengah
                        Semester</option>
                    <option value="akhir" {{ request('periode') == 'akhir' ? 'selected' : '' }}>Akhir Semester</option>
                </select>
            </div>
        </form>

        @if ($kelasDipilih)
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">
                Mapel Kelas {{ $kelasDipilih->nama }}
            </h2>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end gap-2 mb-4">
                <button type="button" @click.prevent="window.dispatchEvent(new CustomEvent('toggle-edit'))"
                    class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                    Edit Nilai
                </button>
                <button type="button" @click.prevent="window.dispatchEvent(new CustomEvent('open-verifikasi'))"
                    class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-blue-light-500 shadow-theme-xs hover:bg-blue-light-600">
                    Verifikasi Nilai
                </button>
            </div>

            <div x-data="{ activeTab: '{{ $activeTab ?: $mapel->first()?->mapel->id ?? '' }}', editMode: false }" x-ref="rootData" @toggle-edit.window="editMode = !editMode"
                x-id="['nilai-tab']">
                <form action="{{ route('nilai-mapel.bulk-store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="kelas_id" value="{{ $kelasDipilih->id }}">
                    <input type="hidden" name="tahun_semester_id" value="{{ $tahunAktif->id }}">
                    {{-- <input type="hidden" name="mapel_id" value="{{ $mapel->id }}"> --}}
                    <input type="hidden" name="mapel_id" :value="activeTab">

                    @if ($mapel->isEmpty())
                        <div class="py-10 text-center text-gray-400">
                            <span>Tidak ada mapel untuk kelas dan tahun pelajaran ini.</span>
                        </div>
                    @else
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
                                <div x-show="activeTab === '{{ $gk->mapel->id }}'" x-cloak>
                                    @include('nilai-mapel.tabs', [
                                        'mapel' => $gk->mapel,
                                        'kelas' => $kelasDipilih,
                                        'guruKelasId' => $gk->id,
                                        'nilaiMapel' => $nilaiMapel[$gk->mapel->id] ?? collect(),
                                        'siswaList' => $siswaList,
                                        'tujuanPembelajaranList' => $tujuanPembelajaranList,
                                        'lingkupMateriList' => $lingkupMateriList,
                                        // 'editMode' => $editMode ?? false,
                                    ])
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
                    @endif
                </form>
            </div>
        @else
            <p class="text-gray-500 text-sm mt-2">Silakan pilih kelas terlebih dahulu untuk menampilkan mapel yang
                tersedia.</p>
        @endif
    </div>
</x-app-layout>
