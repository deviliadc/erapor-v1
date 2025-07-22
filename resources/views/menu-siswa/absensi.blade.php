{{-- filepath: resources/views/siswa/absensi.blade.php --}}
<x-app-layout>
    <div class="container mx-auto py-8">
        <h2 class="text-xl font-bold mb-4">Rekap Absensi</h2>
        <table class="min-w-full bg-white rounded shadow">
            <thead>
                <tr>
                    <th class="px-4 py-2">Semester</th>
                    <th class="px-4 py-2">Sakit</th>
                    <th class="px-4 py-2">Izin</th>
                    <th class="px-4 py-2">Alfa</th>
                </tr>
            </thead>
            <tbody>
                @foreach($absensi as $a)
                <tr>
                    <td class="px-4 py-2">{{ $a->tahunSemester->tahun }} ({{ ucfirst($a->tahunSemester->semester) }})</td>
                    <td class="px-4 py-2">{{ $a->total_sakit }}</td>
                    <td class="px-4 py-2">{{ $a->total_izin }}</td>
                    <td class="px-4 py-2">{{ $a->total_alfa }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
