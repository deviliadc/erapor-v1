{{-- <td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap">
    {{ $loop->iteration + ($data->firstItem() - 1) }}
</td> --}}
{{-- <td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap">{{ $item['id'] }}</td> --}}
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">{{ $item['nama'] }}</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">{{ $item['fase'] }}</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">{{ $item['wali'] }}</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">
    <a href="{{ role_route('kelas-mapel.index', [
        'kelas' => $item['id'],
        'tahun_semester_filter' => request('tahun_semester_filter') ?? ($tahunAktif->id ?? null),
    ]) }}"
        class="text-brand-500 hover:underline">
        {{ $item['mapel_count'] }} Mapel
    </a>
</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">
    @php
        $tahunAjaranId = $tahunAjaranId ?? null;
        $tahunAjaranAktif = $tahunAjaranAktif ?? null;
    @endphp
    <a href="{{ role_route('kelas-siswa.show', [
        'kelas_siswa' => $item['id'],
        'tahun_ajaran_filter' => request('tahun_ajaran_filter') ?? ($tahunAjaranId ?? $tahunAjaranAktif?->id),
    ]) }}"
        class="text-brand-500 hover:underline">
        {{ $item['siswa_count'] }} Siswa
    </a>
</td>
