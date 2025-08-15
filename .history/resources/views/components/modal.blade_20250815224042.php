@props(['name', 'title' => null, 'maxWidth' => '2xl'])

@php
    $maxWidthClass = [
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
        '3xl' => 'sm:max-w-3xl',
        '4xl' => 'sm:max-w-4xl',
        '5xl' => 'sm:max-w-5xl',
        '6xl' => 'sm:max-w-6xl',
        '7xl' => 'sm:max-w-7xl',
    ][$maxWidth];
@endphp

<div x-data="{
    show: false,
    init() {
        window.addEventListener('open-modal', (e) => {
            if (e.detail === {{ Js::from($name) }}) {
                this.show = true;
            }
        })
        window.addEventListener('close-modal', (e) => {
            if (e.detail === {{ Js::from($name) }}) {
                this.show = false;
            }
        })
    }
}" x-show="show" x-cloak x-transition.opacity
    class="fixed inset-0 z-[999999] flex items-start justify-center overflow-y-auto px-4 py-6 sm:px-0 bg-gray-400/50 backdrop-blur-md">

    <div {{ $attributes->merge(['class' => "relative w-full {$maxWidthClass} max-w-md rounded-3xl bg-white dark:bg-gray-900 shadow-xl overflow-hidden"]) }}>

        <!-- Modal Wrapper (sticky header + scrollable content) -->
        <div class="flex flex-col h-full max-h-[80vh] overflow-y-auto">

            <!-- Header -->
            @if ($title)
                <div
                    class="sticky top-0 z-10 flex items-center justify-between px-6 pb-4 pt-4 border-b border-gray-200 dark:border-gray-700 ">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">{{ $title }}</h3>
                    <button @click="show = false"
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-gray-300 hover:text-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24">
                            <path d="M6 18L18 6M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>
            @endif

            <!-- Body -->
            <div class="flex-1 overflow-y-auto">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
