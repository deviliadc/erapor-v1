@props([
    'columns' => [],
    'data' => [],
    'totalCount' => 0,
    'rowView' => null,
    'bulkDeleteRoute' => null,
    'selectable' => true,
    'actions' => ['detail' => false, 'edit' => false, 'delete' => false],
    'sortPrefix' => '',
])

<div x-data="tableComponent({{ $selectable ? 'true' : 'false' }}, '{{ $sortPrefix }}')" x-init="init()">

    {{-- Tombol Hapus Semua --}}
    {{-- <div x-show="selected.length > 0" x-transition
        class="flex justify-between items-center gap-4 px-6 py-4 border-t border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900">
        <form method="POST" action="{{ $bulkDeleteRoute ?? '#' }}"
            @submit.prevent="
                if (confirm('Yakin ingin menghapus semua data yang dipilih?')) {
                    $el.querySelector('input[name=ids]').value = JSON.stringify(selected);
                    $el.submit();
                }
            ">
            @csrf
            @method('DELETE')
            <input type="hidden" name="ids" />
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-error-600 px-4 py-2.5 text-sm font-medium text-grey dark:text-white hover:bg-error-700">
                Hapus Semua (<span x-text="selected.length"></span>)
            </button>
        </form>

        <button @click="selected = []"
            class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">
            Batal Pilih
        </button>
    </div> --}}

    {{-- Tabel --}}
    <div class="custom-scrollbar overflow-x-auto w-full px-4 sm:px-6">
        <table class="min-w-full table-auto divide-y divide-gray-200 dark:divide-gray-700 text-sm">
            <thead class="border-y border-gray-100 py-3 dark:border-gray-800 bg-gray-50 dark:bg-gray-900">
                <tr>
                    {{-- Toogle Select --}}
                    @if ($selectable)
                        <th class="px-4 py-3 text-left">
                            <input type="checkbox" :checked="pageRowIds.every(id => selected.includes(id))"
                                @change="togglePageSelection()" />
                        </th>
                    @endif
                    {{-- Kolom Data --}}
                    @foreach ($columns as $key => $config)
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-900 dark:text-gray-400 whitespace-nowrap {{ $config['sortable'] ? 'cursor-pointer' : '' }}"
                            @if ($config['sortable']) @click="setSort('{{ $key }}')" @endif>
                            {{ $config['label'] }}
                            <template x-if="sortBy === '{{ $key }}'">
                                <span x-text="sortDirection === 'asc' ? '▲' : '▼'" class="ml-1"></span>
                            </template>
                        </th>
                    @endforeach
                    {{-- Kolom Aksi --}}
                    {{-- @if ($actions['detail'] ?? (false || $actions['edit'] ?? (false || $actions['delete'] ?? false))) --}}
                    @if (($actions['detail'] ?? false) || ($actions['edit'] ?? false) || ($actions['delete'] ?? false))
                        <th
                            class="px-4 py-3 text-center w-32 text-sm font-medium text-gray-900 dark:text-gray-400 whitespace-nowrap">
                            Aksi
                        </th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @if ($data->isEmpty())
                {{-- @if (empty($data) || (is_array($data) && count($data) === 0)) --}}
                    <tr>
                        @php
                            $showActions =
                                $actions['detail'] ??
                                (false || $actions['edit'] ?? (false || $actions['delete'] ?? false));
                            $colspan = count($columns) + 1 + ($showActions ? 1 : 0); // +1 untuk checkbox kolom
                        @endphp
                        <td colspan="{{ $colspan }}"
                            class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                            Tidak ada data yang bisa ditampilkan
                        </td>
                    </tr>
                @else
                    @foreach ($data as $item)
                        @php
                            $id = (int) $item['id'];
                            $routeName = Route::currentRouteName();
                            $showRoute = Str::replaceLast('.index', '.show', $routeName);
                        @endphp
                        <tr :class="selected.includes({{ $id }}) ? 'bg-gray-100 dark:bg-gray-800' : ''"
                            class="duration-200 cursor-pointer" @click="handleClick({{ $id }}, $event)">
                            {{-- Selectable Checkbox --}}
                            @if ($selectable)
                                <td class="px-4 py-3">
                                    <input type="checkbox" :value="{{ $id }}"
                                        :checked="selected.includes({{ $id }})"
                                        @click.stop="toggleSingle({{ $id }})" />
                                </td>
                            @endif
                            @if ($rowView)
                                @include($rowView, ['item' => $item])
                            @else
                                @foreach ($columns as $key => $label)
                                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300 whitespace-nowrap">
                                        {{ $item[$key] }}
                                    </td>
                                @endforeach
                            @endif
                            {{-- Action --}}
                            {{-- @if ($actions['detail'] ?? (false || $actions['edit'] ?? (false || $actions['delete'] ?? false))) --}}
                            {{-- @if (($actions['detail'] ?? false) || ($actions['edit'] ?? false) || ($actions['delete'] ?? false)) --}}
                            @if (($actions['detail'] ?? false) || ($actions['edit'] ?? false) || ($actions['delete'] ?? false))
                            <td class="px-4 py-3 text-left text-sm font-medium text-gray-900 dark:text-gray-400 whitespace-nowrap"
                                    data-column="action">
                                    <div class="flex justify-center gap-2 items-center">

                                        {{-- Detail --}}
                                        @if ($actions['detail'] ?? false)
                                            <a href="{{ $actions['routes']['detail'] ?? null ? $actions['routes']['detail']($item) : '#' }}"
                                                class="text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white/90">
                                                <x-icons.list-bullet></x-icons.list-bullet>
                                            </a>
                                        @endif

                                        {{-- Edit --}}
                                        @php
                                            $id = is_array($item) ? $item['id'] : $item->id;
                                            $editRoute = $actions['routes']['edit'] ?? null;
                                        @endphp

                                        @if ($actions['edit'] ?? false)
                                            @if (is_callable($editRoute))
                                                {{-- Pakai route function --}}
                                                <a href="{{ $editRoute($item) }}"
                                                    class="text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white/90">
                                                    <x-icons.edit />
                                                </a>
                                            @else
                                                {{-- Pakai modal --}}
                                                <button type="button"
                                                    onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-modal-{{ $id }}' }))"
                                                    class="text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white/90">
                                                    <x-icons.edit />
                                                </button>
                                            @endif
                                        @endif

                                        {{-- Delete --}}
                                        @if ($actions['delete'] ?? false)
                                            @php
                                                $id = is_array($item) ? $item['id'] : $item->id;
                                            @endphp

                                            <div x-data="{ isDeleteModalOpen{{ $id }}: false }">
                                                <button @click="isDeleteModalOpen{{ $id }} = true"
                                                    class="text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white/90">
                                                    <x-icons.trash />
                                                </button>

                                                <x-modal.delete :id="$id" :route="$actions['routes']['delete'] ?? null
                                                    ? $actions['routes']['delete']($item)
                                                    : route(
                                                        Str::replaceLast(
                                                            '.index',
                                                            '.destroy',
                                                            Route::currentRouteName(),
                                                        ),
                                                        $id,
                                                    )"
                                                    title="Yakin ingin menghapus?"
                                                    message="Data yang sudah dihapus tidak dapat dipulihkan."
                                                    confirmText="Hapus" />
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

        {{-- Pagination --}}
    <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-800 pagination-wrapper">
        <div class="flex items-center justify-between">
            {{-- Previous --}}
            @if ($data->onFirstPage())
                <span
                    class="text-sm cursor-not-allowed rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-400 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-500">
                    Previous
                </span>
            @else
                <a href="{{ $data->previousPageUrl() }}{{ request('tab') ? (Str::contains($data->previousPageUrl(), '?') ? '&' : '?') . 'tab=' . request('tab') : '' }}"
                    class="text-sm rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                    Previous
                </a>
            @endif

            <span class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                Page {{ $data->currentPage() }} of {{ $data->lastPage() }}
            </span>

            {{-- Next --}}
            @if ($data->hasMorePages())
                <a href="{{ $data->nextPageUrl() }}{{ request('tab') ? (Str::contains($data->nextPageUrl(), '?') ? '&' : '?') . 'tab=' . request('tab') : '' }}"
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

