@php
    $isGuru = auth()->user()->hasRole('guru');
    // $activeTab = request('tab')
    //     ?? ($isGuru ? 'lingkup-materi' : 'mapel');
    $activeTab = request('tab', 'mapel');
    // $routePrefix = auth()->user()->hasRole('admin') ? 'admin.' : (auth()->user()->hasRole('guru') ? 'guru.' : '');
    // $tabs = [
    //     ...(!$isGuru ? [
    //         'mapel' => 'Mata Pelajaran',
    //         'bab' => 'Bab',
    //     ] : []),
    //     'lingkup-materi' => 'Lingkup Materi',
    //     'tujuan-pembelajaran' => 'Tujuan Pembelajaran',
    // ];
@endphp

<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    <!-- Wrapper -->
    <div class="rounded-2xl bg-white dark:border-gray-800 dark:bg-white/[0.03]">

        {{-- Toolbar Table --}}
        <x-tabs :tabs="[
            'mapel' => 'Mata Pelajaran',
            'bab' => 'Bab',
            'lingkup-materi' => 'Lingkup Materi',
            'tujuan-pembelajaran' => 'Tujuan Pembelajaran',
        ]" :active="$activeTab">
        {{-- <x-tabs :tabs="$tabs" :active="$activeTab"> --}}

            <div x-show="activeTab === 'mapel'" x-cloak>
                @include('mapel.tabs', ['data' => $mapel])
            </div>

            <div x-show="activeTab === 'bab'" x-cloak>
                @include('bab.tabs', ['data' => $bab])
            </div>

            <div x-show="activeTab === 'lingkup-materi'" x-cloak>
                @include('lingkup-materi.tabs', ['data' => $lingkupMateri])
            </div>

            <div x-show="activeTab === 'tujuan-pembelajaran'" x-cloak>
                @include('tujuan-pembelajaran.tabs', ['data' => $tujuanPembelajaran])
            </div>

            {{-- <div x-show="activeTab === 'kelas'" x-cloak>
                @include('kelas-mapel.tabs', ['data' => $kelas])
            </div> --}}

            {{-- <div x-show="activeTab === 'guru'" x-cloak>
                @include('guru.tabs', ['data' => $guru])
            </div> --}}
        </x-tabs>
    </div>
</x-app-layout>
