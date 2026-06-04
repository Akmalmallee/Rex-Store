@extends('layouts.admin')

@section('title', 'Products')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Products</h1>
            <p class="text-gray-500 dark:text-gray-400">Manage your product catalog</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="bg-[#C8A951] hover:bg-[#b8963e] text-white px-4 py-2 rounded-xl text-sm font-medium transition-colors inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Product
        </a>
    </div>

    <div class="bg-white dark:bg-[#1a1a1a] rounded-xl shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-800">
            <form method="GET" class="flex flex-wrap gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 text-sm">
                <select name="category" class="px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 text-sm">
                    <option value="">All Categories</option>
                    @foreach(App\Models\Category::all() as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 text-sm">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-900 dark:bg-white dark:text-gray-900 text-white rounded-xl text-sm">Filter</button>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900">
                        <th class="text-left py-3 px-4 font-medium">Product</th>
                        <th class="text-left py-3 px-4 font-medium">Category</th>
                        <th class="text-left py-3 px-4 font-medium">Price</th>
                        <th class="text-left py-3 px-4 font-medium">Stock</th>
                        <th class="text-left py-3 px-4 font-medium">Status</th>
                        <th class="text-right py-3 px-4 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-800 overflow-hidden shrink-0">
                                    @php $img = $product->thumbnail ? (str_starts_with($product->thumbnail, 'http') ? $product->thumbnail : Storage::url($product->thumbnail) . '?t=' . $product->updated_at->timestamp) : 'https://picsum.photos/seed/'.$product->id.'/100/100'; @endphp
                                    <img src="{{ $img }}" alt="" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="font-medium">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $product->slug }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-gray-500">{{ $product->category->name ?? '-' }}</td>
                        <td class="py-3 px-4">
                            @if($product->discount > 0)
                                <span class="text-[#C8A951] font-medium">Rp {{ number_format($product->finalPrice, 0, ',', '.') }}</span>
                                <span class="text-xs text-gray-400 line-through ml-1">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            @else
                                <span>Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <span class="{{ $product->stock > 10 ? 'text-green-500' : ($product->stock > 0 ? 'text-yellow-500' : 'text-red-500') }}">{{ $product->stock }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $product->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900/30' : 'bg-gray-100 text-gray-500 dark:bg-gray-800' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="p-2 text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this product?')">
                                    @csrf @method('DELETE')
                                    <button class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="py-12 text-center text-gray-500">No products found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200 dark:border-gray-800">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
