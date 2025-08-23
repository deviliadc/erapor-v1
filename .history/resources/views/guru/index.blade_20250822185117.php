

<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />
    <!-- Wrapper -->

    {{-- Form Tambah Guru --}}
    @include('guru.create')
    {{-- Form Edit Guru --}}
    @include('guru.edit')
    {{-- Modal Import Excel --}}
    @include('guru.import')

    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
        {{-- Toolbar Table --}}
        <x-table.toolbar
            {{-- :filters="$filters" --}}
            :enable-add-button="true"
            :enable-import="true"
            importModalName="import-guru"
            :enable-export="true"
            :enable-search="true"
            {{-- :route-create="role_route('guru.create')" --}}
            {{-- :route="role_route('guru.index')" --}}
            :routeExport="role_route('guru.export')"
            :exportFormats="['excel']"
            filename="data_guru_{{ now()->format('Ymd_His') }}">
            <x-slot name="addButton">
                <button type="button"
                    onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'form-create-guru' }))"
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
            'name' => ['label' => 'Nama', 'sortable' => true],
            'nuptk' => ['label' => 'NUPTK', 'sortable' => true],
            'nip' => ['label' => 'NIP', 'sortable' => true],
            'email' => ['label' => 'Email', 'sortable' => true],
            'no_hp' => ['label' => 'No HP', 'sortable' => true],
            // 'alamat' => ['label' => 'Alamat', 'sortable' => true],
            // 'jenis_kelamin' => ['label' => 'Jenis Kelamin', 'sortable' => true],
            'status' => ['label' => 'Status', 'sortable' => true],
            // 'action' => ['label' => 'Aksi', 'sortable' => false],
        ]"
        :data="$guru"
        :total-count="$totalCount"
        row-view="guru.partials.row"
        :actions="[
            'edit' => true,
            'delete' => true,
            // 'editRoute' => role_route('guru.edit')
        ]"
        :use-modal-edit="true"/>
    </div>

</x-app-layout>
