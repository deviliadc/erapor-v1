<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap">
    {{ $loop->iteration + ($data->firstItem() - 1) }}
</td>
{{-- <td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap">{{ $item->id }}</td> --}}
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">
    {{ $item->guruKelas->kelas->nama ?? '-' }}
</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">
    {{ $item->guruKelas->mapel->nama ?? '-' }}
</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">
    {{ $item->bab->nama ?? '-' }}
</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">
    {{ $item->nama }}
</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap">
    {{-- <a href="{{ route('tujuan-pembelajaran.index', ['lingkupMateri' => $item->id]) }}"
        class="text-blue-500 hover:text-blue-700">
        {{ $item->tujuan_pembelajaran_count }} Tujuan
    </a> --}}
    {{ $item->tujuan_pembelajaran_count }} Tujuan
</td>
