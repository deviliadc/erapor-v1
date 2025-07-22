{{-- filepath: resources/views/siswa/nilai-p5.blade.php --}}
<x-app-layout>
    <div class="container mx-auto py-8">
        <h2 class="text-xl font-bold mb-4">Nilai P5</h2>
        <table class="min-w-full bg-white rounded shadow">
            <thead>
                <tr>
                    <th class="px-4 py-2">Proyek</th>
                    <th class="px-4 py-2">Sub Elemen</th>
                    <th class="px-4 py-2">Nilai</th>
                    <th class="px-4 py-2">Predikat</th>
                    <th class="px-4 py-2">Periode</th>
                </tr>
            </thead>
            <tbody>
                @foreach($nilaiP5 as $p)
                <tr>
                    <td class="px-4 py-2">{{ $p->proyek->nama ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $p->subElemen->nama ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $p->nilai_akhir }}</td>
                    <td class="px-4 py-2">{{ $p->predikat }}</td>
                    <td class="px-4 py-2">{{ ucfirst($p->periode) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
