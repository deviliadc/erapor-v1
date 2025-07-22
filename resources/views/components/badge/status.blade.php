@props([
    'value' => 1, // default 1 = Active
])

@php
    $isActive = $value == 1 || $value === 'Active';
@endphp

<p class="
    text-theme-xs font-medium inline-block rounded-full px-2 py-0.5
    {{ $isActive
        ? 'bg-success-50 text-success-700 dark:bg-success-500/15 dark:text-success-500'
        : 'bg-error-50 text-error-700 dark:bg-error-500/15 dark:text-error-500'
    }}
">
    {{ $isActive ? 'Active' : 'Inactive' }}
</p>
