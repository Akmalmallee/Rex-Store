<nav x-data="{ open: false, cartOpen: false, userOpen: false, scrolled: false }"
     x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 50, { passive: true })"
     data-luxury-nav
     class="fixed top-0 left-0 right-0 z-50 transition-all duration-500"
     :class="scrolled ? 'bg-[#0a0a0a]/80 backdrop-blur-2xl border-b border-white/5 shadow-2xl' : 'bg-transparent'">
    @php $isDarkPage = request()->routeIs('home'); @endphp
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-20">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-baseline tracking-tight logo-glow group">
                <span class="text-2xl md:text-3xl font-light tracking-[0.25em]"
                      :class="scrolled ? 'text-white' : 'text-white'">
                    REX
                </span>
                <span class="text-xl md:text-2xl font-light tracking-wider text-[#C8A951] ml-1 group-hover:text-white transition-colors duration-500">FASHION</span>
            </a>

            <!-- Desktop Nav -->
            <div class="hidden lg:flex items-center gap-10">
                <a href="{{ route('home') }}" class="link-underline text-xs tracking-widest uppercase font-light pb-1 transition-colors duration-300 hover:text-[#C8A951]"
                   :class="scrolled ? 'text-gray-300' : 'text-white/80'">
                    Home
                </a>
                <div x-data="{ shopOpen: false }" class="relative">
                    <button @click="shopOpen = !shopOpen" @click.away="shopOpen = false"
                            class="link-underline text-xs tracking-widest uppercase font-light pb-1 transition-colors duration-300 hover:text-[#C8A951] cursor-pointer flex items-center gap-1.5 focus:outline-none"
                            :class="scrolled ? 'text-gray-300' : 'text-white/80'">
                        Shop
                        <svg class="w-3 h-3 transition-transform duration-300" :class="shopOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="shopOpen" x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="absolute left-1/2 -translate-x-1/2 mt-3 w-44 glass-strong py-2 z-50">
                        <a href="{{ route('shop') }}" @click="shopOpen = false" class="block px-5 py-2.5 text-xs tracking-wider font-light hover:text-[#C8A951] transition-colors hover:translate-x-1">All Products</a>
                        <div class="border-t border-white/5 my-1"></div>
                        @foreach(['baju', 'celana', 'jacket', 'topi'] as $cat)
                        <a href="{{ route('shop') }}?category={{ $cat }}" @click="shopOpen = false" class="block px-5 py-2.5 text-xs tracking-wider font-light hover:text-[#C8A951] transition-all hover:translate-x-1">{{ ucfirst($cat) }}</a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Side -->
            <div class="flex items-center gap-3">
                @auth
                    @php $cartCount = auth()->user()->cart?->items()->count() ?? 0; @endphp
                    <!-- Wishlist -->
                    <a href="{{ route('wishlist') }}" class="p-2 transition-all duration-300 hover:text-[#C8A951] hover:scale-110 hidden sm:block"
                       :class="scrolled ? 'text-gray-300' : 'text-white/70'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </a>

                    <!-- Cart -->
                    <button onclick="toggleCart()" class="p-2 relative transition-all duration-300 hover:text-[#C8A951] hover:scale-110"
                            :class="scrolled ? 'text-gray-300' : 'text-white/70'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <span data-cart-count class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-[#C8A951] text-black text-[8px] font-bold flex items-center justify-center">{{ $cartCount }}</span>
                    </button>

                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 p-2 transition-all duration-300 text-xs tracking-widest uppercase font-light hover:scale-110"
                                :class="scrolled ? 'text-gray-300' : 'text-white/70'">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </button>
                        <div x-show="open" @click.away="open = false"
                             class="absolute right-0 mt-2 w-52 glass-strong py-2 z-50"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100">
                            <div class="px-5 py-2 border-b border-white/5">
                                <p class="text-sm font-light">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] text-gray-500 font-light">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('orders') }}" class="block px-5 py-2.5 text-xs font-light hover:text-[#C8A951] transition-colors hover:translate-x-1">Pesanan Saya</a>
                            <a href="{{ route('wishlist') }}" class="block px-5 py-2.5 text-xs font-light hover:text-[#C8A951] transition-colors hover:translate-x-1">Wishlist</a>
                            <a href="{{ route('profile.edit') }}" class="block px-5 py-2.5 text-xs font-light hover:text-[#C8A951] transition-colors hover:translate-x-1">Profile</a>
                            @if(Auth::user()->role === 'admin')
                                <div class="border-t border-white/5"></div>
                                <a href="{{ route('admin.dashboard') }}" class="block px-5 py-2.5 text-xs font-light text-[#C8A951] hover:text-white transition-colors hover:translate-x-1">Admin Panel</a>
                            @endif
                            <div class="border-t border-white/5"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-5 py-2.5 text-xs font-light text-red-400 hover:text-red-300 transition-colors hover:translate-x-1">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-xs tracking-widest uppercase font-light transition-all duration-300 hover:text-[#C8A951] link-underline"
                       :class="scrolled ? 'text-gray-300' : 'text-white/70'">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="text-xs tracking-widest uppercase font-light px-4 py-2 border border-white/10 hover:border-[#C8A951]/50 hover:text-[#C8A951] transition-all duration-300"
                       :class="scrolled ? 'text-gray-300' : 'text-white/70'">
                        Register
                    </a>
                @endauth

                <!-- Mobile Menu Button -->
                <button @click="open = !open" class="lg:hidden p-2 transition-all duration-300 hover:scale-110"
                        :class="scrolled ? 'text-gray-300' : 'text-white/70'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" class="lg:hidden bg-[#0a0a0a]/95 backdrop-blur-2xl border-t border-white/5"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0">
        <div class="px-6 py-6 space-y-4">
            <a href="{{ route('home') }}" class="block text-sm tracking-widest uppercase font-light hover:text-[#C8A951] transition-colors">Home</a>
            <div x-data="{ shopOpen: false }">
                <button @click="shopOpen = !shopOpen" class="flex items-center justify-between w-full text-sm tracking-widest uppercase font-light hover:text-[#C8A951] transition-colors focus:outline-none">
                    <span>Shop</span>
                    <svg class="w-3 h-3 transition-transform duration-300" :class="shopOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="shopOpen" class="ml-4 mt-3 space-y-2 border-l border-white/10 pl-4">
                    <a href="{{ route('shop') }}" class="block text-xs font-light text-gray-400 hover:text-[#C8A951] transition-colors">All Products</a>
                    @foreach(['baju', 'celana', 'jacket', 'topi'] as $cat)
                    <a href="{{ route('shop') }}?category={{ $cat }}" class="block text-xs font-light text-gray-400 hover:text-[#C8A951] transition-colors">{{ ucfirst($cat) }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</nav>
