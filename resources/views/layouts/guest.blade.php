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

<body class="font-sans text-gray-900 antialiased bg-gray-100 min-h-screen">
    <main class="flex flex-col lg:flex-row h-screen w-screen bg-gray-100 dark:bg-gray-900">
        {{-- Kiri: Gambar (hanya desktop) --}}
        <div class="hidden lg:flex lg:w-1/2 h-64 lg:h-full items-center justify-center bg-gray-200">
            <img src="{{ asset('images/login-logo.png') }}" alt="Login"
                class="w-full h-full object-cover object-center" />
        </div>
        {{-- Kanan: Konten --}}
        <div class="w-full lg:w-1/2 bg-white dark:bg-gray-950">
            <div class="min-h-screen flex items-center justify-center p-8 lg:min-h-0">
                <div class="w-full max-w-md">
                    {{-- Logo hanya di mobile --}}
                    <div class="flex justify-center mb-6 lg:hidden">
                        <img src="{{ asset('images/logo-app.png') }}" alt="Logo Sekolah" class="w-20 h-20" />
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
