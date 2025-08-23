<x-app-layout>
    <h2 class="text-xl font-bold mb-4">Detail Presensi Siswa</h2>
    <div class="mb-2">
        Tahun Semester : 
        {{ $tahunSemesterAktif->tahunAjaran->tahun ?? '-' }} - {{ ucfirst($tahunSemesterAktif->semester ?? '-') }} <br>
        Kelas : {{ $kelas->nama ?? '-' }}<br>
        Wali Kelas : {{ $waliKelas }}
    </div>
    <div class="overflow-x-auto">
        {{-- <table class="min-w-full bg-white rounded shadow">
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
        </table> --}}
    </div>
</x-app-layout>
