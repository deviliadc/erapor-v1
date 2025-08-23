<x-app-layout>
    <h2 class="text-xl font-bold mb-4">Detail Presensi Siswa</h2>
    <div class="mb-2">
        Tahun Semester : 
        {{ $tahunSemesterAktif->tahunAjaran->tahun ?? '-' }} - {{ ucfirst($tahunSemesterAktif->semester ?? '-') }} <br>
        Kelas : {{ $kelas->nama ?? '-' }}<br>
        Wali Kelas : {{ $waliKelas }}
    </div>
    <x-table :columns="[
            // 'no' => ['label' => 'No', 'sortable' => false],
            // 'id' => ['label' => 'ID', 'sortable' => true],
            // 'nama' => ['label' => 'Nama Kelas', 'sortable' => true],
            'kelas' => ['label' => 'Kelas', 'sortable' => true],
            'wali_kelas' => ['label' => 'Wali Kelas', 'sortable' => true],
            'jumlah_siswa' => ['label' => 'Jumlah Siswa', 'sortable' => true],
            // 'action' => ['label' => 'Aksi', 'sortable' => false],
        ]" :data="$kelas"
            :total-count="$totalCount"
            row-view="menu-kepsek.partials.row-presensi"
            :selectable="false"
            :actions="[
                'detail' => true,
                'edit' => false,
                'delete' => false,
                ],
                'routes' => [
                    'detail' => fn($item) => route('kepala-sekolah.rekap-presensi.detail', [
                    'id' => $item['id'],
                    'tahun_semester_filter' => request('tahun_semester_filter') ?? ($tahunSemesterId ?? $tahunSemesterAktif?->id),
                    ]),
            ]"/>
    {{-- <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded shadow">
            <thead>
                <tr>
                    <th>No Absen</th>
                    <th>Nama</th>
                    <th>Hadir</th>
                    <th>Sakit</th>
                    <th>Izin</th>
                    <th>Alpha</th>
                </tr>
            </thead>
            <tbody>
                @foreach($presensiData as $row)
                <tr>
                    <td>{{ $row['no_absen'] }}</td>
                    <td>{{ $row['nama'] }}</td>
                    <td>{{ $row['hadir'] }}</td>
                    <td>{{ $row['sakit'] }}</td>
                    <td>{{ $row['izin'] }}</td>
                    <td>{{ $row['alfa'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div> --}}
</x-app-layout>
