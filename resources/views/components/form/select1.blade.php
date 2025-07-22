@props([
    'label' => '',
    'name' => '',
    'options' => [],
    'placeholder' => '',
    'selected' => null,
])

@php
    $selected = old($name, $selected);
    $isRequired = $attributes->has('required');
@endphp

{{-- <div class="w-full">
    @if ($label)
        <label for="{{ $name }}" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            {{ $label }} @if ($isRequired) <span class="text-error-500">*</span> @endif
        </label>
    @endif

    <div x-data="{ isOptionSelected: '{{ $selected }}' !== '' }" class="relative z-20">
        <select
            name="{{ $name }}"
            id="{{ $name }}"
            {{ $attributes->merge([
                'class' =>
                    'appearance-none w-full h-11 rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-10 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90',
            ]) }}
            :class="isOptionSelected ? 'text-gray-800 dark:text-white/90' : 'text-gray-500 dark:text-gray-400'"
            @change="isOptionSelected = $event.target.value !== ''"
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

        <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">
            <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </span>
    </div>

    @error($name)
        <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
    @enderror
</div> --}}

<div class="w-full">
    @if ($label)
        <label for="{{ $name }}" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            {{ $label }} @if ($isRequired) <span class="text-error-500">*</span> @endif
        </label>
    @endif

    <select
        x-init="new TomSelect($el, { allowEmptyOption: true })"
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $attributes->merge([
                'class' =>
                    'appearance-none w-full h-11 rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-10 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90',
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
