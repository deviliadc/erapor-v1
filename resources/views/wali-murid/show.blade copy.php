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
            :enable-add-button="false"
            :enable-search="true"
            :enable-import="false"
            :enable-export="false"
            :route="role_route('wali-murid.show', ['wali_murid' => $wali_murid->id])">
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
