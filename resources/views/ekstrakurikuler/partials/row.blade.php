<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap">
    {{ $loop->iteration + ($data->firstItem() - 1) }}
</td>
{{-- <td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap">{{ $item['id'] }}</td> --}}
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">{{ $item['nama'] }}</td>
{{-- <td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">
    {{ $item['jumlah_parameter'] }} Parameter
</td>
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">
    {{ $item['jumlah_siswa'] }} Siswa
</td> --}}
<td class="px-4 py-3 text-brand-700 dark:text-brand-400 whitespace-nowrap min-w-[150px]">
    <a href="#" class="text-brand-600 hover:underline"
        @click.prevent="window.dispatchEvent(new CustomEvent('open-parameter-modal', { detail: { id: {{ $item['id'] }} } }))">
        {{ $item['jumlah_parameter'] }} Parameter
    </a>
</td>
