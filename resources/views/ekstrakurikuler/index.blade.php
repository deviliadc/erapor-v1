@php
    $activeTab = request('tab', 'ekstra');
@endphp

<x-app-layout>
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" :title="$title" />

    <!-- Wrapper -->
    <div class="rounded-2xl bg-white dark:border-gray-800 dark:bg-white/[0.03]">

        {{-- Toolbar Table --}}
        <x-tabs :tabs="[
            'ekstra' => 'Ekstrakurikuler',
            'parameter' => 'Parameter',
        ]" :active="$activeTab">

            <div x-show="activeTab === 'ekstra'" x-cloak>
                @include('ekstrakurikuler.tabs', ['data' => $ekstra])
            </div>

            <div x-show="activeTab === 'parameter'" x-cloak>
                @include('param-ekstra.tabs', ['data' => $parameterEkstra])
            </div>
        </x-tabs>
    </div>
</x-app-layout>
