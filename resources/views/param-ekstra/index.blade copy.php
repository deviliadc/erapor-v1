@php
    $isGuru = auth()->user()->hasRole('guru');
@endphp

<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    {{-- Form Tambah Parameter Ekstra --}}
    @include('param-ekstra.create')

    {{-- Form Edit Parameter Ekstra --}}
    @include('param-ekstra.edit')

    <!-- Wrapper -->
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
        {{-- Toolbar Table --}}
        <x-table.toolbar
            {{-- :filters="$filters" --}}
            :enable-add-button="!$isGuru"
            {{-- :enable-import="false"
            :enable-export="false" --}}
            :enable-search="true"
            :route="role_route('param-ekstra.index')">
            <x-slot name="addButton">
                <button type="button"
                    onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'form-create-parameter' }))"
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
            'ekstra' => ['label' => 'Ekstrakurikuler', 'sortable' => false],
            'parameter' => ['label' => 'Parameter', 'sortable' => true],
            // 'action' => ['label' => 'Aksi', 'sortable' => false],
        ]"
            :data="$param_ekstra"
            :total-count="$totalCount"
            row-view="param-ekstra.partials.row"
            :actions="[
            'edit' => !$isGuru,
            'delete' => !$isGuru,
            // 'editRoute' => role_route('param-ekstra.edit'),
            'routes' => [
                'delete' => fn($item) => role_route('param-ekstra.destroy', ['param_ekstra' => $item['id']]),
            ]
        ]"
        :use-modal-edit="true"
        />
    </div>

</x-app-layout>
