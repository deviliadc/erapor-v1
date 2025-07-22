<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SDN Darmorejo 02') }}</title>
    <link rel="icon" href="{{ asset('images/logo-app.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- TailAdmin CSS -->
    <link href="{{ asset('tailadmin/build/style.css') }}" rel="stylesheet">
</head>

<body class="font-sans text-gray-900 antialiased bg-gray-100 min-h-screen flex items-center justify-center">

    <main class="w-full min-h-screen flex items-center justify-center bg-white dark:bg-gray-900">
        <div class="w-full max-w-6xl flex flex-col lg:flex-row overflow-hidden shadow-xl
            bg-white dark:bg-gray-950
            rounded-none sm:rounded-2xl
            sm:my-8 mx-auto">

            <!-- Kiri: Gambar hanya di desktop -->
            <div class="hidden lg:block w-2/3 h-screen">
                <img src="{{ asset('images/sekolah.jpeg') }}" alt="Foto Sekolah"
                    class="w-full h-full object-cover" />
            </div>

            <!-- Kanan: Slot Konten -->
            <div class="w-full lg:w-1/3 flex items-center justify-center p-6 sm:p-8">
                <div class="w-full max-w-md">
                    <!-- Logo Mobile -->
                    <div class="flex justify-center mb-6 lg:hidden">
                        <img src="{{ asset('images/logo-app.png') }}" alt="Logo Sekolah" class="w-16 h-16" />
                    </div>
                    {{ $slot }}
                </div>
            </div>
        </div>
    </main>

    @stack('modals')
    @include('components.script')

</body>
</html>
