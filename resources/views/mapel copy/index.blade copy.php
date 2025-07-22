{{-- @php
$filters = [
    [
        'name' => 'kategori_filter',
        'label' => 'Kategori',
        'options' => $kategori,
        'valueKey' => 'id',
        'labelKey' => 'name',
        'enabled' => true,
    ],
];
@endphp --}}

<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    {{-- Form Tambah Mapel --}}
    @include('mapel.create')
    {{-- Form Edit Mapel --}}
    @include('mapel.edit')

    <!-- Wrapper -->
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
        {{-- <x-tabs :tabs="[
            'mapel' => [
                'label' => 'Mata Pelajaran',
                'content' => view('mapel.partials.tab-mapel')->render()
            ],
            'bab' => [
                'label' => 'Bab',
                'content' => view('mapel.partials.tab-bab')->render()
            ],
            'lingkup_materi' => [
                'label' => 'Lingkup Materi',
                'content' => view('mapel.partials.tab-lingkup-materi')->render
            ],
            'tujuan_pembelajaran' => [
                'label' => 'Tujuan Pembelajaran',
                'content' => view('mapel.partials.tab-tujuan-pembelajaran')->render()
            ],
        ]" active="mapel" /> --}}

        {{-- Toolbar Table --}}
        <x-table.toolbar
            {{-- :filters="$filters" --}}
            :enable-add-button="true"
            :enable-import="true"
            :enable-export="true"
            :enable-search="true"
            :route="role_route('mapel.index')">
            <x-slot name="addButton">
                <button type="button"
                    onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'form-create-mapel' }))"
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
            // 'id' => ['label' => 'ID', 'sortable' => true],
            'kode_mapel' => ['label' => 'Kode Mapel', 'sortable' => true],
            'nama' => ['label' => 'Nama', 'sortable' => true],
            'kategori' => ['label' => 'Kategori', 'sortable' => true],
            // 'action' => ['label' => 'Aksi', 'sortable' => false],
        ]"
            :data="$mapel"
            :total-count="$totalCount"
            row-view="mapel.partials.row"
            :actions="[
            'detail' => false,
            'edit' => true,
            'delete' => true,
        ]"
        :use-modal-edit="true"/>
    </div>

</x-app-layout>
