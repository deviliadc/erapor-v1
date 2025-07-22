@props([
    'label' => '',
    'name' => '',
    'options' => [],
    'placeholder' => '',
    'selected' => null,
    'searchable' => true,
    'multiple' => false,
])

@php
    use Illuminate\Support\Str;

    // Tangani multiple: ambil nama dasar tanpa []
    $isMultiple = $multiple || Str::endsWith($name, '[]');
    $baseName = $isMultiple ? Str::beforeLast($name, '[]') : $name;

    // Ambil nilai yang sudah di-submit sebelumnya
    $selected = old($baseName, $selected);
    $isRequired = $attributes->has('required');
    $selectId = $attributes->get('id', $baseName);

    $options = is_array($options) || is_object($options) ? $options : [];
@endphp

<div class="w-full" x-data="{ isOptionSelected: '{{ $isMultiple ? (!empty($selected) ? '1' : '') : ($selected !== null ? $selected : '') }}' !== '' }">
    @if ($label)
        <label for="{{ $selectId }}" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            {{ $label }} @if ($isRequired) <span class="text-error-500">*</span> @endif
        </label>
    @endif

    <select
        x-init="new TomSelect($el, {
            plugins: ['clear_button'],
            allowEmptyOption: true,
            create: false
        })"
        class="tom-select h-9 w-full sm:w-auto rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
        name="{{ $name }}"
        id="{{ $selectId }}"
        @if ($isMultiple) multiple @endif
        {{ $attributes->merge([
            'class' =>
                'appearance-none w-full h-11 rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-10 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90',
        ]) }}
        :class="isOptionSelected ? 'text-gray-800 dark:text-white/90' : 'text-gray-500 dark:text-gray-400'"
        @change="isOptionSelected = $event.target.value !== ''"
    >
        @if ($placeholder && !$isMultiple)
            <option value="" disabled {{ $selected === '' ? 'selected' : '' }}>{{ $placeholder }}</option>
        @endif

        @foreach ($options as $key => $value)
            <option value="{{ $key }}"
                @if ($isMultiple)
                    {{ is_array($selected) && in_array($key, $selected) ? 'selected' : '' }}
                @else
                    {{ $selected == $key ? 'selected' : '' }}
                @endif>
                {{ is_array($value) ? ($value['nama'] ?? json_encode($value)) : $value }}
            </option>
        @endforeach
    </select>

    @error($baseName)
        <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
    @enderror
</div>

<style>
    .tom-select .ts-control {
        border: none !important;
        box-shadow: none !important;
        background: transparent !important;
    }

    .tom-select .ts-control:focus,
    .tom-select .ts-control.focus {
        border: none !important;
        box-shadow: none !important;
    }
</style>
