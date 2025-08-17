<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    {{-- Form Tambah Kelas --}}
    {{-- @include('kelas-siswa.create') --}}

    {{-- Form Tambah Kelas --}}
    {{-- @include('kelas-siswa.edit') --}}

         {{-- Tahun ajaran aktif --}}
        @if($tahunAjaranAktif)
        <div class="mb-4 text-base font-semibold text-brand-600">
            Tahun Ajaran Aktif: {{ $tahunAjaranAktif->tahun }}
        </div>
        @endif
    <!-- Wrapper -->
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
        {{-- Toolbar Table --}}
        <x-table.toolbar
            {{-- :filters="$filters" --}}
            :enable-add-button="true"
            :enable-import="false"
            :enable-export="false"
            :enable-search="true"
            {{-- :route-create="route(role_route('kelas.create'))" --}}
            {{-- :route="role_route('kelas.mapel.index', ['kelas' => $kelas->id])"> --}}
            <x-slot name="addButton">
                <button type="button"
                    onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'form-create-kelas-mapel' }))"
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
        <x-table :columns="[
            'no' => ['label' => 'No', 'sortable' => false],
            // 'id' => ['label' => 'ID', 'sortable' => true],
            // 'nama' => ['label' => 'Nama Kelas', 'sortable' => true],
            'kelas' => ['label' => 'Mapel', 'sortable' => true],
            'wali_kelas' => ['label' => 'Guru', 'sortable' => true],
            // 'action' => ['label' => 'Aksi', 'sortable' => false],
        ]"
        :data="$mapelList"
        :total-count="$totalCount"
        row-view="kelas.mapel.partials.row"
        :actions="[
            'detail' => true,
            'edit' => true,
            'delete' => true,
            'routes' => [
                'detail' => fn($item) => route('kelas.siswa.show', [
                    'kelas' => $kelas->id,
                ]),
                'delete' => fn($item) => route('kelas.siswa.destroy', [
                    'kelas' => $kelas->id,
                ]),
            ],
        ]"
        :use-modal-edit="true"/>
    </div>
</x-app-layout>
