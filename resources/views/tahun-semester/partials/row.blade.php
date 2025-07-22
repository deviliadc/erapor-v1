<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap">
    {{ $loop->iteration + ($data->firstItem() - 1) }}
</td>
{{-- <td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap">{{ $item['id'] }}</td> --}}
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">{{ $item['tahun'] }}</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">{{ $item['semester'] }}</td>
<td class="px-5 py-4 sm:px-6">
    <x-badge.status :value="$item['status']" />
</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">
    <a href="{{ route('tahun-semester.show', $item['id']) }}"
    class="text-brand-500 hover:underline">
        {{ $item['siswa_count'] }} Siswa
    </a>
</td>
