{{-- filepath: d:\DEVI\DRAFT\erapor-v1\resources\views\menu-kepsek\partials\row-nilai-p5-detail.blade.php --}}
<td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">{{ $item['nama'] }}</td>
@foreach($columns as $key => $config)
    @if($key !== 'nama')
        <td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[120px]">{{ $item[$key] ?? '-' }}</td>
    @endif
@endforeach
