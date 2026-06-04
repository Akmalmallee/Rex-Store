<nav x-data="{ open: false, cartOpen: false, userOpen: false, scrolled: false }" 
     x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 50)"
     class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
     :class="scrolled ? 'bg-white/95 dark:bg-black/95 backdrop-blur-xl shadow-sm' : 'bg-transparent'">
    @php $isDarkPage = request()->routeIs('home'); @endphp
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-20">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <span class="text-2xl font-bold tracking-tight"
                      :class="scrolled ? 'text-gray-900 dark:text-white' : '{{ $isDarkPage ? 'text-white' : 'text-gray-900' }}'">
                    <span class="text-black dark:text-white">REX</span><span class="text-[#C8A951]">FASHION</span>
                </span>
            </a>

            <!-- Desktop Nav -->
            <div class="hidden lg:flex items-center gap-8">
                <a href="{{ route('home') }}" class="text-sm font-medium transition-colors duration-200 hover:text-[#C8A951]"
                   :class="scrolled ? 'text-gray-700 dark:text-gray-300' : '{{ $isDarkPage ? 'text-white/90' : 'text-gray-700' }}'">
                    Home
                </a>
                <div x-data="{ shopOpen: false }" class="relative">
                    <button @click="shopOpen = !shopOpen" @click.away="shopOpen = false"
                            class="text-sm font-medium transition-colors duration-200 hover:text-[#C8A951] cursor-pointer flex items-center gap-1 focus:outline-none"
                            :class="scrolled ? 'text-gray-700 dark:text-gray-300' : '{{ $isDarkPage ? 'text-white/90' : 'text-gray-700' }}'">
                        Shop
                        <svg class="w-4 h-4 transition-transform" :class="shopOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="shopOpen" x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="absolute left-0 mt-2 w-48 bg-white dark:bg-[#1a1a1a] rounded-xl shadow-xl border border-gray-100 dark:border-gray-800 py-2 z-50">
                        <a href="{{ route('shop') }}" @click="shopOpen = false" class="block px-4 py-2.5 text-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">All Products</a>
                        <div class="border-t border-gray-100 dark:border-gray-800 my-1"></div>
                        <a href="{{ route('shop') }}?category=baju" @click="shopOpen = false" class="block px-4 py-2.5 text-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">Baju</a>
                        <a href="{{ route('shop') }}?category=celana" @click="shopOpen = false" class="block px-4 py-2.5 text-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">Celana</a>
                        <a href="{{ route('shop') }}?category=jacket" @click="shopOpen = false" class="block px-4 py-2.5 text-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">Jacket</a>
                        <a href="{{ route('shop') }}?category=topi" @click="shopOpen = false" class="block px-4 py-2.5 text-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">Topi</a>
                    </div>
                </div>
            </div>

            <!-- Right Side -->
            <div class="flex items-center gap-4">
                @auth
                    @php $cartCount = auth()->user()->cart?->items()->count() ?? 0; @endphp
                    <!-- Wishlist -->
                    <a href="{{ route('wishlist') }}" class="p-2 transition-colors duration-200 hover:text-[#C8A951]"
                       :class="scrolled ? 'text-gray-700 dark:text-gray-300' : '{{ $isDarkPage ? 'text-white/90' : 'text-gray-700' }}'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </a>

                    <!-- Cart -->
                    <button onclick="toggleCart()" class="p-2 relative transition-colors duration-200 hover:text-[#C8A951]"
                            :class="scrolled ? 'text-gray-700 dark:text-gray-300' : '{{ $isDarkPage ? 'text-white/90' : 'text-gray-700' }}'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <span data-cart-count class="absolute -top-1 -right-1 bg-[#C8A951] text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center">{{ $cartCount }}</span>
                    </button>

                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 p-2 transition-colors duration-200"
                                :class="scrolled ? 'text-gray-700 dark:text-gray-300' : '{{ $isDarkPage ? 'text-white/90' : 'text-gray-700' }}'">
                            <div class="w-8 h-8 rounded-full bg-[#C8A951] flex items-center justify-center text-white text-sm font-medium">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </button>
                        <div x-show="open" @click.away="open = false"
                             class="absolute right-0 mt-2 w-56 bg-white dark:bg-[#1a1a1a] rounded-xl shadow-xl border border-gray-100 dark:border-gray-800 py-2"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100">
                            <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-800">
                                <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('orders') }}" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">Pesanan Saya</a>
                            <a href="{{ route('wishlist') }}" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">Wishlist</a>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">Profile</a>
                            @if(Auth::user()->role === 'admin')
                                <div class="border-t border-gray-100 dark:border-gray-800"></div>
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-[#C8A951] hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">Admin Panel</a>
                            @endif
                            <div class="border-t border-gray-100 dark:border-gray-800"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium transition-colors duration-200 px-4 py-2 rounded-lg border"
                       :class="scrolled ? 'text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-800' : '{{ $isDarkPage ? 'text-white border-white/30 hover:bg-white/10' : 'text-gray-700 border-gray-300 hover:bg-gray-100' }}'">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="text-sm font-medium px-4 py-2 rounded-lg transition-all duration-200 bg-[#C8A951] hover:bg-[#b8963e] text-white">
                        Register
                    </a>
                @endauth

                <!-- Mobile Menu Button -->
                <button @click="open = !open" class="lg:hidden p-2 transition-colors duration-200"
                        :class="scrolled ? 'text-gray-700 dark:text-gray-300' : '{{ $isDarkPage ? 'text-white/90' : 'text-gray-700' }}'">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" class="lg:hidden bg-white dark:bg-[#0a0a0a] border-t border-gray-100 dark:border-gray-800"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0">
        <div class="px-4 py-4 space-y-3">
            <a href="{{ route('home') }}" class="block text-sm font-medium hover:text-[#C8A951] transition-colors">Home</a>
            <div x-data="{ shopOpen: false }">
                <button @click="shopOpen = !shopOpen" class="flex items-center justify-between w-full text-sm font-medium hover:text-[#C8A951] transition-colors focus:outline-none">
                    <span>Shop</span>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="shopOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="shopOpen" class="ml-4 mt-2 space-y-2 border-l-2 border-gray-200 dark:border-gray-700 pl-4">
                    <a href="{{ route('shop') }}" class="block text-sm text-gray-600 dark:text-gray-400 hover:text-[#C8A951] transition-colors">All Products</a>
                    <a href="{{ route('shop') }}?category=baju" class="block text-sm text-gray-600 dark:text-gray-400 hover:text-[#C8A951] transition-colors">Baju</a>
                    <a href="{{ route('shop') }}?category=celana" class="block text-sm text-gray-600 dark:text-gray-400 hover:text-[#C8A951] transition-colors">Celana</a>
                    <a href="{{ route('shop') }}?category=jacket" class="block text-sm text-gray-600 dark:text-gray-400 hover:text-[#C8A951] transition-colors">Jacket</a>
                    <a href="{{ route('shop') }}?category=topi" class="block text-sm text-gray-600 dark:text-gray-400 hover:text-[#C8A951] transition-colors">Topi</a>
                </div>
            </div>
        </div>
    </div>
</nav>
