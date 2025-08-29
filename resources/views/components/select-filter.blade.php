{{-- @props([
    'name',
    'label',
    'options' => [],
    'valueKey' => 'id',
    'labelKey' => 'name',
    'enabled' => true,
])

@if ($enabled)
    <form method="GET" class="flex flex-col sm:flex-row sm:items-center gap-2">
        <label for="{{ $name }}" class="text-sm text-gray-500 dark:text-gray-400">{{ $label }}:</label>
        <select name="{{ $name }}" id="{{ $name }}" onchange="this.form.submit()"
            class="h-9 w-full sm:w-auto rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            <option value="">{{ '-- ' . $label . ' --' }}</option>
            @foreach ($options as $option)
                <option value="{{ $option[$valueKey] }}" {{ request($name) == $option[$valueKey] ? 'selected' : '' }}>
                    {{ $option[$labelKey] }}
                </option>
            @endforeach
        </select>
    </form>
@endif --}}
{{-- filepath: d:\DEVI\DRAFT\erapor-v1\resources\views\components\select-filter.blade.php --}}
@props([
    'name',
    'label',
    'options' => [],
    'valueKey' => 'id',
    'labelKey' => 'name',
    'enabled' => true,
    'maxOptions' => 10, // batas jumlah opsi
])

@if ($enabled)
    <form method="GET" class="flex flex-col sm:flex-row sm:items-center gap-2">
        <label for="{{ $name }}" class="text-sm text-gray-500 dark:text-gray-400">{{ $label }}:</label>
        <select name="{{ $name }}" id="{{ $name }}" onchange="this.form.submit()"
            class="h-9 w-full sm:w-auto rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            <option value="">{{ '-- ' . $label . ' --' }}</option>
                        @foreach ($options as $option)
                            <option value="{{ is_array($option) ? $option[$valueKey] : $option->{$valueKey} }}" {{ request($name) == (is_array($option) ? $option[$valueKey] : $option->{$valueKey}) ? 'selected' : '' }}>
                                {{ is_array($option) ? $option[$labelKey] : $option->{$labelKey} }}
                            </option>
                        @endforeach
        </select>
    </form>
@endif
