<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    {{-- Form Tambah Lingkup Materi --}}
    @include('lingkup-materi.create')

    {{-- Form Tambah Lingkup Materi --}}
    @include('lingkup-materi.edit')

    <!-- Wrapper -->
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
        {{-- Toolbar Table --}}
        <x-table.toolbar
            {{-- :filters="$filters" --}}
            :enable-add-button="true"
            :enable-import="true"
            :enable-export="true"
            :enable-search="true"
            :route-create="role_route('lingkup-materi.index')">
            <x-slot name="addButton">
                <button type="button"
                    onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'form-create-lingkup-materi' }))"
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
            'kelas' => ['label' => 'Kelas', 'sortable' => true],
            'mapel' => ['label' => 'Mapel', 'sortable' => true],
            'bab' => ['label' => 'Bab', 'sortable' => true],
            'nama' => ['label' => 'Lingkup Materi', 'sortable' => true],
            'jumlah_tujuan' => ['label' => 'Jumlah Tujuan', 'sortable' => false],
            // 'action' => ['label' => 'Aksi', 'sortable' => false],
        ]"
        :data="$lingkup_materi"
        :total-count="$totalCount"
        row-view="lingkup-materi.partials.row"
        :actions="[
            'detail' => true,
            'edit' => true,
            'delete' => true,
            // 'editRoute' => role_route('lingkup-materi.edit'),
        ]"
        :use-modal-edit="true"/>
    </div>

</x-app-layout>
