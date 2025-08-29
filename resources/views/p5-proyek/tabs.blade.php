@php
    $activeTab = request('tab', 'proyek');
@endphp

{{-- Form Tambah Proyek --}}
@include('p5-proyek.create')
{{-- Form Edit Proyek --}}
@include('p5-proyek.edit')

    {{-- Toolbar Table --}}
    <x-table.toolbar
        :enable-add-button="true"
        :enable-import="false"
        :enable-export="false"
        :enable-search="true"
        searchName="search_proyek"
        tabName="proyek"
        :route="role_route('p5.index')">
        <x-slot name="addButton">
            <button type="button"
                onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'form-create-p5-proyek' }))"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                Tambah
                <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M9.25 5a.75.75 0 011.5 0v4.25H15a.75.75 0 010 1.5h-4.25V15a.75.75 0 01-1.5 0v-4.25H5a.75.75 0 010-1.5h4.25V5z" />
                </svg>
            </button>
        </x-slot>
    </x-table.toolbar>

    <x-table :columns="[
        'no' => ['label' => 'No', 'sortable' => false],
        'nama_proyek' => ['label' => 'Nama Proyek', 'sortable' => false],
        'deskripsi_proyek' => ['label' => 'Deskripsi', 'sortable' => false],
        'tahun_semester' => ['label' => 'Tahun', 'sortable' => false],
    ]"
        :data="$proyek"
        :total-count="$proyekTotal"
        row-view="p5-proyek.partials.row"
        :actions="[
            'detail' => true,
            'edit' => true,
            'delete' => true,
            'routes' => [
                'detail' => fn($item) => role_route('p5-proyek.show', ['p5_proyek' => $item['id']]),
                'delete' => fn($item) => role_route('p5-proyek.destroy', ['p5_proyek' => $item['id']]),
            ],
        ]"
        :use-modal-edit="true"
    />

