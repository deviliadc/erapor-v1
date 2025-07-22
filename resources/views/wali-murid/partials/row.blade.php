<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap">
    {{ $loop->iteration + ($data->firstItem() - 1) }}
</td>
{{-- <td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap">{{ $item['id'] }}</td> --}}
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">{{ $item['nama_ayah'] }}</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">{{ $item['nama_ibu'] }}</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[200px]">{{ $item['nama_wali'] }}</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[200px]">{{ $item['jumlah_anak'] }} Anak</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[200px]">{{ $item['no_hp'] }}</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[200px]">{{ $item['pekerjaan_ayah'] }}</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[200px]">{{ $item['pekerjaan_ibu'] }}</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[200px]">{{ $item['pekerjaan_wali'] }}</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[200px]">{{ $item['alamat'] }}</td>
