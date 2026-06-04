<x-app-layout>
    @php
        $bannerImage = 'https://picsum.photos/seed/fashion-hero/1920/1080';
        if (isset($banners) && $banners->isNotEmpty()) {
            $image = $banners->first()->image;
            $bannerImage = str_starts_with($image, 'http') ? $image : asset('storage/' . $image);
        }
    @endphp

    <!-- Hero Section -->
    <section class="min-h-screen flex items-center relative" style="background: linear-gradient(135deg, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.4) 100%), url('{{ $bannerImage }}'); background-size: cover; background-position: center;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pt-20">
            <div class="max-w-2xl">
                <span class="inline-block px-4 py-2 bg-[#C8A951]/20 text-[#C8A951] text-sm rounded-full mb-6">New Collection 2025</span>
                <h1 class="text-5xl md:text-7xl font-bold text-white leading-tight mb-6">
                    Elevate<br>Your <span class="text-[#C8A951]">Style</span>
                </h1>
                <p class="text-lg text-gray-300 mb-8 max-w-lg leading-relaxed">
                    Temukan koleksi fashion premium dengan desain modern dan elegan. Tampil beda dengan Rex Fashion.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('shop') }}" 
                       class="inline-flex items-center gap-2 bg-[#C8A951] hover:bg-[#b8963e] text-white px-8 py-4 rounded-xl font-medium transition-all duration-300 hover:scale-105">
                        Shop Now
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                    <a href="{{ route('shop') }}" 
                       class="inline-flex items-center gap-2 border-2 border-white/30 hover:border-white text-white px-8 py-4 rounded-xl font-medium transition-all duration-300">
                        Explore
                    </a>
                </div>
            </div>
        </div>
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
        </div>
    </section>

    <!-- Categories -->
    <section class="py-20 bg-white dark:bg-[#0a0a0a]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-title title="Shop by Category" subtitle="Temukan koleksi berdasarkan kategori favoritmu" />
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($categories as $category)
                <a href="{{ route('shop') }}?category={{ $category->slug }}" class="group relative overflow-hidden rounded-2xl aspect-[3/4]">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent z-10"></div>
                    <img src="{{ $category->image ?? 'https://picsum.photos/seed/cat-'.$category->id.'/600/800' }}" 
                         alt="{{ $category->name }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute bottom-0 left-0 right-0 p-6 z-20">
                        <h3 class="text-xl font-bold text-white mb-1">{{ $category->name }}</h3>
                        <p class="text-sm text-gray-300">{{ $category->products_count ?? $category->products->count() ?? 0 }} Products</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-20 bg-gray-50 dark:bg-[#111]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-title title="Featured Products" subtitle="Koleksi pilihan terbaik bulan ini" />
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
            <div class="text-center mt-12">
                <a href="{{ route('shop') }}" class="btn-outline inline-flex items-center gap-2">
                    View All Products
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Promo Banner -->
    @if(isset($promos) && $promos->isNotEmpty())
    <section class="py-20 bg-white dark:bg-[#0a0a0a]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($promos as $promo)
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-gray-900 to-black p-8 md:p-12">
                    <div class="relative z-10">
                        <span class="text-[#C8A951] text-sm font-semibold uppercase tracking-wider">Promo</span>
                        <h3 class="text-3xl font-bold text-white mt-2 mb-3">{{ $promo->title }}</h3>
                        <p class="text-gray-400 mb-6">{{ $promo->description }}</p>
                        <div class="text-4xl font-bold text-[#C8A951] mb-6">{{ $promo->discount_percent }}% OFF</div>
                        <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 bg-[#C8A951] hover:bg-[#b8963e] text-white px-6 py-3 rounded-xl transition-all duration-300">
                            Shop Now
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- New Arrivals -->
    <section class="py-20 bg-gray-50 dark:bg-[#111]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-title title="New Arrivals" subtitle="Koleksi terbaru yang baru saja tiba" />
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($newArrivals as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-20 bg-white dark:bg-[#0a0a0a]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-section-title title="What Our Customers Say" subtitle="Testimoni dari pelanggan setia Rex Fashion" />
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-8 rounded-2xl bg-gray-50 dark:bg-[#1a1a1a]">
                    <div class="flex gap-1 mb-4">
                        @for($i=0;$i<5;$i++) <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">"Kualitas produk luar biasa! Bahannya nyaman dan desainnya modern. Pengiriman cepat dan packing rapi."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#C8A951] flex items-center justify-center text-white font-medium">S</div>
                        <div>
                            <p class="font-medium text-sm">Sarah</p>
                            <p class="text-xs text-gray-500">Regular Customer</p>
                        </div>
                    </div>
                </div>
                <div class="p-8 rounded-2xl bg-gray-50 dark:bg-[#1a1a1a]">
                    <div class="flex gap-1 mb-4">
                        @for($i=0;$i<5;$i++) <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">"Pelayanan sangat memuaskan! Ukuran sesuai dan warna tidak luntur. Pasti akan repeat order lagi."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#C8A951] flex items-center justify-center text-white font-medium">R</div>
                        <div>
                            <p class="font-medium text-sm">Rudi</p>
                            <p class="text-xs text-gray-500">Loyal Member</p>
                        </div>
                    </div>
                </div>
                <div class="p-8 rounded-2xl bg-gray-50 dark:bg-[#1a1a1a]">
                    <div class="flex gap-1 mb-4">
                        @for($i=0;$i<5;$i++) <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">"Produk original dengan harga terjangkau. Desain minimalis tapi tetap elegan. Highly recommended!"</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#C8A951] flex items-center justify-center text-white font-medium">D</div>
                        <div>
                            <p class="font-medium text-sm">Dian</p>
                            <p class="text-xs text-gray-500">Fashion Enthusiast</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="py-20 bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Stay in the Loop</h2>
            <p class="text-gray-400 mb-8 max-w-md mx-auto">Subscribe untuk mendapatkan info koleksi terbaru, promo eksklusif, dan tips fashion.</p>
            <form class="max-w-md mx-auto flex gap-3">
                <input type="email" placeholder="Email address" class="flex-1 px-5 py-3 bg-gray-800 rounded-xl text-white placeholder-gray-500 border border-gray-700 focus:border-[#C8A951] focus:outline-none transition-colors">
                <button type="submit" class="px-6 py-3 bg-[#C8A951] hover:bg-[#b8963e] text-white rounded-xl font-medium transition-all duration-300">Subscribe</button>
            </form>
        </div>
    </section>
</x-app-layout>
