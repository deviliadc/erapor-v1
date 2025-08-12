<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    {{-- Form Tambah Tujuan Pembelajaran --}}
    @include('tujuan-pembelajaran.create')

    {{-- Form Tambah Tujuan Pembelajaran --}}
    @include('tujuan-pembelajaran.edit')

    <!-- Wrapper -->
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
        {{-- Toolbar Table --}}
        <x-table.toolbar
            {{-- :filters="$filters" --}}
            :enable-add-button="true"
            :enable-import="true"
            :enable-export="true"
            :enable-search="true"
            :route="role_route('tujuan-pembelajaran.index')">
            <x-slot name="addButton">
                <button type="button"
                    onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'form-create-tujuan' }))"
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
            'kelas' => ['label' => 'Kelas', 'sortable' => false],
            'mapel' => ['label' => 'Mapel', 'sortable' => false],
            'bab' => ['label' => 'Bab', 'sortable' => false],
            'lingkup_materi' => ['label' => 'Lingkup Materi', 'sortable' => false],
            'subbab' => ['label' => 'Subbab', 'sortable' => false],
            'tujuan_pembelajaran' => ['label' => 'Tujuan Pembelajaran', 'sortable' => false],
            // 'action' => ['label' => 'Action', 'sortable' => false],
        ]" :data="$tujuan_pembelajaran"
            :total-count="$totalCount"
            row-view="tujuan-pembelajaran.partials.row"
            :actions="[
                'detail' => false,
                'edit' => true,
                // 'delete' => true,
                'routes' => [
                    // 'edit' => fn($item) => role_route('tujuan-pembelajaran.edit', ['tujuan_pembelajaran' => $item['id']]),
                    'delete' => fn($item) => role_route('tujuan-pembelajaran.destroy', ['tujuan_pembelajaran' => $item['id']]),
                ],
            ]"
            :use-modal-edit="true" />
    </div>

</x-app-layout>
