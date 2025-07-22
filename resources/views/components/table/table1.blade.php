@props([
    'columns' => [],
    'data' => [],
    'totalCount' => 0,
    'rowView' => null,
])

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
    },
    sortBy: '',
    sortDirection: 'asc',
    setSort(column) {
        if (this.sortBy === column) {
            this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            this.sortBy = column;
            this.sortDirection = 'asc';
        }
        this.fetchSortedData();
    },
    confirmDeleteAll() {
        if (confirm('Yakin ingin menghapus semua data yang dipilih?')) {
            $el.querySelector('form').submit();
        }
    },
}" x-init="init()">
    <div class="custom-scrollbar overflow-x-auto w-full px-4 sm:px-6">
        {{-- Tombol Hapus Semua --}}
        <div x-show="selected.length > 0" x-transition
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
                    class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white hover:bg-red-700">
                    Hapus Semua (<span x-text="selected.length"></span>)
                </button>
            </form>

            <button @click="selected = []"
                class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">
                Batal Pilih
            </button>
        </div>

        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
            {{-- Table Header --}}
            <thead class="border-y border-gray-100 py-3 dark:border-gray-800">
                <tr>
                    <th class="px-4 py-3  text-left">
                        <input type="checkbox" :checked="pageRowIds.every(id => selected.includes(id))"
                            @change="togglePageSelection()" />
                    </th>
                    @foreach ($columns as $column)
                        <th
                            class="px-4 py-3 text-left text-sm font-medium text-gray-900 dark:text-gray-400 whitespace-nowrap">
                            {{ $column }}
                        </th>
                    @endforeach
                    {{-- @foreach ($columns as $columnKey => $column)
    <th
        class="px-4 py-3 text-left text-sm font-medium text-gray-900 dark:text-gray-400 whitespace-nowrap cursor-pointer"
        @click="setSort('{{ $columnKey }}')"
    >
        {{ $column }}
        <span x-show="sortBy === '{{ $columnKey }}'">
            <template x-if="sortDirection === 'asc'">▲</template>
            <template x-if="sortDirection === 'desc'">▼</template>
        </span>
    </th>
@endforeach --}}
                </tr>
            </thead>
            {{-- Table Body --}}
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @if ($data->isEmpty())
                    <tr>
                        <td colspan="{{ count($columns) + 1 }}"
                            class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                            Tidak ada data
                        </td>
                    </tr>
                @else
                    @foreach ($data as $item)
                        {{-- @php $id = (int) $item['id']; @endphp
                        <tr :class="selected.includes({{ $id }}) ? 'bg-gray-100 dark:bg-gray-800' : ''"
                            class="duration-200 cursor-pointer" @click="toggleRow({{ $id }}, $event)"
                            @dblclick="window.location.href = '{{ url()->current() }}/{{ $id }}/edit'"> --}}
                        @php
                            $id = (int) $item['id'];
                            $currentRoute = Route::currentRouteName();
                            $showRouteName = Str::replaceLast('.index', '.show', $currentRoute);
                            $showRoute = Route::has($showRouteName);
                        @endphp

                        <tr :class="selected.includes({{ $id }}) ? 'bg-gray-100 dark:bg-gray-800' : ''"
                            class="duration-200 cursor-pointer" @click="toggleRow({{ $id }}, $event)"
                            @dblclick="{{ $showRoute
                                ? "window.location.href = '" . route($showRouteName, $id) . "'"
                                : "window.location.href = '" . route($currentRoute) . "'" }}">

                            <td class="px-4 py-3">
                                <input type="checkbox" :value="{{ $id }}"
                                    :checked="selected.includes({{ $id }})"
                                    @click.stop="toggleSingle({{ $id }})" />
                            </td>

                            {{-- Render row --}}
                            @if ($rowView)
                                @include($rowView, ['item' => $item])
                            @else
                                {{-- Fallback --}}
                                @foreach ($item as $value)
                                    <td class="px-4 py-3 text-gray-700 dark:text-gray-400 whitespace-nowrap">
                                        {{ $value }}
                                    </td>
                                @endforeach
                            @endif
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

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
