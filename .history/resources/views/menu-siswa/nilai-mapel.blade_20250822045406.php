{{-- filepath: resources/views/siswa/nilai-mapel.blade.php --}}
<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />
    <div class="max-w-3xl mx-auto py-8">

        {{-- Filter Tahun Semester --}}
        <form method="GET" class="mb-6">
            <label for="tahun_semester_id" class="mr-2 font-semibold dark:text-gray-200">Tahun Semester:</label>
            <select name="tahun_semester_id" id="tahun_semester_id" onchange="this.form.submit()" class="border rounded px-2 py-1 dark:bg-gray-800 dark:text-white dark:border-gray-600">
                @foreach($daftarTahunSemester as $ts)
                    <option value="{{ $ts->id }}" {{ request('tahun_semester_id', $tahunAktif->id) == $ts->id ? 'selected' : '' }}>
                        {{ $ts->tahunAjaran->tahun }} - {{ ucfirst($ts->semester) }}
                    </option>
                @endforeach
            </select>
        </form>
        <table class="min-w-full bg-white rounded shadow">
            <thead>
                <tr>
                    <th class="px-4 py-2">Mata Pelajaran</th>
                    <th class="px-4 py-2">UTS</th>
                    <th class="px-4 py-2">UAS</th>
                </tr>
            </thead>
            <tbody>
        @foreach($nilaiMapel as $mapelId => $nilaiList)
            @php
                $mapelNama = $nilaiList->first()->mapel->nama ?? '-';
                $nilaiUts = $nilaiList->firstWhere('periode', 'tengah')?->nilai ?? '-';
                $nilaiUas = $nilaiList->firstWhere('periode', 'akhie')?->nilai ?? '-';
            @endphp
            <tr>
                <td class="px-4 py-2">{{ $mapelNama }}</td>
                <td class="px-4 py-2">{{ $nilaiUts }}</td>
                <td class="px-4 py-2">{{ $nilaiUas }}</td>
            </tr>
        @endforeach
    </tbody>
        </table>
    </div>
</x-app-layout>
