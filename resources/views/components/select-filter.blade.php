@props([
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
@endif
