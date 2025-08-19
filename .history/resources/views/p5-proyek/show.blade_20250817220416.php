<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    @include('p5-proyek.create-detail')
    {{-- @include('p5-proyek.edit-detail') --}}
    {{-- @include('p5-proyek.edit-detail', [
        'p5_proyek_detail' => $proyek_detail,
        'p5_proyek_id' => $p5_proyek_id,
        'dimensiList' => $dimensiList,
        'elemenList' => $elemenList,
        'subElemenList' => $subElemenList,
    ]) --}}

    <div class="space-y-6">

        {{-- Informasi Proyek --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Informasi Proyek</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Nama Proyek</p>
                    <p class="text-base font-medium text-gray-800 dark:text-white">{{ $proyek->nama_proyek }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Deskripsi</p>
                    <p class="text-base font-medium text-gray-800 dark:text-white">{{ $proyek->deskripsi }}</p>
                </div>
                {{-- <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Tema</p>
                    <p class="text-base font-medium text-gray-800 dark:text-white">{{ $proyek->tema->nama_tema ?? '-' }}
                    </p>
                </div> --}}
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Tahun Ajaran</p>
                    <p class="text-base font-medium text-gray-800 dark:text-white">
    {{ $proyek->tahunSemester->tahunAjaran->tahun ?? '-' }} -
    {{ ucfirst($proyek->tahunSemester->semester ?? '-') }}
</p>
                </div>
            </div>
        </div>

        {{-- Tabel Detail Proyek --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Daftar Dimensi, Elemen, Subelemen Proyek
                </h2>
            </div>

            <x-table.toolbar
                :enable-add-button="true"
                :enable-import="false"
                :enable-export="false"
                :enable-search="true"
                :route="role_route('p5-proyek.show',  ['p5_proyek' => $proyek->id])">
                <x-slot name="addButton">
                    <button type="button"
                        onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'form-create-proyek-detail' }))"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                        Tambah
                        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M9.25 5a.75.75 0 011.5 0v4.25H15a.75.75 0 010 1.5h-4.25V15a.75.75 0 01-1.5 0v-4.25H5a.75.75 0 010-1.5h4.25V5z" />
                        </svg>
                    </button>
                </x-slot>
            </x-table.toolbar>

            <x-table :columns="[
                'no' => ['label' => 'No', 'sortable' => false],
                'dimensi' => ['label' => 'Dimensi', 'sortable' => true],
                'elemen' => ['label' => 'Elemen', 'sortable' => true],
                'sub_elemen' => ['label' => 'Sub Elemen', 'sortable' => true],
                'fase' => ['label' => 'Capaian Fase', 'sortable' => true],
                // 'capaian' => ['label' => 'Capaian', 'sortable' => true],
            ]" :data="$proyek_detail"
                :total-count="$totalCount"
                row-view="p5-proyek.partials.row-detail"
                :actions="[
                    'edit' => false,
                    'delete' => true,
                    'routes' => [
                        'delete' => fn($item) => role_route('p5-proyek-detail.destroy', [
                            'p5_proyek' => $proyek->id,
                            'p5_proyek_detail' => $item['id'],
                        ]),
                    ],
                    // 'editRoute' => role_route('tujuan-pembelajaran.edit'),
                ]" :use-modal-edit="true" />
        </div>
    </div>
</x-app-layout>

<script>
function formProyekDetail(initData = {}) {
    return {
        dimensi: initData.dimensiInit || '',
        elemen: initData.elemenInit || '',
        subElemen: initData.subElemenInit || '',
        elemenList: initData.elemenList || [],
        subElemenList: initData.subElemenList || [],
        filteredElemen() {
            return this.elemenList.filter(e => String(e.p5_dimensi_id) === String(this.dimensi));
        },
        filteredSubElemen() {
            return this.subElemenList.filter(s => String(s.p5_elemen_id) === String(this.elemen));
        },
        init(data = {}) {
        if (data.elemenList) this.elemenList = data.elemenList;
        if (data.subElemenList) this.subElemenList = data.subElemenList;

        // Jika sedang edit, pastikan filtered dropdown dipicu
        if (this.dimensi) {
            this.filteredElemen(); // ini gak perlu dipanggil, tapi optional
        }
        if (this.elemen) {
            this.filteredSubElemen(); // optional juga
        }

        // Ini penting: pastikan reaktif jika user mengubah
        this.$watch('dimensi', value => {
            if (value !== this.elemenList.find(e => e.id == this.elemen)?.p5_dimensi_id) {
                this.elemen = '';
                this.subElemen = '';
            }
        });

        this.$watch('elemen', value => {
            if (value !== this.subElemenList.find(s => s.id == this.subElemen)?.p5_elemen_id) {
                this.subElemen = '';
            }
        });
    }
    }
}
</script>
