{{-- filepath: d:\DEVI\DRAFT\erapor-v1\resources\views\arsip\siswa.blade.php --}}
<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
        <x-table.toolbar
            :enable-add-button="false"
            :enable-import="false"
            :enable-export="true"
            :enable-search="true"
            :routeExport="role_route('arsip-siswa.export')"
            :exportFormats="['excel']"
            filename="arsip_siswa_{{ now()->format('Ymd_His') }}"
        />

        <x-table :columns="[
            'no' => ['label' => 'No', 'sortable' => false],
            'name' => ['label' => 'Nama', 'sortable' => true],
            'nipd' => ['label' => 'NIPD', 'sortable' => true],
            'nisn' => ['label' => 'NISN', 'sortable' => true],
            'jenis_kelamin' => ['label' => 'Jenis Kelamin', 'sortable' => true],
            // 'kelas' => ['label' => 'Kelas', 'sortable' => true],
            'status' => ['label' => 'Status', 'sortable' => true],
        ]"
        :data="$siswa"
        :total-count="$totalCount"
        row-view="arsip.partials.siswa-row"
        :actions="[
            'detail' => true,
            'edit' => false,
            'delete' => false,
            'routes' => [
                'detail' => fn($item) => role_route('siswa.show', ['siswa' => $item['id']]),
                // 'edit' => fn($item) => role_route('siswa.edit', ['siswa' => $item['id']]),
                // 'delete' => fn($item) => role_route('siswa.destroy', ['siswa' => $item['id']]),
            ]
        ]"/>
    </div>
</x-app-layout>