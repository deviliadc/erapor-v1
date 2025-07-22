<div>
    {{-- Search & Filter --}}
    <div class="mb-4 flex flex-col gap-3 px-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <div class="flex flex-wrap items-center gap-4">
            @foreach($filters as $field => $options)
                <select wire:model="filters.{{ $field }}"
                    class="h-9 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    <option value="">Semua {{ ucfirst($field) }}</option>
                    @foreach($options as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                    @endforeach
                </select>
            @endforeach
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
            {{-- Per Page Selector --}}
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500 dark:text-gray-400">Show :</span>
                <select wire:model="perPage"
                    class="h-9 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
                <span class="text-sm text-gray-500 dark:text-gray-400">entries</span>
            </div>
            {{-- Search Input --}}
            <div class="relative">
                <span class="pointer-events-none absolute top-1/2 left-4 -translate-y-1/2">
                    <svg class="fill-gray-500 dark:fill-gray-400" width="20" height="20" viewBox="0 0 20 20"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z"
                            fill=""></path>
                    </svg>
                </span>
                <input type="text" wire:model.debounce.500ms="search" placeholder="Search..."
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-[42px] w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-4 pl-[42px] text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden xl:w-[300px] dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
            </div>
        </div>
    </div>

    {{-- Bulk Action --}}
    @if(count($selected) > 0)
        <div class="mb-2 flex gap-2 items-center px-5">
            <button wire:click="bulkDelete" class="bg-red-600 text-white px-3 py-1 rounded">Hapus Terpilih ({{ count($selected) }})</button>
            <button wire:click="$set('selected', [])" class="text-gray-500 underline">Batal Pilih</button>
        </div>
    @endif

    <div class="custom-scrollbar overflow-x-auto w-full px-4 sm:px-6">
        <table class="min-w-full table-auto divide-y divide-gray-200 dark:divide-gray-700 text-sm">
            <thead class="border-y border-gray-100 py-3 dark:border-gray-800">
                <tr>
                    {{-- Checkbox select all --}}
                    <th class="px-4 py-3 text-left w-8">
                        <input type="checkbox"
                            @if(count($pageRowIds) && !array_diff($pageRowIds, $selected)) checked @endif
                            wire:click="
                                {{ !array_diff($pageRowIds, $selected) ? 'unselectAllPage('.json_encode($pageRowIds).')' : 'selectAllPage('.json_encode($pageRowIds).')' }}
                            "
                        />
                    </th>
                    {{-- Kolom Data --}}
                    @foreach ($columns as $key => $config)
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-900 dark:text-gray-400 whitespace-nowrap
                            {{ $config['sortable'] ? 'cursor-pointer select-none' : '' }}"
                            @if ($config['sortable']) wire:click="setSort('{{ $key }}')" @endif>
                            {{ $config['label'] }}
                            @if ($sortBy === $key)
                                <span>{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </th>
                    @endforeach
                    {{-- Kolom Aksi --}}
                    <th class="px-4 py-3 text-center w-32 text-sm font-medium text-gray-900 dark:text-gray-400 whitespace-nowrap">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse ($data as $item)
                    <tr class="{{ in_array($item->id, $selected) ? 'bg-gray-100 dark:bg-gray-800' : '' }} duration-200">
                        {{-- Checkbox per row --}}
                        <td class="px-4 py-3">
                            <input type="checkbox" wire:click="toggleSelect({{ $item->id }})" @if(in_array($item->id, $selected)) checked @endif />
                        </td>
                        {{-- Data Row --}}
                        @if ($rowView)
                            @include($rowView, ['item' => $item])
                        @else
                            @foreach ($columns as $key => $config)
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300 whitespace-nowrap">
                                    @if (isset($item->$key) && is_object($item->$key) && method_exists($item->$key, 'pluck'))
                                        {{ $item->$key->pluck('name')->join(', ') }}
                                    @else
                                        {{ $item->$key }}
                                    @endif
                                </td>
                            @endforeach
                        @endif
                        {{-- Action --}}
                        <td class="px-4 py-3 text-center text-sm font-medium text-gray-900 dark:text-gray-400 whitespace-nowrap" data-column="action">
                            <div class="flex justify-center gap-2 items-center">
                                @if ($actions['detail'] ?? false)
                                    <a href="{{ route(Str::replaceLast('.index', '.show', \Route::currentRouteName()), $item->id) }}"
                                        class="text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white/90">
                                        <x-icons.list-bullet />
                                    </a>
                                @endif
                                @if ($actions['edit'] ?? false)
                                    <button type="button"
                                        onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-modal-{{ $item->id }}' }))"
                                        class="text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white/90">
                                        <x-icons.edit />
                                    </button>
                                @endif
                                @if ($actions['delete'] ?? false)
                                    <div x-data="{ isDeleteModalOpen{{ $item->id }}: false }">
                                        <button @click="isDeleteModalOpen{{ $item->id }} = true"
                                            class="text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white/90">
                                            <x-icons.trash />
                                        </button>
                                        <x-modal.delete :id="$item->id"
                                            :route="($actions['routes']['delete'] ?? null)
                                                ? $actions['routes']['delete']($item)
                                                : route(
                                                    Str::replaceLast('.index', '.destroy', Route::currentRouteName()),
                                                    $item->id
                                                )"
                                            title="Yakin ingin menghapus?"
                                            message="Data yang sudah dihapus tidak dapat dipulihkan."
                                            confirmText="Hapus" />
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($columns) + 2 }}" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                            Tidak ada data
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-800 pagination-wrapper">
        <div class="flex items-center justify-between">
            {{-- Previous --}}
            @if ($data->onFirstPage())
                <span class="text-sm cursor-not-allowed rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-400 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-500">
                    Previous
                </span>
            @else
                <a href="{{ $data->previousPageUrl() }}"
                    class="text-sm rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                    Previous
                </a>
            @endif

            <span class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                Page {{ $data->currentPage() }} of {{ $data->lastPage() }}
            </span>

            {{-- Next --}}
            @if ($data->hasMorePages())
                <a href="{{ $data->nextPageUrl() }}"
                    class="text-sm rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                    Next
                </a>
            @else
                <span class="text-sm cursor-not-allowed rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-400 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-500">
                    Next
                </span>
            @endif
        </div>
    </div>
    <div class="mt-2">
        {{ $data->links() }}
    </div>
</div>
