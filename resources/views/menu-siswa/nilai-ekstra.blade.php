{{-- filepath: resources/views/siswa/nilai-ekstra.blade.php --}}
<x-app-layout>
    <div class="container mx-auto py-8">
        <h2 class="text-xl font-bold mb-4">Nilai Ekstrakurikuler</h2>
        <table class="min-w-full bg-white rounded shadow">
            <thead>
                <tr>
                    <th class="px-4 py-2">Ekstrakurikuler</th>
                    <th class="px-4 py-2">Nilai</th>
                    <th class="px-4 py-2">Predikat</th>
                    <th class="px-4 py-2">Periode</th>
                </tr>
            </thead>
            <tbody>
                @foreach($nilaiEkstra as $e)
                <tr>
                    <td class="px-4 py-2">{{ $e->ekstra->nama }}</td>
                    <td class="px-4 py-2">{{ $e->nilai_akhir }}</td>
                    <td class="px-4 py-2">{{ $e->predikat }}</td>
                    <td class="px-4 py-2">{{ ucfirst($e->periode) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
