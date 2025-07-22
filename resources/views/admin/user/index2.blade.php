<x-app-layout>
    <div x-data="{ pageName: `Manage User` }">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90" x-text="pageName">Manage User</h2>
            <nav>
                <ol class="flex items-center gap-1.5">
                    <li>
                        <a class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400"
                            href="dashboard">
                            Home
                            <svg class="stroke-current" width="17" height="16" viewBox="0 0 17 16" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366" stroke=""
                                    stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    </li>
                    <li class="text-sm text-gray-800 dark:text-white/90" x-text="pageName">Manage User</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Wrapper -->
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

        <!-- Filter & Aksi -->
        <div class="mb-4 flex flex-col gap-3 px-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div class="flex flex-wrap items-center gap-4">
                <!-- Role Filter -->
                <form method="GET" action="{{ route('admin.user.index') }}"
                    class="flex flex-col sm:flex-row sm:items-center gap-2">
                    <label for="role_filter" class="text-sm text-gray-500 dark:text-gray-400">Role:</label>
                    <select name="role_filter" id="role_filter" onchange="this.form.submit()"
                        class="h-9 w-full sm:w-auto rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <option value="">-- Pilih Role --</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}"
                                {{ request('role_filter') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            {{-- Aksi Tombol --}}
            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                <!-- Tombol Tambah -->
                <a href="{{ route('admin.user.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                    Tambah
                    <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M9.2502 4.99951C9.2502 4.5853 9.58599 4.24951 10.0002 4.24951C10.4144 4.24951 10.7502 4.5853 10.7502 4.99951V9.24971H15.0006C15.4148 9.24971 15.7506 9.5855 15.7506 9.99971C15.7506 10.4139 15.4148 10.7497 15.0006 10.7497H10.7502V15.0001C10.7502 15.4143 10.4144 15.7501 10.0002 15.7501C9.58599 15.7501 9.2502 15.4143 9.2502 15.0001V10.7497H5C4.58579 10.7497 4.25 10.4139 4.25 9.99971C4.25 9.5855 4.58579 9.24971 5 9.24971H9.2502V4.99951Z"
                            fill=""></path>
                    </svg>
                </a>

                <!-- Dropdown Import -->
                <div x-data="{ openImportDropDown: false }" class="relative inline-block">
                    <button @click.prevent="openImportDropDown = !openImportDropDown"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 w-full sm:w-auto">
                        Import
                        <svg class="stroke-current transition-transform duration-200"
                            :class="{ 'rotate-180': openImportDropDown }" width="20" height="20"
                            viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4.79199 7.396L10.0003 12.6043L15.2087 7.396" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="openImportDropDown" @click.outside="openImportDropDown = false" x-transition
                        class="absolute z-40 mt-2 w-64 rounded-2xl border border-gray-200 bg-white p-3 shadow-theme-lg dark:border-gray-800 dark:bg-[#1E2635]"
                        style="display: none;">
                        <ul class="flex flex-col gap-1">
                            <li>
                                <a href="#"
                                    class="flex rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5">
                                    Download Template
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5">
                                    Import File
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>


                <!-- Dropdown Export -->
                <div x-data="{ openExportDropDown: false }" class="relative inline-block">
                    <button @click.prevent="openExportDropDown = !openExportDropDown"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 w-full sm:w-auto">
                        Export
                        <svg class="stroke-current transition-transform duration-200"
                            :class="{ 'rotate-180': openExportDropDown }" width="20" height="20"
                            viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4.79199 7.396L10.0003 12.6043L15.2087 7.396" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="openExportDropDown" @click.outside="openExportDropDown = false" x-transition
                        class="absolute z-40 mt-2 w-64 rounded-2xl border border-gray-200 bg-white p-3 shadow-theme-lg dark:border-gray-800 dark:bg-[#1E2635]"
                        style="display: none;">
                        <ul class="flex flex-col gap-1">
                            <li>
                                <a href="#"
                                    class="flex rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5">
                                    Excel
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5">
                                    PDF
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-200 py-4 dark:border-gray-800">
            <div class="mb-4 flex flex-col gap-2 px-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <!-- Pagination -->
                <div class="flex flex-wrap items-center gap-4 sm:gap-6">
                    <!-- Pagination Size -->
                    <form method="GET">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Show :</span>
                            <select name="per_page" onchange="this.form.submit()"
                                class="h-9 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                            <span class="text-sm text-gray-500 dark:text-gray-400">entries</span>
                        </div>
                    </form>
                </div>

                {{-- Search --}}
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <form>
                        <div class="relative">
                            <span class="pointer-events-none absolute top-1/2 left-4 -translate-y-1/2">
                                <svg class="fill-gray-500 dark:fill-gray-400" width="20" height="20"
                                    viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z"
                                        fill=""></path>
                                </svg>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search..."
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-[42px] w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-4 pl-[42px] text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden xl:w-[300px] dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div x-data="{
            selected: [],
            pageRowIds: @json($data->pluck('id')->map(fn($id) => (int) $id)),
            allSelected: false,
            totalCount: {{ $totalCount }},
            init() {
                this.updateAllSelected()
            },
            updateAllSelected() {
                this.allSelected = this.pageRowIds.every(id => this.selected.includes(id));
            },
            toggleSingle(id) {
                const index = this.selected.indexOf(id);
                if (index > -1) {
                    this.selected.splice(index, 1);
                } else {
                    this.selected.push(id);
                }
                this.updateAllSelected();
            },
            toggleRow(id, event) {
                if (event.target.closest('td').cellIndex === 6) return;
                this.toggleSingle(id);
            },
            togglePageSelection() {
                if (this.pageRowIds.every(id => this.selected.includes(id))) {
                    this.selected = this.selected.filter(id => !this.pageRowIds.includes(id));
                } else {
                    this.selected = [...new Set(this.selected.concat(this.pageRowIds))];
                }
                this.updateAllSelected();
            },
            selectAllData() {
                this.allSelected = true;
                this.selected = [];
            }
        }" x-init="init()">
            <div class="custom-scrollbar overflow-x-auto w-full px-4 sm:px-6">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    {{-- Table Header --}}
                    <thead class="border-y border-gray-100 py-3 dark:border-gray-800">
                        <tr>
                            <th class="px-4 py-3">
                                <input type="checkbox" :checked="pageRowIds.every(id => selected.includes(id))"
                                    @change="togglePageSelection()" />
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                ID</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                Name</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                Username</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                Email</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                Roles</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                Action</th>
                        </tr>
                    </thead>
                    {{-- End Table Header --}}
                    {{-- Table Body --}}
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($data as $user)
                            @php $id = (int) $user['id']; @endphp
                            <tr :class="selected.includes({{ $id }}) ? 'bg-gray-100 dark:bg-gray-800' : ''"
                                class="duration-200 cursor-pointer" @click="toggleRow({{ $id }}, $event)"
                                @dblclick="window.location.href = '/admin/user/{{ $id }}/edit'">
                                <td class="px-4 py-3">
                                    <input type="checkbox" :value="{{ $id }}"
                                        :checked="selected.includes({{ $id }})"
                                        @click.stop="toggleSingle({{ $id }})" />
                                </td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap">
                                    {{ $user['id'] }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">
                                    {{ $user['name'] }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[150px]">
                                    {{ $user['username'] }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap min-w-[200px]">
                                    {{ $user['email'] }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap">
                                    {{ $user['roles'] }}</td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap">
                                    <div class="col-span-1 flex items-center px-4 py-3">
                                        <div class="flex w-full items-center gap-2">
                                            {{-- Delete --}}
                                            <form action="{{ route('admin.user.destroy', $user['id']) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white/90">
                                                    <svg class="fill-current" width="21" height="21"
                                                        viewBox="0 0 21 21" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M7.04142 4.29199C7.04142 3.04935 8.04878 2.04199 9.29142 2.04199H11.7081C12.9507 2.04199 13.9581 3.04935 13.9581 4.29199V4.54199H16.1252H17.166C17.5802 4.54199 17.916 4.87778 17.916 5.29199C17.916 5.70621 17.5802 6.04199 17.166 6.04199H16.8752V8.74687V13.7469V16.7087C16.8752 17.9513 15.8678 18.9587 14.6252 18.9587H6.37516C5.13252 18.9587 4.12516 17.9513 4.12516 16.7087V13.7469V8.74687V6.04199H3.8335C3.41928 6.04199 3.0835 5.70621 3.0835 5.29199C3.0835 4.87778 3.41928 4.54199 3.8335 4.54199H4.87516H7.04142V4.29199ZM15.3752 13.7469V8.74687V6.04199H13.9581H13.2081H7.79142H7.04142H5.62516V8.74687V13.7469V16.7087C5.62516 17.1229 5.96095 17.4587 6.37516 17.4587H14.6252C15.0394 17.4587 15.3752 17.1229 15.3752 16.7087V13.7469ZM8.54142 4.54199H12.4581V4.29199C12.4581 3.87778 12.1223 3.54199 11.7081 3.54199H9.29142C8.87721 3.54199 8.54142 3.87778 8.54142 4.29199V4.54199ZM8.8335 8.50033C9.24771 8.50033 9.5835 8.83611 9.5835 9.25033V14.2503C9.5835 14.6645 9.24771 15.0003 8.8335 15.0003C8.41928 15.0003 8.0835 14.6645 8.0835 14.2503V9.25033C8.0835 8.83611 8.41928 8.50033 8.8335 8.50033ZM12.9168 9.25033C12.9168 8.83611 12.581 8.50033 12.1668 8.50033C11.7526 8.50033 11.4168 8.83611 11.4168 9.25033V14.2503C11.4168 14.6645 11.7526 15.0003 12.1668 15.0003C12.581 15.0003 12.9168 14.6645 12.9168 14.2503V9.25033Z"
                                                            fill=""></path>
                                                    </svg>
                                                </button>
                                            </form>

                                            {{-- Edit --}}
                                            <button
                                                @click.stop="window.location.href = '/admin/users/{{ $user['id'] }}/edit'"
                                                class="text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white/90">
                                                <svg class="fill-current" width="21" height="21"
                                                    viewBox="0 0 21 21" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M17.0911 3.53206C16.2124 2.65338 14.7878 2.65338 13.9091 3.53206L5.6074 11.8337C5.29899 12.1421 5.08687 12.5335 4.99684 12.9603L4.26177 16.445C4.20943 16.6931 4.286 16.9508 4.46529 17.1301C4.64458 17.3094 4.90232 17.3859 5.15042 17.3336L8.63507 16.5985C9.06184 16.5085 9.45324 16.2964 9.76165 15.988L18.0633 7.68631C18.942 6.80763 18.942 5.38301 18.0633 4.50433L17.0911 3.53206ZM14.9697 4.59272C15.2626 4.29982 15.7375 4.29982 16.0304 4.59272L17.0027 5.56499C17.2956 5.85788 17.2956 6.33276 17.0027 6.62565L16.1043 7.52402L14.0714 5.49109L14.9697 4.59272ZM13.0107 6.55175L6.66806 12.8944C6.56526 12.9972 6.49455 13.1277 6.46454 13.2699L5.96704 15.6283L8.32547 15.1308C8.46772 15.1008 8.59819 15.0301 8.70099 14.9273L15.0436 8.58468L13.0107 6.55175Z"
                                                        fill=""></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </td>

                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                    {{-- End Table Body --}}
                </table>
                {{-- @if ($totalCount > count($data))
                    <div
                        class="px-4 sm:px-6 py-2 text-sm text-gray-600 dark:text-gray-300 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 mt-2">
                        <template x-if="!allSelected">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none"
                                    stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.5 9.75L7.5 18m0 0H16.5m-9 0V6" />
                                </svg>
                                <span>
                                    {{ count($data) }} selected.
                                    <button @click.prevent="selectAllData()"
                                        class="text-blue-600 hover:underline font-medium">
                                        Select all {{ $totalCount }} users
                                    </button>
                                </span>
                            </div>
                        </template>

                        <template x-if="allSelected">
                            <div class="flex items-center gap-2 text-green-600 dark:text-green-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>All {{ $totalCount }} users selected.</span>
                            </div>
                        </template>
                    </div>
                @endif --}}

            </div>
        </div>

        {{-- Pagination --}}
        <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-800">
            <div class="flex items-center justify-between">
                {{-- Previous --}}
                @if ($data->onFirstPage())
                    <span
                        class="text-sm cursor-not-allowed rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-400 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-500">
                        Previous
                    </span>
                @else
                    <a href="{{ $data->previousPageUrl() }}"
                        class="text-sm rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                        Previous
                    </a>
                @endif

                {{-- Page Info --}}
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
                    <span
                        class="text-sm cursor-not-allowed rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-400 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-500">
                        Next
                    </span>
                @endif
            </div>
        </div>

    </div>
</x-app-layout>
