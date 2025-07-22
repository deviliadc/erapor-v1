@props([
    'label' => '',
    'name',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
])

@php
    $isReadonly = $attributes->has('readonly');
    $isRequired = $attributes->has('required');
@endphp

<div>
    @if ($label)
        <label for="{{ $name }}" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            {{ $label }} @if ($isRequired) <span class="text-error-500">*</span> @endif
        </label>
    @endif

    {{-- <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge([
            'class' => 'form-input h-11 w-full rounded-lg border px-4 py-2.5 text-sm
                ' . ($isReadonly
                    ? 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 cursor-not-allowed border-gray-300 dark:border-gray-700'
                    : 'bg-transparent border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-gray-800 dark:text-white/90 placeholder:text-gray-400 dark:placeholder:text-white/30 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 focus:ring-3 focus:outline-hidden'
                )
            ])
        }}
        @if ($isReadonly) readonly @endif
    > --}}

    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}"
        @if (!empty($value) && !$attributes->has('x-model')) value="{{ old($name, $value) }}" @endif placeholder="{{ $placeholder }}"
        {{ $attributes->merge([
            'class' =>
                'form-input h-11 w-full rounded-lg border px-4 py-2.5 text-sm' .
                ($isReadonly
                    ? ' bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 cursor-not-allowed border-gray-300 dark:border-gray-700'
                    : ' bg-transparent border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-gray-800 dark:text-white/90 placeholder:text-gray-400 dark:placeholder:text-white/30 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 focus:ring-3 focus:outline-hidden'),
        ]) }}
        @if ($isReadonly) readonly @endif>

    @error($name)
        <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
    @enderror
</div>
