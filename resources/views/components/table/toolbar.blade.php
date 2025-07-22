@props([
    // 'enableFilterRole' => true,
    'filters' => [],
    'enableAddButton' => true,
    'enableImport' => true,
    'enableExport' => true,
    'enableSearch' => true,
    'enablePerPage' => true,
    'routeCreate' => null,
])

<div class="mb-4 flex flex-col gap-3 px-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
    <div class="flex flex-wrap items-center gap-4">
        {{-- @if ($enableFilterRole)
            <form method="GET" class="flex flex-col sm:flex-row sm:items-center gap-2">
                <label for="role_filter" class="text-sm text-gray-500 dark:text-gray-400">Role:</label>
                <select name="role_filter" id="role_filter" onchange="this.form.submit()"
                    class="h-9 w-full sm:w-auto rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    <option value="">-- Pilih Role --</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ request('role_filter') == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        @endif --}}
        @foreach ($filters as $filter)
            <x-select-filter
                :name="$filter['name']"
                :label="$filter['label']"
                :options="$filter['options']"
                :value-key="$filter['valueKey'] ?? 'id'"
                :label-key="$filter['labelKey'] ?? 'name'"
                :enabled="$filter['enabled'] ?? true"
            />
        @endforeach
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
        @if ($enableAddButton && $routeCreate)
            <a href="{{ $routeCreate }}"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                Tambah
                <!-- plus icon -->
                <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M9.25 5a.75.75 0 011.5 0v4.25H15a.75.75 0 010 1.5h-4.25V15a.75.75 0 01-1.5 0v-4.25H5a.75.75 0 010-1.5h4.25V5z" />
                </svg>
            </a>
        @endif

        @if ($enableImport)
            <!-- Import dropdown -->
            <x-table.dropdown label="Import">
                <a href="#" class="dropdown-item">Download Template</a>
                <a href="#" class="dropdown-item">Import File</a>
            </x-table.dropdown>
        @endif

        @if ($enableExport)
            <!-- Export dropdown -->
            <x-table.dropdown label="Export">
                <a href="#" class="dropdown-item">Excel</a>
                <a href="#" class="dropdown-item">PDF</a>
            </x-table.dropdown>
        @endif
    </div>
</div>

<div class="border-t border-gray-200 py-4 dark:border-gray-800">
    <div class="mb-4 flex flex-col gap-2 px-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <!-- Pagination -->
        {{-- <div class="flex flex-wrap items-center gap-4 sm:gap-6"> --}}
        {{-- Per Page Selector --}}
        @if ($enablePerPage)
            <form method="GET" class="flex items-center gap-2">
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
                <div class="relative">
                    <span class="pointer-events-none absolute top-1/2 left-4 -translate-y-1/2">
                        <svg class="fill-gray-500 dark:fill-gray-400" width="20" height="20" viewBox="0 0 20 20"
                            fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z"
                                fill=""></path>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-[42px] w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-4 pl-[42px] text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden xl:w-[300px] dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                </div>
            </form>
        @endif
    </div>
</div>
