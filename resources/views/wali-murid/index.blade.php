@php
    // $routePrefix = auth()->user()->hasRole('admin') ? 'admin.' : (auth()->user()->hasRole('guru') ? 'guru.' : '');
    $isGuru = auth()->user()->hasRole('guru');
@endphp

<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    {{-- Modal Form Create Wali Murid --}}
    @include('wali-murid.create')

    {{-- Modal Form Edit Wali Murid --}}
    @include('wali-murid.edit')

    <!-- Wrapper -->
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
        {{-- Toolbar Table --}}
        <x-table.toolbar
            :enable-add-button="auth()->user()->hasRole('admin') || auth()->user()->hasRole('guru')"
            :enable-search="true"
            :enable-import="false"
            :enable-export="false"
            :route="role_route('wali-murid.index')">
            <x-slot name="addButton">
                <button type="button"
                    onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'form-create-wali-murid' }))"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5
                    text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                    Tambah
                    <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M9.25 5a.75.75 0 011.5 0v4.25H15a.75.75 0 010 1.5h-4.25V15a.75.75 0 01-1.5 0v-4.25H5a.75.75 0 010-1.5h4.25V5z" />
                    </svg>
                </button>
            </x-slot>
        </x-table.toolbar>

        {{-- Table --}}
        <x-table
        :columns="[
            'no' => ['label' => 'No', 'sortable' => false],
            'nama_ayah' => ['label' => 'Nama Ayah', 'sortable' => true],
            'nama_ibu' => ['label' => 'Nama Ibu', 'sortable' => true],
            'nama_wali' => ['label' => 'Nama Wali (Opsional)', 'sortable' => true],
            'jumlah_anak' => ['label' => 'Jumlah Anak', 'sortable' => true],
            'no_hp' => ['label' => 'No. HP', 'sortable' => true],
            'pekerjaan_ayah' => ['label' => 'Pekerjaan Ayah', 'sortable' => true],
            'pekerjaan_ibu' => ['label' => 'Pekerjaan Ibu', 'sortable' => true],
            'pekerjaan_wali' => ['label' => 'Pekerjaan Wali (Opsional)', 'sortable' => true],
            'alamat' => ['label' => 'Alamat Lengkap', 'sortable' => false],
        ]"
        :data="$wali_murid"
        :total-count="$totalCount"
        row-view="wali-murid.partials.row"
        :actions="[
            'detail' => true,
            'edit' => true,
            'delete' => !$isGuru,
            'routes' => [
                'detail' => fn($item) => role_route('wali-murid.show', ['wali_murid' => $item['id']]),
                // 'edit' => fn($item) => role_route('wali-murid.edit', ['wali_murid' => $item['id']]),
                'delete' => fn($item) => role_route('wali-murid.destroy', ['wali_murid' => $item['id']]),
            ]
        ]"
        :use-modal-edit="true"/>
    </div>
</x-app-layout>
