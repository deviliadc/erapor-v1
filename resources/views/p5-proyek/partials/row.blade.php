<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap">
    {{ $loop->iteration + ($data->firstItem() - 1) }}
</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">{{ $item['nama_kelas'] }}</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">{{ $item['nama_proyek'] }}</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">{{ $item['deskripsi_proyek'] }}</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">{{ $item['nama_guru'] }}</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">{{ $item['nama_tema'] }}</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">{{ $item['dimensi'] }}</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">{{ $item['elemen'] }}</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">{{ $item['sub_elemen'] }}</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">{{ $item['nama_tahun_semester'] }}</td>
