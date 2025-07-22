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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="//unpkg.com/alpinejs" defer></script>
</head>

<body class="font-sans text-gray-900 antialiased bg-gray-100 min-h-screen flex flex-col">

    <!-- Main Content -->
    <main class="flex-1 flex items-center justify-center px-4">
        <div class="w-full max-w-2xl bg-white shadow-md rounded-xl overflow-hidden grid lg:grid-cols-2 items-stretch">

            <!-- Logo (Mobile) -->
            <div class="flex justify-center py-6 lg:hidden">
                <a href="/">
                    <img src="{{ asset('images/logo-app.png') }}" alt="Logo Sekolah"
                        class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <!-- Logo (Desktop) -->
            <div class="hidden lg:flex items-center justify-center bg-gray-200">
                <a href="/" class="w-full h-full">
                    <img src="{{ asset('images/sekolah.jpeg') }}" alt="Foto Sekolah"
                        class="w-full h-full object-cover" />
                </a>
            </div>


            <!-- Form Slot -->
            <div class="p-6 sm:p-8 flex items-center justify-center">
                <div class="w-full">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <x-footer />

</body>

</html>
