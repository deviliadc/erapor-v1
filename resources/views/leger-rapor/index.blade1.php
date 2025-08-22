<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

  <div class="container">
    <h1 class="mb-4">Leger Rapor - Tahun Semester {{ $tahunAktif->nama ?? '-' }} ({{ ucfirst($periode) }})</h1>

    <table class="table table-bordered table-sm">
        <thead class="thead-light">
            <tr>
                <th rowspan="2" class="align-middle text-center">No</th>
                <th rowspan="2" class="align-middle">Nama Siswa</th>
                <th colspan="{{ $leger->first()['mapel']->count() }}" class="text-center">Mata Pelajaran</th>
                <th colspan="{{ $leger->first()['ekstra']->count() }}" class="text-center">Ekstrakurikuler</th>
                <th colspan="{{ $leger->first()['p5']->count() }}" class="text-center">Proyek / P5</th>
                <th rowspan="2" class="align-middle text-center">Sakit</th>
                <th rowspan="2" class="align-middle text-center">Izin</th>
                <th rowspan="2" class="align-middle text-center">Alfa</th>
            </tr>
            <tr>
                @foreach($leger->first()['mapel'] as $mapelNama => $nilai)
                    <th class="text-center">{{ $mapelNama }}</th>
                @endforeach

                @foreach($leger->first()['ekstra'] as $ekstraNama => $nilai)
                    <th class="text-center">{{ $ekstraNama }}</th>
                @endforeach

                @foreach($leger->first()['p5'] as $p5Nama => $catatan)
                    <th class="text-center">{{ $p5Nama }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($leger as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item['siswa'] }}</td>

                {{-- Mapel --}}
                @foreach($item['mapel'] as $nilai)
                    <td class="text-center">{{ $nilai ?? '-' }}</td>
                @endforeach

                {{-- Ekstra --}}
                @foreach($item['ekstra'] as $nilai)
                    <td class="text-center">{{ $nilai ?? '-' }}</td>
                @endforeach

                {{-- P5 --}}
                @foreach($item['p5'] as $catatan)
                    <td>{{ $catatan ?? '-' }}</td>
                @endforeach

                {{-- Absensi --}}
                <td class="text-center">{{ $item['absensi']->total_sakit ?? 0 }}</td>
                <td class="text-center">{{ $item['absensi']->total_izin ?? 0 }}</td>
                <td class="text-center">{{ $item['absensi']->total_alfa ?? 0 }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</x-app-layout>
