<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>
        SDN Darmorejo 02
    </title>

    <link rel="icon" href="{{ asset('images/logo-app.png') }}">
    <link href="{{ asset('tailadmin/build/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>

<body x-data="{ page: 'ecommerce', 'loaded': true, 'darkMode': false, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }" x-init="darkMode = JSON.parse(localStorage.getItem('darkMode'));
$watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))" :class="{ 'dark bg-gray-900': darkMode === true }">
    <!-- ===== Preloader Start ===== -->
    <div x-show="loaded" x-init="window.addEventListener('DOMContentLoaded', () => { setTimeout(() => loaded = false, 500) })"
        class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white dark:bg-black">
        <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-brand-500 border-t-transparent">
        </div>
    </div>

    <!-- ===== Preloader End ===== -->

    <!-- ===== Alerts Start ===== -->
    @if (session('success'))
        <x-alert type="success" title="Berhasil!" :message="session('success')" />
    @endif

    @if (session('error'))
        <x-alert type="error" title="Error!" :message="session('error')" />
    @endif

    @if ($errors->any())
        <x-alert type="error" title="Kesalahan Validasi">
            <ul class="list-disc list-inside text-sm text-gray-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif
    <!-- ===== Alerts End ===== -->

    <!-- ===== Page Wrapper Start ===== -->
    <div class="flex h-screen overflow-hidden">
        <!-- ===== Sidebar Start ===== -->
        <x-sidebar />
        <!-- ===== Sidebar End ===== -->

        <!-- ===== Content Area Start ===== -->
        <div class="relative flex flex-col flex-1 overflow-x-hidden overflow-y-auto">
            <!-- Small Device Overlay Start -->
            <div @click="sidebarToggle = false" :class="sidebarToggle ? 'block lg:hidden' : 'hidden'"
                class="fixed w-full h-screen z-9 bg-gray-900/50"></div>
            <!-- Small Device Overlay End -->

            <!-- ===== Header Start ===== -->
            <x-header />
            <!-- ===== Header End ===== -->

            <!-- ===== Main Content Start ===== -->
            <main>
                <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
                    {{-- Breadcrumb --}}
                    @if (isset($breadcrumbs) && count($breadcrumbs))
                        <x-breadcrumbs :items="$breadcrumbs" />
                    @endif

                    {{-- Content --}}
                    {{ $slot }}
                </div>
            </main>
            <!-- ===== Main Content End ===== -->

            <!-- ===== Footer Start ===== -->
            <x-footer />
            <!-- ===== Footer End ===== -->

        </div>

        <!-- ===== Content Area End ===== -->
    </div>

    <!-- ===== Page Wrapper End ===== -->
    @include('components.script')
</body>

</html>
