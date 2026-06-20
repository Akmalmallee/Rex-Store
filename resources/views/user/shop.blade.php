<x-app-layout>
    @php
        $hasFilters = request()->filled('search') || request()->filled('category') || request()->filled('min_price') || request()->filled('max_price') || request()->filled('sizes') || request()->filled('sort');
    @endphp
    <div class="pt-28 pb-24 bg-[#0a0a0a] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-10">
                <div class="reveal">
                    <p class="text-xs tracking-[0.3em] uppercase text-gray-500 font-light">All Products</p>
                    <p class="text-xs text-gray-600 font-light mt-1">{{ $products->total() }} items</p>
                </div>
                <div class="flex items-center gap-4 mt-4 md:mt-0 reveal reveal-delay-1">
                    <form method="GET" action="{{ route('shop') }}" class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
                               class="bg-transparent border-0 border-b border-white/10 text-white text-xs font-light pl-0 py-2 w-40 md:w-48 focus:border-[#C8A951] focus:ring-0 focus:outline-none placeholder:text-gray-600 transition-colors">
                    </form>
                    <select name="sort" onchange="this.form.submit()" form="filter-form"
                            class="bg-transparent border-0 border-b border-white/10 text-xs tracking-widest uppercase font-light text-gray-400 py-2 focus:outline-none focus:border-[#C8A951]">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="cheapest" {{ request('sort') == 'cheapest' ? 'selected' : '' }}>Lowest Price</option>
                        <option value="expensive" {{ request('sort') == 'expensive' ? 'selected' : '' }}>Highest Price</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                    </select>
                </div>
            </div>

            <!-- Active Filters -->
            @if($hasFilters)
            <div class="mb-8 flex flex-wrap items-center gap-2 reveal reveal-delay-1">
                @if(request('search'))
                <span class="filter-chip filter-chip-active">
                    "{{ request('search') }}"
                    <button onclick="removeFilter('search')" class="text-gray-500 hover:text-white ml-1">&times;</button>
                </span>
                @endif
                @if(request('category'))
                <span class="filter-chip filter-chip-active">
                    {{ request('category') }}
                    <button onclick="removeFilter('category')" class="text-gray-500 hover:text-white ml-1">&times;</button>
                </span>
                @endif
                @if(request('min_price') || request('max_price'))
                <span class="filter-chip filter-chip-active">
                    Rp{{ request('min_price') ? number_format((int)request('min_price'), 0, ',', '.') : '0' }} - Rp{{ request('max_price') ? number_format((int)request('max_price'), 0, ',', '.') : '∞' }}
                    <button onclick="removeFilter('min_price'); removeFilter('max_price')" class="text-gray-500 hover:text-white ml-1">&times;</button>
                </span>
                @endif
                @if(request('sizes'))
                    @foreach((array)request('sizes') as $size)
                    <span class="filter-chip filter-chip-active">
                        {{ $size }}
                        <button onclick="removeFilter('sizes[]', '{{ $size }}')" class="text-gray-500 hover:text-white ml-1">&times;</button>
                    </span>
                    @endforeach
                @endif
                @if(request('sort') && request('sort') != 'newest')
                <span class="filter-chip filter-chip-active">
                    {{ ucfirst(request('sort')) }}
                    <button onclick="removeFilter('sort')" class="text-gray-500 hover:text-white ml-1">&times;</button>
                </span>
                @endif
                <button onclick="clearAllFilters()" class="filter-chip text-red-400/60 hover:text-red-400 ml-1">
                    Clear All
                </button>
            </div>
            @endif

            <div class="flex flex-col lg:flex-row gap-10">
                <!-- Sidebar Filters -->
                <aside class="lg:w-56 shrink-0 reveal">
                    <form id="filter-form" method="GET" action="{{ route('shop') }}" class="space-y-8">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="sort" value="{{ request('sort') }}">

                        <!-- Categories -->
                        <div>
                            <p class="text-xs tracking-widest uppercase font-light text-gray-300 mb-4">Categories</p>
                            <div class="space-y-2.5">
                                <a href="{{ route('shop') }}" class="block text-sm font-light {{ !request('category') ? 'text-[#C8A951]' : 'text-gray-500 hover:text-[#C8A951]' }} transition-colors">All</a>
                                @foreach($categories as $category)
                                <a href="{{ route('shop') }}?category={{ $category->slug }}"
                                   class="block text-sm font-light {{ request('category') == $category->slug ? 'text-[#C8A951]' : 'text-gray-500 hover:text-[#C8A951]' }} transition-colors">
                                    {{ $category->name }}
                                </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div>
                            <p class="text-xs tracking-widest uppercase font-light text-gray-300 mb-4">Price Range</p>
                            <div class="flex gap-2">
                                <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}"
                                       class="w-full bg-transparent border border-white/10 text-white text-xs font-light px-3 py-2 focus:border-[#C8A951] focus:outline-none transition-colors placeholder:text-gray-600">
                                <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}"
                                       class="w-full bg-transparent border border-white/10 text-white text-xs font-light px-3 py-2 focus:border-[#C8A951] focus:outline-none transition-colors placeholder:text-gray-600">
                            </div>
                            <button type="submit" class="w-full mt-3 py-2 bg-white/5 border border-white/10 text-xs tracking-widest uppercase font-light text-gray-400 hover:border-[#C8A951] hover:text-[#C8A951] transition-all duration-300">Apply</button>
                        </div>

                        <!-- Sizes -->
                        <div>
                            <p class="text-xs tracking-widest uppercase font-light text-gray-300 mb-4">Size</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach(['S', 'M', 'L', 'XL', 'XXL'] as $size)
                                <label class="flex items-center gap-2 cursor-pointer border border-white/10 px-3 py-2 hover:border-[#C8A951] transition-colors has-[:checked]:border-[#C8A951] has-[:checked]:bg-[#C8A951]/5">
                                    <input type="checkbox" name="sizes[]" value="{{ $size }}" {{ in_array($size, (array)request('sizes', [])) ? 'checked' : '' }}
                                           onchange="document.getElementById('filter-form').submit()" class="hidden">
                                    <span class="text-xs font-light {{ in_array($size, (array)request('sizes', [])) ? 'text-[#C8A951]' : 'text-gray-400' }}">{{ $size }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </aside>

                <!-- Product Grid -->
                <div class="flex-1">
                    @if($products->isNotEmpty())
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-8" data-stagger>
                            @foreach($products as $product)
                                <div class="reveal">
                                    <x-product-card :product="$product" />
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-16">
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-20 reveal">
                            <svg class="w-12 h-12 mx-auto text-gray-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                            <p class="text-sm font-light text-gray-500">No products found</p>
                            <p class="text-xs text-gray-600 font-light mt-1">Try adjusting your filters</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
