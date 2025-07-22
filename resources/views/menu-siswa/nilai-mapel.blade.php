{{-- filepath: resources/views/siswa/nilai-mapel.blade.php --}}
<x-app-layout>
    <div class="container mx-auto py-8">
        <h2 class="text-xl font-bold mb-4">Nilai Mata Pelajaran</h2>
        <table class="min-w-full bg-white rounded shadow">
            <thead>
                <tr>
                    <th class="px-4 py-2">Mata Pelajaran</th>
                    <th class="px-4 py-2">Nilai Akhir</th>
                    <th class="px-4 py-2">Periode</th>
                </tr>
            </thead>
            <tbody>
                @foreach($nilaiMapel as $n)
                <tr>
                    <td class="px-4 py-2">{{ $n->mapel->nama }}</td>
                    <td class="px-4 py-2">{{ $n->nilai_akhir }}</td>
                    <td class="px-4 py-2">{{ ucfirst($n->periode) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
