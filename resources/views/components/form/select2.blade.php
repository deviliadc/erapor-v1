@props([
    'label' => '',
    'name' => '',
    'options' => [],
    'placeholder' => '',
    'selected' => null,
])

@php
    $selected = old($name, $selected);
    $selectId = $attributes->get('id', $name);
    $isRequired = $attributes->has('required');
@endphp

<div class="w-full">
    @if ($label)
        <label for="{{ $selectId }}" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            {{ $label }} @if ($isRequired) <span class="text-error-500">*</span> @endif
        </label>
    @endif

    <select
        name="{{ $name }}"
        id="{{ $selectId }}"
        {{ $attributes->merge([
            'class' =>
                'select2 w-full h-11 rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-10 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90',
        ]) }}
    >
        @if ($placeholder)
            <option value="" disabled {{ $selected === '' ? 'selected' : '' }}>{{ $placeholder }}</option>
        @endif

        @foreach ($options as $key => $value)
            <option value="{{ $key }}" {{ $selected == $key ? 'selected' : '' }}>
                {{ $value }}
            </option>
        @endforeach
    </select>

    @error($name)
        <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
    @enderror
</div>
