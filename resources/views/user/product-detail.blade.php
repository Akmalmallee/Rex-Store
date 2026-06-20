<x-app-layout>
    <div class="pt-24 pb-16 bg-[#0a0a0a] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-10 text-[10px] tracking-widest uppercase text-gray-600 font-light reveal">
                <a href="{{ route('home') }}" class="hover:text-[#C8A951] transition-colors">Home</a>
                <span class="mx-2 text-gray-700">·</span>
                <a href="{{ route('shop') }}" class="hover:text-[#C8A951] transition-colors">Shop</a>
                @if($product->category)
                <span class="mx-2 text-gray-700">·</span>
                <a href="{{ route('shop') }}?category={{ $product->category->slug }}" class="hover:text-[#C8A951] transition-colors">{{ $product->category->name }}</a>
                @endif
                <span class="mx-2 text-gray-700">·</span>
                <span class="text-white/60">{{ $product->name }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16">
                <!-- Gallery -->
                <div class="reveal-left">
                    @php
                        $cacheBuster = '?t=' . $product->updated_at->timestamp;
                        $imageUrl = $product->thumbnail 
                            ? (str_starts_with($product->thumbnail, 'http') ? $product->thumbnail : Storage::url($product->thumbnail)) . $cacheBuster
                            : ($product->images->first() 
                                ? (str_starts_with($product->images->first()->image, 'http') ? $product->images->first()->image : Storage::url($product->images->first()->image))
                                : 'https://picsum.photos/seed/product-'.$product->id.'/800/1000');
                    @endphp
                     <div class="aspect-[3/4] overflow-hidden bg-[#111] mb-4 relative group">
                        <img id="main-image" src="{{ $imageUrl }}" alt="{{ $product->name }}" 
                             class="w-full h-full object-cover cursor-crosshair transition-transform duration-700 img-fade"
                             onmousemove="zoomImage(event)" onmouseleave="resetZoom(event)">
                        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                            <div class="absolute top-3 left-3 text-[10px] tracking-widest uppercase bg-black/60 backdrop-blur-sm px-2.5 py-1 text-gray-400 font-light">Hover to zoom</div>
                        </div>
                    </div>
                    @if($product->images->isNotEmpty())
                    <div class="grid grid-cols-4 gap-3 thumb-group">
                        @foreach($product->images as $image)
                        @php 
                            $imgSrc = str_starts_with($image->image, 'http') ? $image->image : Storage::url($image->image);
                            $imgSrcWithCache = str_starts_with($image->image, 'http') ? $imgSrc : ($imgSrc . $cacheBuster);
                        @endphp
                        <button onclick="changeImage(this)" 
                                data-src="{{ $imgSrcWithCache }}"
                                class="aspect-square overflow-hidden border thumb-prestige {{ $loop->first ? 'active border-[#C8A951]' : 'border-white/5' }}">
                            <img src="{{ $imgSrc . $cacheBuster }}" 
                                 alt="" class="w-full h-full object-cover">
                        </button>
                        @endforeach
                    </div>
                    @endif

                    @if($product->productModel && $product->productModel->is_active)
                    <button onclick="open3DViewer({
                        model_url: '{{ Storage::url($product->productModel->model_file) }}',
                        name: '{{ $product->name }}',
                    })" class="w-full mt-4 text-xs tracking-widest uppercase btn-luxury-outline px-5 py-3 inline-flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        3D Preview
                    </button>
                    @endif
                </div>

                <!-- Info -->
                <div class="reveal-right">
                    @if($product->category)
                    <p class="text-[10px] tracking-[0.3em] uppercase text-gray-500 font-light mb-3">{{ $product->category->name }}</p>
                    @endif
                    <h1 class="text-4xl md:text-5xl font-light tracking-tight text-white mb-6">{{ $product->name }}</h1>
                    
                    <div class="flex items-center gap-4 mb-8">
                        <div class="flex items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= round($product->rating) ? 'text-[#C8A951]' : 'text-gray-700' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                            <span class="text-xs font-light text-gray-500 ml-2">({{ $product->reviews->count() }} reviews)</span>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="mb-8">
                        @if($product->discount > 0)
                            @php $finalPrice = $product->price - ($product->price * $product->discount / 100); @endphp
                            <div class="flex items-center gap-4">
                                <span class="text-3xl font-light text-white">Rp {{ number_format($finalPrice, 0, ',', '.') }}</span>
                                <span class="text-sm font-light text-gray-600 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <span class="text-[10px] tracking-widest uppercase font-light text-[#C8A951] border border-[#C8A951]/30 px-3 py-0.5">-{{ $product->discount }}%</span>
                            </div>
                        @else
                            <span class="text-3xl font-light text-white">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        @endif
                    </div>

                    <!-- Description -->
                    <p class="text-sm font-light text-gray-500 leading-relaxed mb-10">{{ $product->description }}</p>

                    <!-- Size -->
                    @if($product->sizes->isNotEmpty())
                    <div class="mb-8">
                        <p class="label-luxury">Size</p>
                        <div class="flex flex-wrap gap-2" id="size-selector">
                            @foreach($product->sizes as $size)
                            <button onclick="selectSize(this, '{{ $size->size }}')" 
                                    class="px-6 py-3 border border-white/10 text-xs font-light hover:border-[#C8A951] transition-colors
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
                        <p class="label-luxury">Color</p>
                        <div class="flex flex-wrap gap-3" id="color-selector">
                            @foreach($product->colors as $color)
                            <button onclick="selectColor(this, '{{ $color->color }}')" 
                                    class="w-10 h-10 border {{ $loop->first ? 'border-[#C8A951] ring-2 ring-[#C8A951]/30' : 'border-white/10' }} transition-all hover:scale-110"
                                    style="background-color: {{ $color->color_code ?? '#000' }}"
                                    title="{{ $color->color }}">
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Quantity & Add to Cart -->
                    <div class="flex items-center gap-4 mb-10">
                        <div class="flex items-center border border-white/10">
                            <button onclick="updateQty(-1)" class="px-4 py-3 hover:bg-white/5 transition-colors text-xs font-light text-gray-400">−</button>
                            <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock }}" 
                                   class="w-14 text-center border-x border-white/10 py-3 bg-transparent text-white text-xs font-light focus:outline-none">
                            <button onclick="updateQty(1)" class="px-4 py-3 hover:bg-white/5 transition-colors text-xs font-light text-gray-400">+</button>
                        </div>
                        <span class="text-xs font-light text-gray-600">Stock: {{ $product->stock }}</span>
                    </div>

                    <div class="flex gap-3">
                        <button onclick="handleAddToCart()" 
                                class="flex-1 bg-[#C8A951] text-black text-sm tracking-widest uppercase font-medium px-8 py-4 hover:bg-white transition-all duration-500">
                            Add to Cart
                        </button>
                        <button onclick="toggleWishlist({{ $product->id }}, this)"
                                class="px-5 py-4 border border-white/10 hover:border-red-500/30 hover:text-red-400 transition-all duration-300 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </button>
                    </div>

                    <!-- Additional Info -->
                    <div class="mt-10 space-y-3">
                        <div class="flex items-center gap-3 text-xs font-light text-gray-500">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
                            Free shipping on orders over Rp 500k
                        </div>
                        <div class="flex items-center gap-3 text-xs font-light text-gray-500">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
                            30-day easy returns
                        </div>
                        <div class="flex items-center gap-3 text-xs font-light text-gray-500">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
                            Secure checkout
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews -->
            <div class="mt-20">
                <p class="text-xs tracking-[0.3em] uppercase text-gray-500 font-light mb-10 reveal">Customer Reviews</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                    @forelse($product->reviews as $review)
                    <div class="glass-card p-6 reveal">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 border border-white/10 flex items-center justify-center text-white text-xs font-light">
                                    {{ substr($review->user->name ?? 'U', 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-xs font-light text-white/80">{{ $review->user->name ?? 'Anonymous' }}</p>
                                    <p class="text-[10px] text-gray-500 font-light">{{ $review->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="flex gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'text-[#C8A951]' : 'text-gray-700' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                        </div>
                        <p class="text-xs font-light text-gray-400 leading-relaxed">{{ $review->comment }}</p>
                    </div>
                    @empty
                    <div class="md:col-span-2 text-center py-12 text-sm font-light text-gray-500 reveal">No reviews yet.</div>
                    @endforelse
                </div>

                <!-- Write Review -->
                @auth
                <div class="glass-card p-8 reveal">
                    <p class="text-xs tracking-widest uppercase font-light text-gray-300 mb-6">Write a Review</p>
                    <form action="{{ route('review.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="mb-6">
                            <label class="label-luxury">Rating</label>
                            <div class="flex gap-1" id="rating-input">
                                @for($i = 1; $i <= 5; $i++)
                                <button type="button" onclick="setRating({{ $i }})" class="rating-star p-1">
                                    <svg class="w-6 h-6 text-gray-700 hover:text-[#C8A951] transition-colors" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </button>
                                @endfor
                                <input type="hidden" name="rating" id="rating-value" value="5">
                            </div>
                        </div>
                        <div class="mb-6">
                            <label class="label-luxury">Comment</label>
                            <textarea name="comment" rows="3" class="input-luxury" placeholder="Share your thoughts..."></textarea>
                        </div>
                        <button type="submit" class="btn-luxury">Submit Review</button>
                    </form>
                </div>
                @endauth
            </div>

            <!-- Related Products -->
            @if($related && $related->isNotEmpty())
            <div class="mt-20">
                <p class="text-xs tracking-[0.3em] uppercase text-gray-500 font-light mb-10 reveal">You May Also Like</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($related as $relatedItem)
                        <div class="reveal">
                            <x-product-card :product="$relatedItem" />
                        </div>
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
                b.classList.add('border-white/10');
            });
            btn.classList.remove('border-white/10');
            btn.classList.add('border-[#C8A951]', 'bg-[#C8A951]/10');
            selectedSize = size;
        }

        function selectColor(btn, color) {
            document.querySelectorAll('#color-selector button').forEach(b => {
                b.classList.remove('border-[#C8A951]', 'ring-2', 'ring-[#C8A951]/30');
                b.classList.add('border-white/10');
            });
            btn.classList.remove('border-white/10');
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
            const mainImg = document.getElementById('main-image');
            mainImg.style.opacity = '0';
            setTimeout(() => {
                mainImg.src = btn.dataset.src;
                mainImg.style.opacity = '1';
            }, 200);
            document.querySelectorAll('[data-src]').forEach(b => {
                b.classList.remove('active', 'border-[#C8A951]');
                b.classList.add('border-white/5');
            });
            btn.classList.remove('border-white/5');
            btn.classList.add('active', 'border-[#C8A951]');
        }

        function setRating(val) {
            document.getElementById('rating-value').value = val;
            document.querySelectorAll('.rating-star svg').forEach((svg, i) => {
                svg.classList.toggle('text-[#C8A951]', i < val);
                svg.classList.toggle('text-gray-700', i >= val);
            });
        }

        @if($product->productModel && $product->productModel->is_active)
        @endif
    </script>
    @endpush

    @if($product->productModel && $product->productModel->is_active)
        <x-product-3d-viewer :product="$product" />
    @endif

    <x-fitting-ai-button :product="$product" />
    <x-fitting-modal :product="$product" :profile="$profile ?? null" :recommendations="$recommendations ?? null" />
</x-app-layout>
