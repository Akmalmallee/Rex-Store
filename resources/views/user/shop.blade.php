<x-app-layout>
    <div class="pt-24 pb-16 bg-gray-50 dark:bg-[#111] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold">Shop</h1>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">{{ $products->total() }} products found</p>
                </div>
                <div class="flex items-center gap-4 mt-4 md:mt-0">
                    <form method="GET" action="{{ route('shop') }}" class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..."
                               class="pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm focus:border-[#C8A951] focus:ring-[#C8A951] w-64">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </form>
                    <select name="sort" onchange="this.form.submit()" form="filter-form"
                            class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm px-4 py-2.5 focus:border-[#C8A951] focus:ring-[#C8A951]">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="cheapest" {{ request('sort') == 'cheapest' ? 'selected' : '' }}>Cheapest</option>
                        <option value="expensive" {{ request('sort') == 'expensive' ? 'selected' : '' }}>Most Expensive</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Sidebar Filters -->
                <aside class="lg:w-64 shrink-0">
                    <form id="filter-form" method="GET" action="{{ route('shop') }}" class="space-y-6">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="sort" value="{{ request('sort') }}">

                        <!-- Categories -->
                        <div class="bg-white dark:bg-[#1a1a1a] rounded-xl p-5">
                            <h3 class="font-semibold mb-3">Categories</h3>
                            <div class="space-y-2">
                                <a href="{{ route('shop') }}" class="block text-sm {{ !request('category') ? 'text-[#C8A951] font-medium' : 'text-gray-600 dark:text-gray-400 hover:text-[#C8A951]' }} transition-colors">All</a>
                                @foreach($categories as $category)
                                <a href="{{ route('shop') }}?category={{ $category->slug }}" 
                                   class="block text-sm {{ request('category') == $category->slug ? 'text-[#C8A951] font-medium' : 'text-gray-600 dark:text-gray-400 hover:text-[#C8A951]' }} transition-colors">
                                    {{ $category->name }}
                                </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="bg-white dark:bg-[#1a1a1a] rounded-xl p-5">
                            <h3 class="font-semibold mb-3">Price Range</h3>
                            <div class="flex gap-2">
                                <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}"
                                       class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 dark:border-gray-700 dark:bg-gray-800">
                                <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}"
                                       class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 dark:border-gray-700 dark:bg-gray-800">
                            </div>
                            <button type="submit" class="w-full mt-3 py-2 bg-gray-900 dark:bg-white dark:text-gray-900 text-white text-sm rounded-lg hover:bg-black transition-colors">Apply</button>
                        </div>

                        <!-- Sizes -->
                        <div class="bg-white dark:bg-[#1a1a1a] rounded-xl p-5">
                            <h3 class="font-semibold mb-3">Size</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach(['S', 'M', 'L', 'XL', 'XXL'] as $size)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="sizes[]" value="{{ $size }}" {{ in_array($size, (array)request('sizes', [])) ? 'checked' : '' }}
                                           onchange="document.getElementById('filter-form').submit()" class="rounded border-gray-300 text-[#C8A951] focus:ring-[#C8A951]">
                                    <span class="text-sm">{{ $size }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </aside>

                <!-- Product Grid -->
                <div class="flex-1">
                    @if($products->isNotEmpty())
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach($products as $product)
                                <x-product-card :product="$product" />
                            @endforeach
                        </div>
                        <div class="mt-12">
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-20">
                            <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                            <h3 class="text-xl font-medium text-gray-500 dark:text-gray-400">No products found</h3>
                            <p class="text-gray-400 mt-2">Try adjusting your filters</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
