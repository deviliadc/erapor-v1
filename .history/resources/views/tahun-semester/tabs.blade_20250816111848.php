    {{-- Form Tambah Tahun Semester --}}
    @include('tahun-semester.create')
    {{-- Form Edit Tahun Semester --}}
    @include('tahun-semester.edit')

    {{-- Toolbar Table --}}
        <x-table.toolbar
            {{-- :filters="$filters" --}}
            :enable-add-button="true"
            :enable-import="false"
            :enable-export="false"
            :enable-search="true"
            :route="role_route('tahun-semester.index')">
            <x-slot name="addButton">
                <button type="button"
                    onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'form-create-tahun' }))"
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
            'tahun' => ['label' => 'Tahun', 'sortable' => true],
            'semester' => ['label' => 'Semester', 'sortable' => true],
            'status' => ['label' => 'Status', 'sortable' => false],
            // 'siswa_count' => ['label' => 'Jumlah Siswa', 'sortable' => false],
            ]"
            :data="$tahunSemester"
            :total-count="$tahunSemesterTotal"
            row-view="tahun-semester.partials.row"
            :selectable="false"
            :actions="[
            'edit' => true,
            'delete' => true,
            'routes' => [
                'detail' => fn($item) => role_route(['tahun-semester.show', ['tahun_semester' => $item['id']]]),
                'delete' => fn($item) => role_route(['tahun-semester.destroy', ['tahun_semester' => $item['id']]]),
            ],
            ]"
            :use-modal-edit="true"
        />