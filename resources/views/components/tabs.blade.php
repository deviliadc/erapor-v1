@props([
    'tabs' => [],
    'active' => '',
])

<div class="rounded-xl border border-gray-200 p-6 dark:border-gray-800" x-data="{ activeTab: '{{ $active ?? array_key_first($tabs) }}' }">
    <div class="border-b border-gray-200 dark:border-gray-800">
        <nav class="-mb-px flex space-x-2 overflow-x-auto [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-gray-200 dark:[&::-webkit-scrollbar-thumb]:bg-gray-600 dark:[&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar]:h-1.5">
            @foreach($tabs as $key => $label)
                <button
                    class="inline-flex items-center border-b-2 px-2.5 py-2 text-sm font-medium transition-colors duration-200 ease-in-out"
                    :class="activeTab === '{{ $key }}'
                        ? 'text-brand-500 dark:text-brand-400 border-brand-500 dark:border-brand-400'
                        : 'bg-transparent text-gray-500 border-transparent hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                    x-on:click="activeTab = '{{ $key }}'">
                    {{ $label }}
                </button>
            @endforeach
        </nav>
    </div>

    <div class="pt-4">
        {{-- Default content for the first tab --}}
        {{ $slot }}
    </div>
</div>
