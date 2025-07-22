{{-- Toolbar Table --}}
        <x-table.toolbar
        {{-- :filters="$filters" --}}
        :enable-add-button="true"
        :enable-import="true"
        :enable-export="true"
        :enable-search="true"
        {{-- :bulkDeleteRoute="role_route('bab.bulk-delete')" --}}
        :route="role_route('bab.index')">
            <x-slot name="addButton">
                <button type="button"
                    onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'form-create-bab' }))"
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
             'no' => ['label' => 'No', 'sortable' => false], // <- nomor urut biasa, tidak perlu di-sort
            // 'id' => ['label' => 'ID', 'sortable' => true],
            'nama' => ['label' => 'Nama', 'sortable' => true],
        ]"
        :data="$bab"
        :total-count="$totalCount"
        row-view="bab.partials.row"
        :actions="[
            'detail' => false,
            'edit' => true,
            'delete' => true]"
        :use-modal-edit="true"
        />
