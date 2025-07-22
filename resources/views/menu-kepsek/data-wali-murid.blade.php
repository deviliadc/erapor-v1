{{-- filepath: resources/views/kepala-sekolah/data-wali-murid.blade.php --}}
<x-app-layout>
    <h2 class="text-xl font-bold mb-4">Data Wali Murid</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white dark:bg-gray-800 rounded shadow">
            <thead>
                <tr>
                    <th class="px-4 py-2">Nama Ayah</th>
                    <th class="px-4 py-2">Nama Ibu</th>
                    <th class="px-4 py-2">Nama Wali</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $wali)
                <tr>
                    <td class="px-4 py-2">{{ $wali->nama_ayah }}</td>
                    <td class="px-4 py-2">{{ $wali->nama_ibu }}</td>
                    <td class="px-4 py-2">{{ $wali->nama_wali }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
