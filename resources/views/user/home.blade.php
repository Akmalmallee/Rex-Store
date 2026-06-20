<x-app-layout>
    @php
        $bannerImage = 'https://picsum.photos/seed/fashion-hero/1920/1080';
        $bannerVideo = null;
        if (isset($banners) && $banners->isNotEmpty()) {
            $banner = $banners->first();
            $image = $banner->image;
            $bannerImage = str_starts_with($image, 'http') ? $image : asset('storage/' . $image);
            if ($banner->video) {
                $bannerVideo = str_starts_with($banner->video, 'http') ? $banner->video : asset('storage/' . $banner->video);
            }
        }
    @endphp

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center bg-[#050505] overflow-hidden">
        <div class="absolute inset-0 parallax-slow" data-parallax-speed="0.08">
            @if($bannerVideo)
                <video autoplay muted loop playsinline
                       class="w-full h-full object-cover opacity-40"
                       style="filter: grayscale(100%);">
                    <source src="{{ $bannerVideo }}" type="video/mp4">
                </video>
            @else
                <img src="{{ $bannerImage }}" alt=""
                     class="w-full h-full object-cover opacity-40"
                     style="filter: grayscale(100%);">
            @endif
            <div class="absolute inset-0 bg-gradient-to-r from-[#050505] via-[#050505]/80 to-transparent"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pt-20">
            <div class="max-w-3xl">
                <p class="text-xs tracking-[0.3em] uppercase text-gray-500 font-light mb-6 reveal">New Collection</p>
                <h1 class="hero-title text-7xl md:text-9xl font-light tracking-tighter text-white leading-[0.9] mb-8">
                    <span class="hero-line block">Elevate</span>
                    <span class="hero-line block text-[#C8A951]">Your Style.</span>
                </h1>
                <p class="text-sm font-light text-gray-500 max-w-md leading-relaxed mb-10 reveal reveal-delay-2">
                    Discover premium fashion curated for the discerning. Minimal lines, maximum impact.
                </p>
                <div class="flex flex-wrap gap-6 reveal reveal-delay-3">
                    <a href="{{ route('shop') }}"
                       class="link-underline text-xs tracking-widest uppercase font-light text-white">
                        Shop Collection
                    </a>
                    <a href="{{ route('shop') }}"
                       class="text-xs tracking-widest uppercase font-light text-gray-500 border-b border-transparent pb-1 hover:text-white hover:border-white/30 transition-all duration-300">
                        Explore
                    </a>
                </div>
            </div>
        </div>
        <div class="absolute bottom-12 left-1/2 -translate-x-1/2">
            <div class="w-px h-16 bg-gradient-to-b from-[#C8A951]/0 via-[#C8A951]/50 to-[#C8A951]/0 animate-pulse"></div>
        </div>
    </section>

    <!-- Categories -->
    <section class="bg-[#0a0a0a]">
        <div class="max-w-7xl mx-auto">
            <div class="px-4 sm:px-6 lg:px-8 pt-24 pb-8">
                <p class="text-xs tracking-[0.3em] uppercase text-gray-500 font-light reveal">Categories</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-0" data-stagger>
                @foreach($categories as $category)
                <a href="{{ route('shop') }}?category={{ $category->slug }}" class="group relative aspect-square overflow-hidden bg-[#111] reveal">
                    <img src="{{ $category->image ?? 'https://picsum.photos/seed/cat-'.$category->id.'/600/800' }}"
                         alt="{{ $category->name }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition-all duration-1000 ease-out opacity-60 group-hover:opacity-80"
                         style="filter: grayscale(50%);">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 md:p-8">
                        <h3 class="text-lg md:text-xl font-light text-white tracking-wider">{{ $category->name }}</h3>
                        <p class="text-[10px] text-gray-500 uppercase tracking-widest font-light mt-1">{{ $category->products_count ?? $category->products->count() ?? 0 }} Items</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-24 bg-[#0a0a0a]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-14 reveal">
                <p class="text-xs tracking-[0.3em] uppercase text-gray-500 font-light">Featured</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8" data-stagger>
                @foreach($featuredProducts as $product)
                    <div class="reveal">
                        <x-product-card :product="$product" />
                    </div>
                @endforeach
            </div>
            <div class="mt-14 text-center reveal">
                <a href="{{ route('shop') }}" class="link-underline text-xs tracking-widest uppercase font-light text-gray-500">
                    View All Products
                </a>
            </div>
        </div>
    </section>

    <!-- Promo Banner -->
    @if(isset($promos) && $promos->isNotEmpty())
    <section class="py-24 bg-[#050505]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-0">
                @foreach($promos as $promo)
                <div class="border border-white/5 p-12 md:p-16 reveal group">
                    <p class="text-[10px] tracking-[0.3em] uppercase text-[#C8A951] font-light mb-4">Exclusive</p>
                    <h3 class="text-3xl md:text-4xl font-light text-white tracking-tight mb-4">{{ $promo->title }}</h3>
                    <p class="text-sm font-light text-gray-500 mb-6 leading-relaxed">{{ $promo->description }}</p>
                    <div class="text-7xl md:text-8xl font-light text-[#C8A951] mb-8 tracking-tighter">{{ $promo->discount_percent }}%</div>
                    <a href="{{ route('shop') }}" class="link-underline text-xs tracking-widest uppercase font-light text-white">
                        Shop Now
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- New Arrivals -->
    <section class="py-24 bg-[#0a0a0a]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-14 reveal">
                <p class="text-xs tracking-[0.3em] uppercase text-gray-500 font-light">New Arrivals</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8" data-stagger>
                @foreach($newArrivals as $product)
                    <div class="reveal">
                        <x-product-card :product="$product" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    @php
        $testimonials = \App\Models\Review::with('user')->inRandomOrder()->take(3)->get();
    @endphp
    <section class="py-24 bg-[#0a0a0a] border-t border-white/5">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-xs tracking-[0.3em] uppercase text-gray-500 font-light mb-12 reveal">Testimonials</p>
            @if($testimonials->isNotEmpty())
            <div x-data="{ active: 0 }" class="relative">
                <template x-for="(review, i) in {{ json_encode($testimonials->map(fn($r) => ['comment' => $r->comment, 'name' => $r->user->name ?? 'Anonymous', 'rating' => $r->rating])) }}" :key="i">
                    <div x-show="active === i"
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="px-4">
                        <p class="text-2xl md:text-3xl font-light leading-relaxed text-gray-300 mb-8" x-text="`\u201C${review.comment}\u201D`"></p>
                        <div class="flex items-center justify-center gap-3">
                            <div class="flex gap-1">
                                <template x-for="s in 5">
                                    <svg class="w-4 h-4" :class="s <= review.rating ? 'text-[#C8A951]' : 'text-gray-700'" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </template>
                            </div>
                        </div>
                        <p class="text-xs tracking-widest uppercase text-gray-500 font-light mt-4" x-text="review.name"></p>
                    </div>
                </template>
                <div class="flex justify-center gap-2 mt-10">
                    <template x-for="(review, i) in {{ json_encode($testimonials) }}" :key="i">
                        <button @click="active = i" class="w-2 h-2 rounded-full transition-all duration-300"
                                :class="active === i ? 'bg-[#C8A951] w-6' : 'bg-gray-700 hover:bg-gray-500'"></button>
                    </template>
                </div>
            </div>
            @else
            <p class="text-sm font-light text-gray-500">No testimonials yet. Be the first to leave a review!</p>
            @endif
        </div>
    </section>

    <!-- Newsletter -->
    <section class="py-24 bg-[#050505] border-t border-white/5">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal">
            <p class="text-xs tracking-[0.3em] uppercase text-gray-500 font-light mb-4">Stay Connected</p>
            <p class="text-lg font-light text-gray-400 mb-10 leading-relaxed">Subscribe for exclusive access to new drops and members-only offers.</p>
            <form onsubmit="showToast('Thank you! Newsletter feature coming soon.', 'success'); return false;" class="max-w-md mx-auto flex border-b border-white/10">
                <input type="email" placeholder="Enter your email" class="flex-1 bg-transparent border-0 text-white text-sm font-light py-4 placeholder:text-gray-600 focus:outline-none focus:ring-0">
                <button type="submit" class="text-xs tracking-widest uppercase text-[#C8A951] font-light hover:text-white transition-colors px-2">
                    Subscribe
                </button>
            </form>
        </div>
    </section>
</x-app-layout>
