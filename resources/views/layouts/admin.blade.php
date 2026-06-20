<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0a0a0a">
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Rex Fashion') }} Admin</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:300,400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-[#0a0a0a] text-white/90">
    <div class="flex h-screen overflow-hidden">
        <aside class="fixed inset-y-0 left-0 z-30 w-64 bg-[#0a0a0a]/90 backdrop-blur-2xl border-r border-white/5 transform -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-in-out lg:static lg:inset-auto lg:h-screen lg:overflow-y-auto" id="sidebar">
            <div class="flex items-center justify-between h-16 px-6 border-b border-white/5">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                    <span class="text-xl font-bold text-white">REX</span>
                    <span class="text-xl font-light text-[#C8A951]">FASHION</span>
                </a>
                <button onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full')" class="lg:hidden text-gray-500 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <nav class="px-3 py-4 space-y-0.5">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-light transition-all duration-200 @if(Route::is('admin.dashboard')) text-[#C8A951] border-l border-[#C8A951] bg-[#C8A951]/5 @else text-gray-500 hover:text-white hover:bg-white/[0.02] border-l border-transparent @endif">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-light transition-all duration-200 @if(Route::is('admin.products.*')) text-[#C8A951] border-l border-[#C8A951] bg-[#C8A951]/5 @else text-gray-500 hover:text-white hover:bg-white/[0.02] border-l border-transparent @endif">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    Products
                </a>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-light transition-all duration-200 @if(Route::is('admin.categories.*')) text-[#C8A951] border-l border-[#C8A951] bg-[#C8A951]/5 @else text-gray-500 hover:text-white hover:bg-white/[0.02] border-l border-transparent @endif">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    Categories
                </a>
                <a href="{{ route('admin.brands.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-light transition-all duration-200 @if(Route::is('admin.brands.*')) text-[#C8A951] border-l border-[#C8A951] bg-[#C8A951]/5 @else text-gray-500 hover:text-white hover:bg-white/[0.02] border-l border-transparent @endif">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 4h4a2 2 0 012 2v4M4 20h4m0 0l-4-4m4 4l4-4"></path></svg>
                    Brands
                </a>
                <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-light transition-all duration-200 @if(Route::is('admin.orders.*')) text-[#C8A951] border-l border-[#C8A951] bg-[#C8A951]/5 @else text-gray-500 hover:text-white hover:bg-white/[0.02] border-l border-transparent @endif">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    Orders
                </a>
                <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-light transition-all duration-200 @if(Route::is('admin.payments.*')) text-[#C8A951] border-l border-[#C8A951] bg-[#C8A951]/5 @else text-gray-500 hover:text-white hover:bg-white/[0.02] border-l border-transparent @endif">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Payments
                </a>
                <a href="{{ route('admin.reviews.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-light transition-all duration-200 @if(Route::is('admin.reviews.*')) text-[#C8A951] border-l border-[#C8A951] bg-[#C8A951]/5 @else text-gray-500 hover:text-white hover:bg-white/[0.02] border-l border-transparent @endif">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                    Reviews
                </a>
                <a href="{{ route('admin.coupons.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-light transition-all duration-200 @if(Route::is('admin.coupons.*')) text-[#C8A951] border-l border-[#C8A951] bg-[#C8A951]/5 @else text-gray-500 hover:text-white hover:bg-white/[0.02] border-l border-transparent @endif">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    Coupons
                </a>
                <a href="{{ route('admin.banners.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-light transition-all duration-200 @if(Route::is('admin.banners.*')) text-[#C8A951] border-l border-[#C8A951] bg-[#C8A951]/5 @else text-gray-500 hover:text-white hover:bg-white/[0.02] border-l border-transparent @endif">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Banners
                </a>
                <a href="{{ route('admin.promos.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-light transition-all duration-200 @if(Route::is('admin.promos.*')) text-[#C8A951] border-l border-[#C8A951] bg-[#C8A951]/5 @else text-gray-500 hover:text-white hover:bg-white/[0.02] border-l border-transparent @endif">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                    Promos
                </a>
                <div class="pt-4 mt-4 border-t border-white/5">
                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-light transition-all duration-200 @if(Route::is('admin.users.*')) text-[#C8A951] border-l border-[#C8A951] bg-[#C8A951]/5 @else text-gray-500 hover:text-white hover:bg-white/[0.02] border-l border-transparent @endif">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path></svg>
                        Users
                    </a>
                    <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-light transition-all duration-200 @if(Route::is('admin.reports')) text-[#C8A951] border-l border-[#C8A951] bg-[#C8A951]/5 @else text-gray-500 hover:text-white hover:bg-white/[0.02] border-l border-transparent @endif">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Reports
                    </a>
                    <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-light transition-all duration-200 @if(Route::is('admin.settings')) text-[#C8A951] border-l border-[#C8A951] bg-[#C8A951]/5 @else text-gray-500 hover:text-white hover:bg-white/[0.02] border-l border-transparent @endif">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Settings
                    </a>
                </div>
            </nav>
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-white/5 bg-[#0a0a0a]">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 w-full px-3 py-2.5 text-sm font-light text-gray-500 hover:text-red-400 hover:bg-white/[0.02] border-l border-transparent hover:border-red-400/30 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="sticky top-0 z-20 bg-[#0a0a0a]/80 backdrop-blur-2xl border-b border-white/5">
                <div class="flex items-center justify-between h-16 px-4 lg:px-8">
                    <button onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full')" class="lg:hidden p-2 text-gray-500 hover:text-white hover:bg-white/5 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <div class="flex items-center gap-4 ml-auto">
                        <a href="{{ route('home') }}" class="text-xs tracking-widest uppercase font-light text-gray-500 hover:text-[#C8A951] transition-colors" target="_blank">
                            View Site
                        </a>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-[#C8A951]/10 border border-[#C8A951]/30 flex items-center justify-center text-[#C8A951] text-xs font-light">
                                {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                            </div>
                            <span class="text-sm font-light text-white/80 hidden sm:block">{{ Auth::user()->name ?? 'Admin' }}</span>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-6 lg:p-10 overflow-y-auto">
                @if (session('success'))
                    <div class="mb-6 glass-card border border-emerald-500/20 p-4 flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-xs font-light text-emerald-400">{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-6 glass-card border border-red-500/20 p-4 flex items-center gap-3">
                        <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-xs font-light text-red-400">{{ session('error') }}</span>
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
