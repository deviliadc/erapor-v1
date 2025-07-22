{{-- filepath: resources/views/kepala-sekolah/data-guru.blade.php --}}
<x-app-layout>
    <h2 class="text-xl font-bold mb-4">Data Guru & Tenaga Kerja</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white dark:bg-gray-800 rounded shadow">
            <thead>
                <tr>
                    <th class="px-4 py-2">Nama</th>
                    <th class="px-4 py-2">NIP</th>
                    <th class="px-4 py-2">Jabatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $guru)
                <tr>
                    <td class="px-4 py-2">{{ $guru->nama }}</td>
                    <td class="px-4 py-2">{{ $guru->nip }}</td>
                    <td class="px-4 py-2">{{ $guru->jabatan }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
