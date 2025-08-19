@php
    $isGuru = auth()->user()->hasRole('guru');
    // $activeTab = request('tab')
    //     ?? ($isGuru ? 'lingkup-materi' : 'mapel');
    $activeTab = request('tab', 'tahun-ajaran');
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
            'tahun-ajaran' => 'Tahun Ajaran',
            'semester' => 'Semester',
        ]" :active="$activeTab">

            <div x-show="activeTab === 'tahun-ajaran'" x-cloak>
                @include('tahun-ajaran.tabs', ['data' => $tahunAjaran])
            </div>

            <div x-show="activeTab === 'semester'" x-cloak>
                @include('tahun-semester.tabs', ['data' => $tahunSemester])
            </div>
        </x-tabs>
    </div>
</x-app-layout>
