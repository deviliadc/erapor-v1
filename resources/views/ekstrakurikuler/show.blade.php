@php
    $isGuru = auth()->user()->hasRole('guru');
@endphp

<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    <div class="space-y-6">

        {{-- Detail Ekstrakurikuler --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Informasi Ekstrakurikuler</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Nama</p>
                    <p class="text-base font-medium text-gray-800 dark:text-white">{{ $ekstra->nama }}</p>
                </div>
            </div>
        </div>

        {{-- Tabel Parameter Ekstra --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Daftar Parameter Ekstrakurikuler</h2>
            </div>

            <x-table.toolbar
                :enable-add-button="!$isGuru"
                :enable-import="false"
                :enable-export="false"
                :enable-search="true"
                :route="role_route('param-ekstra.create')">
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

            {{-- Modal Tambah Parameter Ekstra --}}
            <x-modal name="form-create-parameter" title="Tambah Parameter Ekstra" maxWidth="2xl">
                <form method="POST" action="{{ role_route('param-ekstra.store') }}" class="space-y-6 sm:p-6">
                    @csrf
                    <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
                    <input type="hidden" name="ekstra_id" value="{{ $ekstra->id }}">

                    {{-- Parameter Ekstrakurikuler --}}
                    <x-form.textarea name="parameter" label="Nama Parameter" required />

                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                            Simpan
                        </button>
                    </div>
                </form>
            </x-modal>

            <x-table
                :columns="[
                    'no' => ['label' => 'No', 'sortable' => false],
                    'parameter' => ['label' => 'Parameter', 'sortable' => false],
                ]"
                :data="$param_ekstra"
                :total-count="$totalCount"
                row-view="param-ekstra.partials.row-detail"
                :actions="[
                    // 'detail' => false,
                    'edit' => !$isGuru,
                    'delete' => !$isGuru,
                    // 'editRoute' => role_route('tujuan-pembelajaran.edit'),
                    'routes' => [
                        'delete' => fn($item) => role_route('param-ekstra.destroy', ['param_ekstra' => $item['id']]),
                    ],
                ]"
                :use-modal-edit="true"
            />

            @foreach ($param_ekstra as $item)
                <x-modal name="edit-modal-{{ $item['id'] }}" title="Edit Parameter Ekstrakurikuler" maxWidth="2xl">
                    <form method="POST" action="{{ role_route('param-ekstra.update', ['param_ekstra' => $item['id']]) }}" class="space-y-6 sm:p-6">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
                        <input type="hidden" name="ekstra_id" value="{{ $ekstra->id }}">

                        <x-form.textarea
                            name="parameter"
                            label="Parameter Ekstrakurikuler"
                            :value="old('parameter', $item['parameter'])"
                            required />

                        <div class="flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                                Update
                            </button>
                        </div>
                    </form>
                </x-modal>
            @endforeach
        </div>
    </div>
</x-app-layout>
