<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0a0a0a">
    <title>@yield('title', config('app.name', 'Rex Fashion')) - Rex Fashion</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:300,400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-[#0a0a0a] text-white selection:bg-[#C8A951]/30">
    <div id="page-load-bar"></div>

    <x-user-navbar />

    <main>
        {{ $slot }}
    </main>

    <x-user-footer />

    <div id="cart-sidebar-overlay" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-40 hidden transition-opacity duration-300" onclick="toggleCart()"></div>
    <div id="cart-sidebar" class="fixed top-0 right-0 h-full w-full max-w-md bg-[#0a0a0a]/90 backdrop-blur-2xl z-50 transform translate-x-full transition-transform duration-500 border-l border-white/5 overflow-y-auto">
        <div id="cart-sidebar-content"></div>
    </div>

    <div id="toast-container" class="fixed bottom-8 left-8 z-[100] space-y-2"></div>

    @stack('scripts')
</body>
</html>
