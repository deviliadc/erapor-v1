@php
$filters = [
    [
        'name' => 'role_filter',
        'label' => 'Semua Role',
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
    @include('user.create')
    {{-- Form Edit User --}}
    @foreach ($users as $user)
        @include('user.edit', ['user' => $user, 'roles' => $roles])
    @endforeach

    <!-- Wrapper -->
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
        {{-- Toolbar Table --}}
        <x-table.toolbar
            :filters="$filters"
            :enable-add-button="true"
            :enable-import="true"
            :enable-export="true"
            :enable-search="true"
            {{-- :route-create="role_route('user.create')" --}}
            :route="route('user.index')">
            <x-slot name="addButton">
                <button type="button"
                    onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'form-create-user' }))"
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
        <x-table
        :columns="[
            'no' => ['label' => 'No', 'sortable' => false],
            // 'id' => ['label' => 'ID', 'sortable' => true],
            'name' => ['label' => 'Name', 'sortable' => true],
            'username' => ['label' => 'Username', 'sortable' => true],
            'email' => ['label' => 'Email', 'sortable' => true],
            'roles' => ['label' => 'Roles', 'sortable' => true],
            // 'action' => ['label' => 'Action', 'sortable' => false],
        ]"
        :data="$users"
        :total-count="$totalCount"
        row-view="user.partials.row"
        :actions="[
            'edit' => true,
            'delete' => true,
            // 'editRoute' => role_route('user.edit'),
        ]"
        :use-modal-edit="true"
        />
    </div>

    {{-- Note --}}
    <div class="mt-6 rounded-xl bg-gray-50 dark:bg-white/[0.03] p-4 border border-gray-200 dark:border-gray-700">
        <p class="text-sm font-semibold text-gray-900 dark:text-gray-400 mb-2">
            Note:
        </p>
        <ul class="list-disc pl-5 text-sm text-gray-500 dark:text-gray-400 space-y-1">
            <li>Untuk mengubah role user, silakan edit user tersebut dan pilih role yang diinginkan.</li>
            <li><span class="font-medium">Username guru default</span>: Nama Awal + 4 digit awal NIP (ex: <span class="font-mono">devi2003</span>)</li>
            <li><span class="font-medium">Password guru default</span>: <span class="font-mono">password123</span></li>
            <li><span class="font-medium">Username siswa default</span>: NISN (ex: <span class="font-mono">3245715542</span>)</li>
            <li><span class="font-medium">Password siswa default</span>: Tanggal Lahir (ex: <span class="font-mono">01072025</span>)</li>
        </ul>
    </div>
</x-app-layout>
