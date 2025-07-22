@php
    $activeTab = request('tab', 'tema');
@endphp

<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    <!-- Wrapper -->
    <div class="rounded-2xl bg-white dark:border-gray-800 dark:bg-white/[0.03]">

        {{-- Toolbar Table --}}
        <x-tabs :tabs="[
            'tema' => 'Tema',
            'dimensi' => 'Dimensi',
            'elemen' => 'Elemen',
            'subelemen' => 'Sub Elemen',
            'proyek' => 'Proyek',
            // 'dokumentasi' => 'Dokumentasi',
        ]" :active="$activeTab">

            <div x-show="activeTab === 'tema'" x-cloak>
                @include('p5-tema.tabs', ['data' => $tema])
            </div>

            <div x-show="activeTab === 'dimensi'" x-cloak>
                @include('p5-dimensi.tabs', ['data' => $dimensi])
            </div>

            <div x-show="activeTab === 'elemen'" x-cloak>
                @include('p5-elemen.tabs', ['data' => $elemen])
            </div>

            <div x-show="activeTab === 'subelemen'" x-cloak>
                @include('p5-subelemen.tabs', ['data' => $subElemen])
            </div>

            <div x-show="activeTab === 'proyek'" x-cloak>
            @include('p5-proyek.tabs', ['data' => $proyek])
            </div>

            {{-- <div x-show="activeTab === 'dokumentasi'" x-cloak>
                @include('p5-dokumentasi.tabs', ['data' => $dokumentasi])
            </div> --}}

        </x-tabs>
    </div>
</x-app-layout>
