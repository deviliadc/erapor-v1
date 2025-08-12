@php
    $isGuru = auth()->user()->hasRole('guru');
@endphp

<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    {{-- Detail Wali Murid --}}
    <div class="mb-4">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
            Nama Wali Murid: {{ $wali_murid->nama_ayah }} & {{ $wali_murid->nama_ibu }}
            @if($wali_murid->nama_wali)
                (Wali: {{ $wali_murid->nama_wali }})
            @endif
        </h2>
    </div>

    <!-- Wrapper -->
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
        {{-- Toolbar Table --}}
        <x-table.toolbar
            :enable-add-button="true"
            :enable-search="true"
            :enable-import="false"
            :enable-export="false"
            :route="role_route('wali-murid.show', ['wali_murid' => $wali_murid->id])">
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
            'nama_anak' => ['label' => 'Nama Anak', 'sortable' => true],
            'nipd' => ['label' => 'NIPD', 'sortable' => true],
            'nisn' => ['label' => 'NISN', 'sortable' => true],
        ]"
        :data="$siswa"
        :total-count="$totalCount"
        row-view="wali-murid.partials.row-detail"
        :actions="[
            'detail' => true,
            'edit' => false,
            'delete' => false,
            'routes' => [
                'detail' => fn($item) => role_route('siswa.show', ['siswa' => $item['id']]),
            ]
        ]"
        :use-modal-edit="true"/>
    </div>
</x-app-layout>
