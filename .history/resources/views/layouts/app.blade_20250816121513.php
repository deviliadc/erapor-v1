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

    <!-- Logo -->
    <link rel="icon" href="{{ asset('images/logo-app.png') }}">
    <!-- CSS utama aplikasi (TailAdmin build) -->
    <link href="{{ asset('tailadmin/build/style.css') }}" rel="stylesheet">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- jQuery (dibutuhkan oleh Select) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Tom Select CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
    <!-- Tom Select JS -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <!-- Flatpickr JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

</head>

<body x-data="{ page: 'dashboard', 'loaded': true, 'darkMode': false, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }" x-init="darkMode = JSON.parse(localStorage.getItem('darkMode'));
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
        <x-alert type="success" title="Success!" :message="session('success')" />
    @endif

    @if (session('status') === 'password-updated')
        <x-alert type="success" title="Berhasil" message="Password berhasil diubah!" />
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
        {{-- @includeIf('components.sidebar.' . auth()->user()?->role) --}}
        {{-- @php
            $role = auth()->user()->role;
            $sidebarMenu = config("menu.$role", []);
        @endphp --}}
        @php
            $role = auth()->user()->role;
            $sidebarMenu = config("menu.$role", []);
            $guru = auth()->user()->guru;
            $isGuruAktif = $role === 'guru' && $guru && \App\Models\GuruKelas::where('guru_id', $guru->id)->exists();
            $isGuruPengajar =
                $role === 'guru' &&
                $guru &&
                !\App\Models\GuruKelas::where('guru_id', $guru->id)->where('peran', 'wali')->exists();

            $filteredMenu = [];
            foreach ($sidebarMenu as $group => $items) {
                // Jika guru dan tidak aktif (tidak wali & tidak mengajar)
                if ($role === 'guru' && !$isGuruAktif) {
                    $filteredMenu[$group] = collect($items)
                        ->filter(fn($item) => in_array($item['label'], ['Dashboard', 'Ubah Profil']))
                        ->toArray();
                } else {
                    // Filter seperti sebelumnya
                    $filteredMenu[$group] = collect($items)
                        ->filter(function ($item) use ($isGuruPengajar) {
                            if ($item['label'] === 'Data Siswa' && $isGuruPengajar) {
                                return false;
                            }
                            if ($item['label'] === 'Nilai Ekstrakurikuler' && $isGuruPengajar) {
                                return false;
                            }
                            if ($item['label'] === 'Nilai P5' && $isGuruPengajar) {
                                return false;
                            }
                            return true;
                        })
                        ->toArray();
                }
            }
        @endphp

        <x-sidebar-item :items="$filteredMenu" :sidebarToggle="$sidebarToggle ?? false" />
        <!-- ===== Sidebar End ===== -->

        <!-- ===== Content Area Start ===== -->
        <div class="relative flex flex-col flex-1 overflow-x-hidden overflow-y-auto">
            <!-- Small Device Overlay Start -->
            <div @click="sidebarToggle = false" :class="sidebarToggle ? 'block lg:hidden' : 'hidden'"
                class="fixed inset-0 z-[999998] bg-gray-900/50"></div>
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

    @stack('modals')

    <!-- ===== Page Wrapper End ===== -->
    @include('components.script')
</body>

</html>
