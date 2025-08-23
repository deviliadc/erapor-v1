{{-- filepath: d:\DEVI\DRAFT\erapor-v1\resources\views\menu-kepsek\rekap-nilai-mapel-detail.blade.php --}}
@php
    $filters = [
        [
            'name' => 'mapel_filter',
            'label' => 'Pilih Mata Pelajaran',
            'options' => $mapelOptions,
            'valueKey' => 'id',
            'labelKey' => 'name',
            'enabled' => true,
            'value' => request('mapel_filter') ?? $mapelAktif?->id,
        ],
    ];
@endphp

<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />
    <div class="mb-2">
        Tahun Semester :
