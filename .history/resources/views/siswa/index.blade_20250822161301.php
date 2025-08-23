@php
    $isGuru = auth()->user()->hasRole('guru');
@endphp

<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    {{-- Modal Import Excel --}}
    @include('siswa.import')

    <!-- Wrapper -->
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

        {{-- Toolbar Table --}}
        <x-table.toolbar
            :enable-add-button="auth()->user()->hasRole('admin') || auth()->user()->hasRole('guru')"
            {{-- :enable-import="auth()->user()->hasRole('admin') || auth()->user()->hasRole('guru')" --}}
            importModalName="import-siswa"
            :enable-export="false"
            :enable-search="true"
            :routeCreate="role_route('siswa.create')"
            :routeExport="role_route('siswa.export')"
            :exportFormats="['excel']"
            filename="data_siswa_{{ now()->format('Ymd_His') }}"
        />

        {{-- Table --}}
        <x-table :columns="[
            'no' => ['label' => 'No', 'sortable' => false],
            'name' => ['label' => 'Nama', 'sortable' => true],
            'nipd' => ['label' => 'NIPD', 'sortable' => true],
            'nisn' => ['label' => 'NISN', 'sortable' => true],
            'tempat_lahir' => ['label' => 'Tempat Lahir', 'sortable' => true],
            'tanggal_lahir' => ['label' => 'Tanggal Lahir', 'sortable' => true],
            'jenis_kelamin' => ['label' => 'Jenis Kelamin', 'sortable' => true],
            'nama_ayah' => ['label' => 'Nama Ayah', 'sortable' => true],
            'nama_ibu' => ['label' => 'Nama Ibu', 'sortable' => true],
            'nama_wali' => ['label' => 'Nama Wali', 'sortable' => true],
            'alamat' => ['label' => 'Alamat', 'sortable' => true],
            'no_hp' => ['label' => 'No HP', 'sortable' => true],
            'no_hp_wali' => ['label' => 'No HP Wali', 'sortable' => true],
            'email' => ['label' => 'Email', 'sortable' => true],
            // 'kelas' => ['label' => 'Kelas', 'sortable' => true],
            'status' => ['label' => 'Status', 'sortable' => true],
        ]"
        :data="$siswa"
        :total-count="$totalCount"
        row-view="siswa.partials.row"
        :actions="[
            'detail' => true,
            'edit' => true,
            'delete' => !$isGuru,
            'routes' => [
                'edit' => fn($item) => role_route('siswa.edit', ['siswa' => $item['id']]),
                'detail' => fn($item) => role_route('siswa.show', ['siswa' => $item['id']]),
                'delete' => fn($item) => role_route('siswa.destroy', ['siswa' => $item['id']]),
            ]
        ]"/>
    </div>
</x-app-layout>
