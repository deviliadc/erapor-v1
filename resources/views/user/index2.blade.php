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
];
@endphp

<x-app-layout>
    {{-- Breadcrumb --}}
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    {{-- Form Tambah User --}}
    {{-- @include('user.create') --}}
    {{-- Form Edit User --}}
    {{-- @foreach ($users as $user)
        @include('user.edit', ['user' => $user, 'roles' => $roles])
    @endforeach --}}

    <!-- Wrapper -->
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

    {{-- Livewire TableComponent --}}
        <livewire:table-component
            :columns="[
                'no' => ['label' => 'No', 'sortable' => false],
                'name' => ['label' => 'Name', 'sortable' => true],
                'username' => ['label' => 'Username', 'sortable' => true],
                'email' => ['label' => 'Email', 'sortable' => true],
                'roles' => ['label' => 'Roles', 'sortable' => false],
            ]"
            model="\App\Models\User"
            :filters="['role' => $roles]"
            :relation-filters="['role' => 'roles']"
            row-view="user.partials.row"
            :actions="['detail' => false, 'edit' => true, 'delete' => true]"
        />

</x-app-layout>
