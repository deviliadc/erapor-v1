@props([
    'filters' => [],
    'enableAddButton' => true,
    'enableImport' => true,
    'enableExport' => true,
    'enableSearch' => true,
    'searchName' => 'search',
    'tabName' => null,
    'enablePerPage' => true,
    'routeCreate' => null,
    'routeExport' => '#',
    'routeImportForm' => null,
    'importModalName' => null,
    'exportModalName' => null,
    'exportFormats' => ['excel', 'pdf'],
    'filename' => '',
])

@php
    $showToolbar = (is_array($filters) && count($filters) > 0) || $enableAddButton || $enableImport || $enableExport;
@endphp

@if ($showToolbar)
    <div
        class="border-b border-gray-200 py-4 dark:border-gray-800 mb-4 flex flex-col gap-3 px-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <div
            class="flex flex-wrap items-center gap-4 overflow-x-auto max-w-full scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-transparent">
            @foreach ($filters as $filter)
                <x-select-filter
                    :name="$filter['name']"
                    :label="$filter['label']" :options="$filter['options']" :value-key="$filter['valueKey'] ?? 'id'"
                    :label-key="$filter['labelKey'] ?? 'name'" :enabled="$filter['enabled'] ?? true" />
            @endforeach
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">

            @if ($enableAddButton)
                {{ $addButton ?? view('components.table.default-add-button', ['routeCreate' => $routeCreate]) }}
            @endif

            @if ($enableImport)
                <button type="button" @click="$dispatch('open-modal', '{{ $importModalName }}')"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                    Import
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                </button>
            @endif

            {{-- @if ($enableExport)
                <!-- Export dropdown -->
                <x-table.dropdown label="Export">
                    @if (in_array('excel', $exportFormats))
                        <a href="{{ $routeExport }}?type=excel&filename={{ $filename }}"
                            class="dropdown-item flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12-3-3m0 0-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            Export Excel
                        </a>
                    @endif
                    @if (in_array('pdf', $exportFormats))
                        <a href="{{ $routeExport }}?type=pdf&filename={{ $filename }}"
                            class="dropdown-item flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12-3-3m0 0-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            Export PDF
                        </a>
                    @endif
                </x-table.dropdown>
            @endif --}}

            @if ($enableExport)
                @if ($exportModalName)
                    <button type="button"
                        @click="$dispatch('open-modal', '{{ $exportModalName }}')"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                        Export
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12-3-3m0 0-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                    </button>
                @else
                    <!-- Export dropdown -->
                    <x-table.dropdown label="Export">
                        @if (in_array('excel', $exportFormats))
                            <a href="{{ $routeExport }}?type=excel&filename={{ $filename }}"
                                class="dropdown-item flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12-3-3m0 0-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                                Export Excel
                            </a>
                        @endif
                        @if (in_array('pdf', $exportFormats))
                            <a href="{{ $routeExport }}?type=pdf&filename={{ $filename }}"
                                class="dropdown-item flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12-3-3m0 0-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                                Export PDF
                            </a>
                        @endif
                    </x-table.dropdown>
                @endif
            @endif
        </div>
    </div>
@endif

<div class="mb-4 flex flex-col gap-2 px-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
    {{-- Per Page Selector --}}
    @if ($enablePerPage)
        <form method="GET" class="flex items-center gap-2">
            @if ($tabName)
                <input type="hidden" name="tab" value="{{ $tabName }}">
            @endif
            <span class="text-sm text-gray-500 dark:text-gray-400">Show :</span>
            <select name="per_page" onchange="this.form.submit()"
                class="h-9 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
            </select>
            <span class="text-sm text-gray-500 dark:text-gray-400">entries</span>
        </form>
    @endif

    {{-- Search Input --}}
    @if ($enableSearch)
        <form method="GET">
            @if ($tabName)
                <input type="hidden" name="tab" value="{{ $tabName }}">
            @endif
            <div class="relative">
                <span class="pointer-events-none absolute top-1/2 left-4 -translate-y-1/2">
                    <svg class="fill-gray-500 dark:fill-gray-400" width="20" height="20" viewBox="0 0 20 20"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z"
                            fill=""></path>
                    </svg>
                </span>
                <input type="hidden" name="modal" class="modal-hidden-input">
                <input type="text" name="{{ $searchName }}" value="{{ request($searchName) }}"
                    placeholder="Search..."
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-[42px] w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-4 pl-[42px] text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden xl:w-[300px] dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
            </div>
        </form>
    @endif
</div>
