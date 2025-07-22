{{-- filepath: resources/views/kepala-sekolah/data-siswa.blade.php --}}
<x-app-layout>
    <h2 class="text-xl font-bold mb-4">Data Siswa</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white dark:bg-gray-800 rounded shadow">
            <thead>
                <tr>
                    <th class="px-4 py-2">Nama</th>
                    <th class="px-4 py-2">NIS</th>
                    <th class="px-4 py-2">Kelas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $siswa)
                <tr>
                    <td class="px-4 py-2">{{ $siswa->nama }}</td>
                    <td class="px-4 py-2">{{ $siswa->nis }}</td>
                    <td class="px-4 py-2">{{ $siswa->kelasSiswa->kelas->nama ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
