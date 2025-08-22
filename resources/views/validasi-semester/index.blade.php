@php
$filters = [
    [
        'name' => 'tahun_semester', // harus sama dengan controller
        'label' => 'Tahun Semester',
        'options' => $semester, // semua semester, default aktif
        'valueKey' => 'id',
        'labelKey' => 'label',
        'enabled' => true,
    ],
];
@endphp

<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs ?? []" title="Validasi Semester" />

    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

        <x-table.toolbar 
            :filters="$filters" 
            :enable-add-button="true" 
            :enable-search="true"
            :enable-import="false"
            :enable-export="false"
            >
            <x-slot name="addButton">
                @if($semesterId) {{-- Button muncul hanya kalau filter semester dipilih --}}
                    <form action="{{ role_route('validasi_semester.validateAll') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="semester_id" value="{{ $semesterId }}">
                        <button type="submit" onclick="return confirm('Yakin ingin memvalidasi semua data?')"
                            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5
                            text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                            </svg>
                            Validasi Semua
                        </button>
                    </form>
                @endif
            </x-slot>
        </x-table.toolbar>

        <x-table :columns="[
            'tahun_semester' => ['label' => 'Tahun Semester', 'sortable' => true],
            'tipe' => ['label' => 'Tipe', 'sortable' => true],
            'is_validated' => ['label' => 'Status', 'sortable' => true],
            'validator_name' => ['label' => 'Divalidasi Oleh', 'sortable' => false],
            'validated_at' => ['label' => 'Tanggal Validasi', 'sortable' => true],
            'actions' => ['label' => 'Aksi', 'sortable' => false],
        ]" :data="$validasi" :total-count="$totalCount" :selectable="false"
            row-view="validasi-semester.partials.row" :actions="['detail' => false, 'edit' => false, 'delete' => false]">
        </x-table>
    </div>
</x-app-layout>