<script>
    function tableComponent(selectable = true, sortPrefix = '') {
        return {
            selectable: selectable,
            selected: [],
            pageRowIds: @json($data->pluck('id')->map(fn($id) => (int) $id)),
            allSelected: false,
            totalCount: {{ $totalCount }},
            sortBy: '',
            sortDirection: 'asc',
            clickTimer: null,
            clickCount: 0,

            // init() {
            //     this.updateAllSelected();
            // },
            init() {
                this.sortBy = this.getQueryParam(this.sortPrefix ? `sortBy_${this.sortPrefix}` : 'sortBy') || '';
                this.sortDirection = this.getQueryParam(this.sortPrefix ? `sortDirection_${this.sortPrefix}` :
                    'sortDirection') || 'asc';
                this.updateAllSelected();
            },
            getQueryParam(name) {
                const urlParams = new URLSearchParams(window.location.search);
                return urlParams.get(name);
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
            togglePageSelection() {
                if (this.pageRowIds.every(id => this.selected.includes(id))) {
                    this.selected = this.selected.filter(id => !this.pageRowIds.includes(id));
                } else {
                    this.selected = [...new Set(this.selected.concat(this.pageRowIds))];
                }
                this.updateAllSelected();
            },
            setSort(column) {
                if (this.sortBy === column) {
                    this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                } else {
                    this.sortBy = column;
                    this.sortDirection = 'asc';
                }
                this.fetchSortedData();
            },
            // fetchSortedData() {
            //     // fetch(`?sortBy=${this.sortBy}&sortDirection=${this.sortDirection}`)
            //     const query = new URLSearchParams(window.location.search);
            //     query.set(`sortBy_${this.sortPrefix}`, this.sortBy);
            //     query.set(`sortDirection_${this.sortPrefix}`, this.sortDirection);
            //     fetch(`?${query.toString()}`)
            //         .then(res => res.text())
            //         .then(html => {
            //             const parser = new DOMParser();
            //             const doc = parser.parseFromString(html, 'text/html');

            //             const newTbody = doc.querySelector('tbody');
            //             const newPagination = doc.querySelector('.pagination-wrapper');

            //             this.$root.querySelector('tbody').replaceWith(newTbody);
            //             this.$root.querySelector('.pagination-wrapper').replaceWith(newPagination);

            //             // Update pageRowIds dari data baru
            //             this.pageRowIds = Array.from(newTbody.querySelectorAll('tr')).map(row => {
            //                 const checkbox = row.querySelector('input[type=checkbox]');
            //                 return checkbox ? parseInt(checkbox.value) : null;
            //             }).filter(id => id !== null);

            //             // Hapus selected yang tidak ada di halaman ini
            //             this.selected = this.selected.filter(id => this.pageRowIds.includes(id));

            //             // Re-init ulang
            //             this.updateAllSelected();
            //         });
            // },
            fetchSortedData() {
                const query = new URLSearchParams(window.location.search);

                if (this.sortPrefix) {
                    query.set(`sortBy_${this.sortPrefix}`, this.sortBy);
                    query.set(`sortDirection_${this.sortPrefix}`, this.sortDirection);
                    query.set('tab', '{{ request('tab') }}'); // Menjaga tab tetap sama jika ada
                } else {
                    query.set('sortBy', this.sortBy);
                    query.set('sortDirection', this.sortDirection);
                }

                fetch(`?${query.toString()}`)
                    .then(res => res.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');

                        const newTbody = doc.querySelector('tbody');
                        const newPagination = doc.querySelector('.pagination-wrapper');

                        this.$root.querySelector('tbody').replaceWith(newTbody);
                        this.$root.querySelector('.pagination-wrapper').replaceWith(newPagination);

                        this.pageRowIds = Array.from(newTbody.querySelectorAll('tr')).map(row => {
                            const checkbox = row.querySelector('input[type=checkbox]');
                            return checkbox ? parseInt(checkbox.value) : null;
                        }).filter(id => id !== null);

                        this.selected = this.selected.filter(id => this.pageRowIds.includes(id));
                        this.updateAllSelected();
                    });
            },
            handleClick(id, event) {
                const td = event.target.closest('td');
                if (td && td.dataset.column === 'action') return;

                this.clickCount++;
                if (this.clickCount === 1) {
                    this.clickTimer = setTimeout(() => {
                        this.toggleSingle(id);
                        this.clickCount = 0;
                    }, 200);
                } else if (this.clickCount === 2) {
                    clearTimeout(this.clickTimer);
                    // window.location.href = `{{ url()->current() }}/${id}`;
                    this.clickCount = 0;
                }
            },
        }
    }
</script>
