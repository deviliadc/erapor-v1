<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    <div class="space-y-6">

        {{-- Detail Lingkup Materi --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Informasi Lingkup Materi</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Kelas</p>
                    {{-- <p class="text-base font-medium text-gray-800 dark:text-white">{{ $lingkupMateri->guruKelas->kelas->nama }}</p> --}}
                    <p class="text-base font-medium text-gray-800 dark:text-white">{{ $lingkupMateri->kelas->nama }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Mata Pelajaran</p>
                    {{-- <p class="text-base font-medium text-gray-800 dark:text-white">{{ $lingkupMateri->guruKelas->mapel->nama }}</p> --}}
                    <p class="text-base font-medium text-gray-800 dark:text-white">{{ $lingkupMateri->mapel->nama }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Bab</p>
                    <p class="text-base font-medium text-gray-800 dark:text-white">{{ $lingkupMateri->bab->nama }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Lingkup Materi</p>
                    <p class="text-base font-medium text-gray-800 dark:text-white">{{ $lingkupMateri->nama }}</p>
                </div>
            </div>
        </div>

        {{-- Tabel Tujuan Pembelajaran --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Daftar Tujuan Pembelajaran</h2>
            </div>

            <x-table.toolbar
                :enable-add-button="true"
                :enable-import="false"
                :enable-export="false"
                :enable-search="true"
                :route="role_route('tujuan-pembelajaran.create')">
                <x-slot name="addButton">
                    <button type="button"
                        onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'form-create-tujuan' }))"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                        Tambah
                        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M9.25 5a.75.75 0 011.5 0v4.25H15a.75.75 0 010 1.5h-4.25V15a.75.75 0 01-1.5 0v-4.25H5a.75.75 0 010-1.5h4.25V5z" />
                        </svg>
                    </button>
                </x-slot>
            </x-table.toolbar>

            {{-- Modal Tambah Tujuan Pembelajaran --}}
            <x-modal name="form-create-tujuan" title="Tambah Tujuan Pembelajaran" maxWidth="2xl">
                <form method="POST" action="{{ role_route('tujuan-pembelajaran.store') }}" class="space-y-6 sm:p-6">
                    @csrf
                    <input type="hidden" name="lingkup_materi_id" value="{{ $lingkupMateri->id }}">
                    <input type="hidden" name="redirect_to" value="{{ url()->current() }}">

                    <x-form.input
                        name="subbab"
                        label="Subbab"
                        required />

                    <x-form.textarea
                        name="tujuan"
                        label="Tujuan Pembelajaran"
                        required />

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
                    'subbab' => ['label' => 'Subbab', 'sortable' => false],
                    'tujuan' => ['label' => 'Tujuan Pembelajaran', 'sortable' => false],
                ]"
                :data="$tujuan_pembelajaran"
                :total-count="$totalCount"
                row-view="lingkup-materi.partials.row-detail"
                :actions="[
                    'edit' => true,
                    'delete' => true,
                    // 'editRoute' => role_route('tujuan-pembelajaran.edit'),
                ]"
                :use-modal-edit="true"
            />

            {{-- Modal Edit Tujuan Pembelajaran --}}
            @foreach ($tujuan_pembelajaran as $item)
                <x-modal name="edit-modal-{{ $item['id'] }}" title="Edit Tujuan Pembelajaran" maxWidth="2xl">
                    <form method="POST" action="{{ role_route('tujuan-pembelajaran.update', ['tujuan_pembelajaran' => $item['id']]) }}" class="space-y-6 sm:p-6">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="lingkup_materi_id" value="{{ $lingkupMateri->id }}">
                        <input type="hidden" name="redirect_to" value="{{ url()->current() }}">

                        <x-form.input
                            name="subbab"
                            label="Subbab"
                            value="{{ $item['subbab'] }}"
                            required />

                        <x-form.textarea
                            name="tujuan"
                            label="Tujuan Pembelajaran"
                            value="{{ $item['tujuan_pembelajaran'] }}"
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
