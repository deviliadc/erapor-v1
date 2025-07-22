<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap">
    {{ \Carbon\Carbon::parse($item['tanggal'])->translatedFormat('l, d F Y') }}
</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap">{{ $item['kelas'] }}</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap">{{ $item['catatan'] }}</td>
