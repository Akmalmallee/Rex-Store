<x-app-layout>
    <div class="pt-24 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-8 text-sm text-gray-500 dark:text-gray-400">
                <a href="{{ route('home') }}" class="hover:text-[#C8A951]">Home</a>
                <span class="mx-2">/</span>
                <a href="{{ route('shop') }}" class="hover:text-[#C8A951]">Shop</a>
                <span class="mx-2">/</span>
                @if($product->category)
                <a href="{{ route('shop') }}?category={{ $product->category->slug }}" class="hover:text-[#C8A951]">{{ $product->category->name }}</a>
                <span class="mx-2">/</span>
                @endif
                <span class="text-gray-900 dark:text-white">{{ $product->name }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Gallery -->
                <div>
                    @php
                        $cacheBuster = '?t=' . $product->updated_at->timestamp;
                        $imageUrl = $product->thumbnail 
                            ? (str_starts_with($product->thumbnail, 'http') ? $product->thumbnail : Storage::url($product->thumbnail)) . $cacheBuster
                            : ($product->images->first() 
                                ? (str_starts_with($product->images->first()->image, 'http') ? $product->images->first()->image : Storage::url($product->images->first()->image))
                                : 'https://picsum.photos/seed/product-'.$product->id.'/800/1000');
                    @endphp
                    <div class="aspect-[4/5] rounded-2xl overflow-hidden bg-gray-100 dark:bg-gray-800 mb-4">
                        <img id="main-image" src="{{ $imageUrl }}" alt="{{ $product->name }}" 
                             class="w-full h-full object-cover cursor-zoom-in"
                             onmousemove="zoomImage(event)" onmouseleave="resetZoom()">
                    </div>
                    @if($product->images->isNotEmpty())
                    <div class="grid grid-cols-4 gap-3">
                        @foreach($product->images as $image)
                        @php 
                            $imgSrc = str_starts_with($image->image, 'http') ? $image->image : Storage::url($image->image);
                            $imgSrcWithCache = str_starts_with($image->image, 'http') ? $imgSrc : ($imgSrc . $cacheBuster);
                        @endphp
                        <button onclick="changeImage(this)" 
                                data-src="{{ $imgSrcWithCache }}"
                                class="aspect-square rounded-xl overflow-hidden border-2 {{ $loop->first ? 'border-[#C8A951]' : 'border-transparent' }} hover:border-[#C8A951] transition-colors">
                            <img src="{{ $imgSrc . $cacheBuster }}" 
                                 alt="" class="w-full h-full object-cover">
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- Info -->
                <div>
                    @if($product->category)
                    <p class="text-sm text-[#C8A951] font-medium uppercase tracking-wider mb-2">{{ $product->category->name }}</p>
                    @endif
                    <h1 class="text-3xl font-bold mb-4">{{ $product->name }}</h1>
                    
                    <div class="flex items-center gap-4 mb-6">
                        <div class="flex items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= round($product->rating) ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                            <span class="text-sm text-gray-500 ml-2">({{ $product->reviews->count() }} reviews)</span>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="mb-8">
                        @if($product->discount > 0)
                            @php $finalPrice = $product->price - ($product->price * $product->discount / 100); @endphp
                            <div class="flex items-center gap-3">
                                <span class="text-3xl font-bold text-[#C8A951]">Rp {{ number_format($finalPrice, 0, ',', '.') }}</span>
                                <span class="text-lg text-gray-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <span class="px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-500 text-sm rounded-full">-{{ $product->discount }}%</span>
                            </div>
                        @else
                            <span class="text-3xl font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        @endif
                    </div>

                    <!-- Description -->
                    <p class="text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">{{ $product->description }}</p>

                    <!-- Size -->
                    @if($product->sizes->isNotEmpty())
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold mb-3">Size</h3>
                        <div class="flex flex-wrap gap-2" id="size-selector">
                            @foreach($product->sizes as $size)
                            <button onclick="selectSize(this, '{{ $size->size }}')" 
                                    class="px-5 py-2.5 border-2 border-gray-200 dark:border-gray-700 rounded-xl text-sm font-medium hover:border-[#C8A951] transition-colors
                                    {{ $loop->first ? 'border-[#C8A951] bg-[#C8A951]/10' : '' }}">
                                {{ $size->size }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Color -->
                    @if($product->colors->isNotEmpty())
                    <div class="mb-8">
                        <h3 class="text-sm font-semibold mb-3">Color</h3>
                        <div class="flex flex-wrap gap-3" id="color-selector">
                            @foreach($product->colors as $color)
                            <button onclick="selectColor(this, '{{ $color->color }}')" 
                                    class="w-10 h-10 rounded-full border-2 {{ $loop->first ? 'border-[#C8A951] ring-2 ring-[#C8A951]/30' : 'border-gray-300 dark:border-gray-600' }} transition-all hover:scale-110"
                                    style="background-color: {{ $color->color_code ?? '#000' }}"
                                    title="{{ $color->color }}">
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Quantity & Add to Cart -->
                    <div class="flex items-center gap-4 mb-8">
                        <div class="flex items-center border-2 border-gray-200 dark:border-gray-700 rounded-xl">
                            <button onclick="updateQty(-1)" class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">-</button>
                            <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock }}" 
                                   class="w-16 text-center border-x border-gray-200 dark:border-gray-700 py-3 bg-transparent">
                            <button onclick="updateQty(1)" class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">+</button>
                        </div>
                        <span class="text-sm text-gray-500">Stock: {{ $product->stock }}</span>
                    </div>

                    <div class="flex gap-3">
                        <button onclick="handleAddToCart()" 
                                class="flex-1 bg-gray-900 hover:bg-black dark:bg-white dark:hover:bg-gray-200 text-white dark:text-gray-900 px-8 py-4 rounded-xl font-medium transition-all duration-300 hover:scale-[1.02]">
                            Add to Cart
                        </button>
                        <button onclick="toggleWishlist({{ $product->id }}, this)"
                                class="px-4 py-4 border-2 border-gray-200 dark:border-gray-700 rounded-xl hover:border-red-300 hover:text-red-500 transition-all duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </button>
                    </div>

                    <!-- Additional Info -->
                    <div class="mt-8 p-6 bg-gray-50 dark:bg-[#1a1a1a] rounded-xl space-y-3">
                        <div class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-400">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Free shipping on orders over Rp 500k
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-400">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            30-day easy returns
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-400">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Secure checkout
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews -->
            <div class="mt-16">
                <h2 class="text-2xl font-bold mb-8">Customer Reviews</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    @forelse($product->reviews as $review)
                    <div class="p-6 bg-gray-50 dark:bg-[#1a1a1a] rounded-xl">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-[#C8A951] flex items-center justify-center text-white font-medium text-sm">
                                    {{ substr($review->user->name ?? 'U', 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-medium text-sm">{{ $review->user->name ?? 'Anonymous' }}</p>
                                    <p class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="flex gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $review->comment }}</p>
                    </div>
                    @empty
                    <div class="md:col-span-2 text-center py-12 text-gray-500">No reviews yet.</div>
                    @endforelse
                </div>

                <!-- Write Review -->
                @auth
                <div class="bg-gray-50 dark:bg-[#1a1a1a] rounded-xl p-8">
                    <h3 class="text-lg font-semibold mb-4">Write a Review</h3>
                    <form action="{{ route('review.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Rating</label>
                            <div class="flex gap-1" id="rating-input">
                                @for($i = 1; $i <= 5; $i++)
                                <button type="button" onclick="setRating({{ $i }})" class="rating-star p-1">
                                    <svg class="w-8 h-8 text-gray-300 hover:text-yellow-400 transition-colors" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </button>
                                @endfor
                                <input type="hidden" name="rating" id="rating-value" value="5">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-2">Comment</label>
                            <textarea name="comment" rows="3" class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-800 px-4 py-3" placeholder="Share your thoughts..."></textarea>
                        </div>
                        <button type="submit" class="btn-primary">Submit Review</button>
                    </form>
                </div>
                @endauth
            </div>

            <!-- Related Products -->
            @if($related && $related->isNotEmpty())
            <div class="mt-16">
                <x-section-title title="Related Products" subtitle="Products you might also like" />
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($related as $relatedItem)
                        <x-product-card :product="$relatedItem" />
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        let selectedSize = '{{ $product->sizes->first()->size ?? "" }}';
        let selectedColor = '{{ $product->colors->first()->color ?? "" }}';

        function selectSize(btn, size) {
            document.querySelectorAll('#size-selector button').forEach(b => {
                b.classList.remove('border-[#C8A951]', 'bg-[#C8A951]/10');
                b.classList.add('border-gray-200', 'dark:border-gray-700');
            });
            btn.classList.remove('border-gray-200', 'dark:border-gray-700');
            btn.classList.add('border-[#C8A951]', 'bg-[#C8A951]/10');
            selectedSize = size;
        }

        function selectColor(btn, color) {
            document.querySelectorAll('#color-selector button').forEach(b => {
                b.classList.remove('border-[#C8A951]', 'ring-2', 'ring-[#C8A951]/30');
                b.classList.add('border-gray-300', 'dark:border-gray-600');
            });
            btn.classList.remove('border-gray-300', 'dark:border-gray-600');
            btn.classList.add('border-[#C8A951]', 'ring-2', 'ring-[#C8A951]/30');
            selectedColor = color;
        }

        function updateQty(delta) {
            const input = document.getElementById('quantity');
            const val = parseInt(input.value) + delta;
            if (val >= 1 && val <= {{ $product->stock }}) input.value = val;
        }

        function handleAddToCart() {
            addToCart({{ $product->id }}, selectedSize, selectedColor, parseInt(document.getElementById('quantity').value));
        }

        function changeImage(btn) {
            document.getElementById('main-image').src = btn.dataset.src;
            document.querySelectorAll('[data-src]').forEach(b => b.classList.remove('border-[#C8A951]'));
            btn.classList.add('border-[#C8A951]');
        }

        function setRating(val) {
            document.getElementById('rating-value').value = val;
            document.querySelectorAll('.rating-star svg').forEach((svg, i) => {
                svg.classList.toggle('text-yellow-400', i < val);
                svg.classList.toggle('text-gray-300', i >= val);
            });
        }
    </script>
    @endpush
</x-app-layout>
