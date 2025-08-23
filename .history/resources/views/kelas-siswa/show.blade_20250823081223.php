@php
    // $routePrefix = auth()->user()->hasRole('admin') ? 'admin.' : (auth()->user()->hasRole('guru') ? 'guru.' : '');
    // $canDelete = auth()->user()->hasRole('admin');
    $isGuru = auth()->user()->hasRole('guru');
    $filters = [
        [
            'name' => 'tahun_ajaran_filter',
            'label' => 'Pilih Tahun Ajaran',
            'options' => $tahunAjaranSelect,
            'valueKey' => 'id',
            'labelKey' => 'name',
            'enabled' => true,
            // Ambil dari request jika ada, jika tidak pakai tahun yang sedang dibuka, jika tidak pakai tahun aktif
            'value' => request('tahun_ajaran_filter') ?? ($tahunAjaranId ?? $tahunAktif?->id),
        ],
    ];
@endphp

<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    {{-- Form Tambah Siswa --}}
    @include('kelas-siswa.create')

    {{-- Form Edit Siswa --}}
    @include('kelas-siswa.edit')

    {{-- Form Promote --}}
    @include('kelas-siswa.promote')

    {{-- Form Generate Absen --}}
    @include('kelas-siswa.generate')

    {{-- Tahun ajaran aktif --}}
    @if ($tahunAjaranAktif)
        <div class="mb-4 text-base font-semibold text-brand-600 dark:text-white">
            Tahun Ajaran Aktif: {{ $tahunAjaranAktif->tahun }}
        </div>
    @endif

    <!-- Wrapper -->
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
        {{-- Toolbar Table --}}
        <x-table.toolbar
            :filters="$filters"
            :enable-add-button="true"
            :enable-import="false"
            :enable-export="false"
            :enable-search="true"
            {{-- :route="role_route('kelas-siswa.index', ['kelas' => $kelas->id])"> --}}
            >

            <x-slot name="addButton">
                <div class="flex gap-2">  <button type="button"
                        onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'form-generate-absen' }))"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8.242 5.992h12m-12 6.003H20.24m-12 5.999h12M4.117 7.495v-3.75H2.99m1.125 3.75H2.99m1.125 0H5.24m-1.92 2.577a1.125 1.125 0 1 1 1.591 1.59l-1.83 1.83h2.16M2.99 15.745h1.125a1.125 1.125 0 0 1 0 2.25H3.74m0-.002h.375a1.125 1.125 0 0 1 0 2.25H2.99" />
                        </svg>
                        Generate No. Absen
                        </a>

                        {{-- Tombol Tambah --}}
                        <button type="button"
                            onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'form-create-kelas-siswa' }))"
                            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 w-36 justify-center px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                            Tambah
                            <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M9.25 5a.75.75 0 011.5 0v4.25H15a.75.75 0 010 1.5h-4.25V15a.75.75 0 01-1.5 0v-4.25H5a.75.75 0 010-1.5h4.25V5z" />
                            </svg>
                        </button>
                </div>
            </x-slot>
        </x-table.toolbar>

        {{-- Table --}}
        <x-table :columns="[
            // 'no' => ['label' => 'No', 'sortable' => false],
            'no_absen' => ['label' => 'No. Absen', 'sortable' => true],
            'nama' => ['label' => 'Nama Siswa', 'sortable' => true],
            // 'nis' => ['label' => 'NIS', 'sortable' => true],
            'nipd' => ['label' => 'NIPD', 'sortable' => true],
            'nisn' => ['label' => 'NISN', 'sortable' => true],
        ]"
            :data="$query" {{-- :data="$siswaList" --}}
            :total-count="$totalCount"
            row-view="kelas-siswa.partials.row-detail"
            :actions="[
                'detail' => true,
                'edit' => true,
                'delete' => true,
                'routes' => [
                    'detail' => fn($item) => role_route('siswa.show', [
                        // 'kelas' => $kelas->id,
                        'siswa' => $item['siswa_id'],
                        'tahun_ajaran_filter' => request('tahun_ajaran_filter') ?? ($tahunAjaranId ?? $tahunAjaranAktif?->id),
                    ]),
                    'delete' => fn($item) => role_route('kelas-siswa.destroy', [
                        'kelas_siswa' => $item['id'],
                    ]),
                ]
            ]" :use-modal-edit="true" />
    </div>

    {{-- <x-modal name="form-generate-absen" title="Generate Nomor Absen" maxWidth="md">
        <form
            action="{{ role_route('kelas.generate.absen', [
                'kelas' => $kelas->id,
                'tahun_ajaran_filter' => request('tahun_ajaran_filter') ?? ($tahunAjaranId ?? $tahunAjaranAktif?->id),
            ]) }}"
            method="GET">
            @csrf
            <input type="hidden" name="tahun_ajaran_filter" value="{{ request('tahun_ajaran_filter') ?? ($tahunAjaranId ?? $tahunAjaranAktif?->id) }}">
            <div class="p-6">
                <p>Urutkan ulang nomor absen berdasarkan nama siswa?</p>
                <div class="flex justify-center mt-4 p-4">
                    <button type="submit" class="px-4 py-2 bg-brand-500 text-white rounded-lg hover:bg-brand-600">
                        Ya, Generate
                    </button>
                </div>
            </div>
        </form>
    </x-modal> --}}
</x-app-layout>
