<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap">
    {{ $loop->iteration + ($data->firstItem() - 1) }}
</td>
{{-- <td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap">{{ $item->id }}</td> --}}
{{-- <td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">
    {{ $item->guruKelas->kelas->nama ?? '-' }}
</td> --}}
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">
    {{ $item['kelas'] }}</td>
{{-- <td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">
    {{ $item->guruKelas->mapel->nama ?? '-' }}
</td> --}}
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">
    {{ $item['mapel'] }}</td>
{{-- <td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">
    {{ $item->bab->nama ?? '-' }}
</td> --}}
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">
    {{ $item['bab'] }}
</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">
    {{ $item['nama'] }}
</td>
{{-- <td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap">
    {{ $item['tujuan_pembelajaran_count'] }} Tujuan
</td> --}}
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">
    <a href="{{ route('lingkup-materi.show', $item['id']) }}"
    class="text-brand-500 hover:underline">
        {{ $item['tujuan_pembelajaran_count'] }} Tujuan
    </a>
</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap">
    {{ $item['periode'] === 'tengah' ? 'Tengah Semester' : 'Akhir Semester' }}
</td>
