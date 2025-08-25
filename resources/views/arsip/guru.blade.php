{{-- filepath: d:\DEVI\DRAFT\erapor-v1\resources\views\arsip\guru.blade.php --}}
<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
        <x-table.toolbar
            :enable-add-button="false"
            :enable-import="false"
            :enable-export="true"
            :enable-search="true"
            :routeExport="role_route('arsip-guru.export')"
            :exportFormats="['excel']"
            filename="arsip_guru_{{ now()->format('Ymd_His') }}"
        />

        <x-table :columns="[
            'no' => ['label' => 'No', 'sortable' => false],
            'name' => ['label' => 'Nama', 'sortable' => true],
            'nuptk' => ['label' => 'NUPTK', 'sortable' => true],
            'nip' => ['label' => 'NIP', 'sortable' => true],
            'email' => ['label' => 'Email', 'sortable' => true],
            'no_hp' => ['label' => 'No HP', 'sortable' => true],
            'status' => ['label' => 'Status', 'sortable' => true],
        ]"
        :data="$guru"
        :total-count="$totalCount"
        row-view="arsip.partials.guru-row"
        :actions="[
            'detail' => false,
            'edit' => false,
            'delete' => false,
        ]"/>
    </div>
</x-app-layout>
