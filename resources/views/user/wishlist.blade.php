<x-app-layout>
    <div class="pt-24 pb-16 min-h-screen bg-gray-50 dark:bg-[#111]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold mb-8">My Wishlist</h1>
            @if($wishlists && $wishlists->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($wishlists as $item)
                    <x-product-card :product="$item->product" />
                @endforeach
            </div>
            @else
            <div class="text-center py-20">
                <svg class="w-20 h-20 mx-auto text-gray-300 dark:text-gray-600 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <h3 class="text-xl font-medium text-gray-500 mb-2">Wishlist is empty</h3>
                <p class="text-gray-400 mb-6">Save your favorite items here</p>
                <a href="{{ route('shop') }}" class="btn-primary inline-flex">Explore Products</a>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
