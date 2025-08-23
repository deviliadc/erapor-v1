{{-- filepath: resources/views/kepala-sekolah/rekap-absensi.blade.php --}}
<x-app-layout>
    <h2 class="text-xl font-bold mb-4">Rekap Absensi</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white dark:bg-gray-800 rounded shadow">
            <thead>
                <tr>
                    <th class="px-4 py-2">Nama Siswa</th>
                    <th class="px-4 py-2">Sakit</th>
                    <th class="px-4 py-2">Izin</th>
                    <th class="px-4 py-2">Alfa</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $absen)
                <tr>
                    <td class="px-4 py-2">{{ $absen->siswa->nama ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $absen->total_sakit }}</td>
                    <td class="px-4 py-2">{{ $absen->total_izin }}</td>
                    <td class="px-4 py-2">{{ $absen->total_alfa }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
