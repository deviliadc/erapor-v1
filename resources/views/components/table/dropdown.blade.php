@props(['label'])

<div x-data="{ open: false }" class="relative inline-block">
    <button @click.prevent="open = !open"
        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 w-full sm:w-auto">
                        {{ $label }}
        <svg class="stroke-current transition-transform duration-200"
            :class="{ 'rotate-180': open }" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4.792 7.396L10 12.604l5.208-5.208" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </button>

    <div x-show="open" @click.outside="open = false" x-transition
        class="absolute z-40 mt-2 w-64 rounded-2xl border border-gray-200 bg-white p-3 shadow-theme-lg dark:border-gray-800 dark:bg-[#1E2635]"
                        style="display: none;">
        <ul class="flex flex-col gap-1">
            {{ $slot }}
        </ul>
    </div>
</div>
