{{-- filepath: d:\DEVI\DRAFT\erapor-v1\resources\views\leger-rapor\index.blade.php --}}
<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    {{-- Filter Tahun Semester & Kelas --}}
    <div class="mb-4 px-4">
        <form action="{{ role_route('leger-rapor.index') }}" method="GET" class="flex flex-wrap gap-4">
            {{-- Tahun Semester --}}
            <div class="flex-1 min-w-0">
                <x-form.select name="tahun_semester_id" label="Tahun Semester" :options="$allTahunSemester" :selected="$tahunAktif->id ?? ''"
                    required />
            </div>

            {{-- Kelas --}}
            <div class="flex-1 min-w-0">
                <x-form.select name="kelas_id" label="Kelas" :options="$allKelas" :selected="$kelasDipilih->id ?? ''" />
            </div>

            {{-- Submit --}}
            <div class="flex-1 min-w-0 flex items-end">
                <button type="submit"
                    class="w-full h-14 inline-flex justify-center items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                    Filter
                </button>
            </div>
        </form>
    </div>



    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
        {{-- Tombol export --}}
        <div class="flex item-end justify-end mb-4 px-4">
            <a href="{{ role_route('leger-rapor.export', ['kelas_id' => $kelasDipilih->id, 'tahun_semester_id' => $tahunAktif->id]) }}"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-8m0 8l-4-4m4 4l4-4M4 20h16" />
                </svg>
                Export Excel
            </a>
        </div>
        {{-- Scrollable table only --}}
        <div class="overflow-x-auto p-4">
            <table class="min-w-[1200px] w-full text-sm text-center table-auto whitespace-nowrap">
                <thead class="text-gray-700 bg-gray-100 dark:bg-gray-800 dark:text-gray-200 sticky top-0 z-10">
                    <tr>
                        <th class="px-3 py-2 bg-gray dark:bg-gray-900">No</th>
                        <th class="px-3 py-2 bg-gray dark:bg-gray-900">Nama</th>
                        <th class="px-3 py-2 bg-gray dark:bg-gray-900">NIPD</th>
                        <th class="px-3 py-2 bg-gray dark:bg-gray-900">NISN</th>
                        <th class="px-3 py-2 bg-gray dark:bg-gray-900">Jenis Kelamin</th>
                        @foreach ($mapelColumns as $mapel)
                            <th class="px-3 py-2 bg-brand-50">{{ $mapel['nama'] }}</th>
                        @endforeach
                        <th class="px-3 py-2 bg-indigo-50 font-bold">Rata-rata</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900">
                    @foreach ($leger as $i => $row)
                        <tr>
                            <td class="px-3 py-2">{{ $i + 1 }}</td>
                            <td class="px-3 py-2 text-left">{{ $row['nama'] }}</td>
                            <td class="px-3 py-2">{{ $row['nipd'] }}</td>
                            <td class="px-3 py-2">{{ $row['nisn'] }}</td>
                            <td class="px-3 py-2">{{ $row['jk'] }}</td>
                            @foreach ($mapelColumns as $mapel)
                                <td class="px-3 py-2">{{ $row['mapel'][$mapel['id']] }}</td>
                            @endforeach
                            <td class="px-3 py-2 bg-indigo-50 font-bold">{{ $row['rata_rata'] }}</td>
                        </tr>
                    @endforeach
                    @if ($leger->isEmpty())
                        <tr>
                            <td colspan="{{ 5 + $mapelColumns->count() + 1 }}" class="text-center py-4 text-gray-500">
                                Tidak ada data siswa.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
