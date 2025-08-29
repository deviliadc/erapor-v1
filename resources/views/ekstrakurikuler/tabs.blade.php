{{-- @php
$filters = [
    [
        'name' => 'tahun_semester_id',
        'label' => 'Pilih Tahun Ajaran Semester',
        'options' => $tahunSemesterList->map(function ($item) {
            return [
                'id' => $item->id,
                'label' => $item->tahun . ' - Semester ' . ucfirst($item->semester) . ($item->is_active ? ' (Aktif)' : ''),
            ];
        }),
        'valueKey' => 'id',
        'labelKey' => 'label',
        'enabled' => true,
    ],
];
@endphp --}}
@php
    $isGuru = auth()->user()->hasRole('guru');
    // $routePrefix = auth()->user()->hasRole('admin') ? 'admin.' : (auth()->user()->hasRole('guru') ? 'guru.' : '');
@endphp

{{-- Form Tambah Ekstrakurikuler --}}
@include('ekstrakurikuler.create')
{{-- Form Edit Ekstrakurikuler --}}
@include('ekstrakurikuler.edit')

<!-- Wrapper -->
{{-- <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]"> --}}
    {{-- Toolbar Table --}}
    <x-table.toolbar
        {{-- :filters="$filters" --}}
        :enable-add-button="!$isGuru"
        :enable-import="false"
        :enable-export="false"
        :enable-search="true"
        searchName="search_ekstrakurikuler"
        tabName="ekstra"
        :route="role_route('ekstrakurikuler.index')">
        <x-slot name="addButton">
            <button type="button"
                onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'form-create-ekstra' }))"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                Tambah
                <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M9.25 5a.75.75 0 011.5 0v4.25H15a.75.75 0 010 1.5h-4.25V15a.75.75 0 01-1.5 0v-4.25H5a.75.75 0 010-1.5h4.25V5z" />
                </svg>
            </button>
        </x-slot>
    </x-table.toolbar>

    {{-- Table --}}
    <x-table :columns="[
        'no' => ['label' => 'No', 'sortable' => false],
        // 'id' => ['label' => 'ID', 'sortable' => false],
        'nama' => ['label' => 'Nama', 'sortable' => false],
        'jumlah_parameter' => ['label' => 'Jumlah Parameter', 'sortable' => false],
        // 'kelas' => ['label' => 'Kelas', 'sortable' => false],
        // 'action' => ['label' => 'Aksi', 'sortable' => false],
    ]" :data="$ekstra" :total-count="$ekstraTotal" row-view="ekstrakurikuler.partials.row"
        :actions="[
            'detail' => true,
            'edit' => !$isGuru,
            'delete' => !$isGuru,
            // 'routes' => [
            //     'detail' => fn($item) => route('ekstra.show', $item['id']),
            //     'delete' => fn($item) => route('ekstra.destroy', $item['id']),
            // ],
            'routes' => [
                'detail' => fn($item) => role_route('ekstra.show', ['ekstra' => $item['id']]),
                'delete' => fn($item) => role_route('ekstra.destroy', ['ekstra' => $item['id']]),
            ],
            // 'editRoute' => role_route('ekstrakurikuler.edit'),
        ]" :use-modal-edit="true" />
{{-- </div> --}}
