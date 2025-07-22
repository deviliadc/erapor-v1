{{-- <td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap">{{ $loop->iteration + ($data->firstItem() - 1) }}</td> --}}
{{-- <td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap">{{ $loop->iteration }}</td> --}}
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap">
    {{ $loop->iteration + ($data->firstItem() - 1) }}
</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[200px]">{{ $item['name'] }}</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">{{ $item['username'] }}</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[200px]">{{ $item['email'] }}</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[200px]">{{ $item['roles'] }}</td>
