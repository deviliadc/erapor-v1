<td class="border px-4 py-2">{{ $item['tahun_semester'] }}</td>
<td class="border px-4 py-2">{{ $item['tipe'] }}</td>
<td class="border px-4 py-2">
    <x-badge.status :value="$item['is_validated']" />
</td>
<td class="border px-4 py-2">{{ $item['validator_name'] ?? $item['validator_username']?? '-' }}</td>
<td class="border px-4 py-2">
    {{ $item['validated_at']
        ? \Carbon\Carbon::parse($item['validated_at'])
            ->locale('id')
            ->timezone('Asia/Jakarta') // set timezone ke WIB
            ->translatedFormat('d F Y H:i')
        : '-'
    }}
</td>

<td class="border px-4 py-2">
    @if (!$item['is_validated'])
        <form action="{{ role_route('validasi-semester.validate', ['validasiSemester' => $item['id']]) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-blue-light-500 shadow-theme-xs hover:bg-blue-light-600">Validasi</button>
        </form>
    @else
        <form action="{{ role_route('validasi-semester.cancel', ['validasiSemester' => $item['id']]) }}" method="POST"
            class="inline">
            @csrf
            <button type="submit" class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-error-500 shadow-theme-xs hover:bg-error-600">Batal</button>
        </form>
    @endif
</td>
