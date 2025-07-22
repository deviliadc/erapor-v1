@php
$filters = [
    [
        'name' => 'role_filter',
        'label' => 'Role',
        'options' => $roles,
        'valueKey' => 'id',
        'labelKey' => 'name',
        'enabled' => true,
    ],
    // kalau mau tambah filter lain, tambahkan di sini
];
@endphp

<x-app-layout>
    {{-- Breadcrumb --}}
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />
    <!-- Wrapper -->
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
        {{-- Toolbar Table --}}
        <x-table.toolbar
            :filters="$filters"
            :enable-add-button="true"
            :enable-import="true"
            :enable-export="true"
            :enable-search="true"
            :route-create="route('admin.user.create')"
        />

        {{-- Table --}}
        <x-table :columns="['ID', 'Name', 'Username', 'Email', 'Roles', 'Action']" :data="$users" :total-count="$totalCount" row-view="admin.user.partials.row" />
    </div>

</x-app-layout>
