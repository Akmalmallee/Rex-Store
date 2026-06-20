@extends('layouts.admin')

@section('title', 'Products')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-light tracking-tight text-white">Products</h1>
            <p class="text-xs tracking-[0.3em] uppercase text-gray-500 font-light mt-1">Manage your product catalog</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="text-xs tracking-widest uppercase btn-luxury px-5 py-2.5 inline-flex items-center gap-2">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Product
        </a>
    </div>

    <div class="glass-card overflow-hidden">
        <div class="p-4 border-b border-white/5">
            <form method="GET" class="flex flex-wrap gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="bg-white/5 border border-white/10 text-white text-xs font-light rounded-none px-4 py-2.5 placeholder:text-gray-600 focus:outline-none focus:border-[#C8A951]">
                <select name="category" class="bg-white/5 border border-white/10 text-white text-xs font-light rounded-none px-4 py-2.5 focus:outline-none focus:border-[#C8A951]">
                    <option value="" class="bg-[#0a0a0a]">All Categories</option>
                    @foreach(App\Models\Category::all() as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }} class="bg-[#0a0a0a]">{{ $cat->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="bg-white/5 border border-white/10 text-white text-xs font-light rounded-none px-4 py-2.5 focus:outline-none focus:border-[#C8A951]">
                    <option value="" class="bg-[#0a0a0a]">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }} class="bg-[#0a0a0a]">Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }} class="bg-[#0a0a0a]">Inactive</option>
                </select>
                <button type="submit" class="text-xs tracking-widest uppercase px-5 py-2.5 bg-white/5 text-white/80 border border-white/10 hover:border-[#C8A951] hover:text-[#C8A951] transition-all duration-500">Filter</button>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-white/[0.02] border-b border-white/5">
                        <th class="text-left py-3 px-4 text-[10px] tracking-widest uppercase font-light text-gray-500">Product</th>
                        <th class="text-left py-3 px-4 text-[10px] tracking-widest uppercase font-light text-gray-500">Category</th>
                        <th class="text-left py-3 px-4 text-[10px] tracking-widest uppercase font-light text-gray-500">Price</th>
                        <th class="text-left py-3 px-4 text-[10px] tracking-widest uppercase font-light text-gray-500">Stock</th>
                        <th class="text-left py-3 px-4 text-[10px] tracking-widest uppercase font-light text-gray-500">Status</th>
                        <th class="text-right py-3 px-4 text-[10px] tracking-widest uppercase font-light text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr class="border-b border-white/5 hover:bg-white/[0.02]">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-white/5 overflow-hidden shrink-0">
                                    @php $img = $product->thumbnail ? (str_starts_with($product->thumbnail, 'http') ? $product->thumbnail : Storage::url($product->thumbnail) . '?t=' . $product->updated_at->timestamp) : 'https://picsum.photos/seed/'.$product->id.'/100/100'; @endphp
                                    <img src="{{ $img }}" alt="" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="text-sm font-light text-white/90">{{ $product->name }}</p>
                                    <p class="text-xs font-light text-gray-500">{{ $product->slug }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-xs font-light text-gray-500">{{ $product->category->name ?? '-' }}</td>
                        <td class="py-3 px-4 text-xs font-light">
                            @if($product->discount > 0)
                                <span class="text-[#C8A951]">Rp {{ number_format($product->finalPrice, 0, ',', '.') }}</span>
                                <span class="text-xs text-gray-600 line-through ml-1">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            @else
                                <span class="text-white/80">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-xs font-light {{ $product->stock > 10 ? 'text-emerald-400' : ($product->stock > 0 ? 'text-yellow-400' : 'text-red-400') }}">{{ $product->stock }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-[10px] tracking-wider px-2 py-0.5 font-light {{ $product->is_active ? 'border border-emerald-500/30 text-emerald-400' : 'border border-white/10 text-gray-500' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="text-[10px] tracking-wider uppercase font-light text-gray-500 hover:text-[#C8A951] transition-colors">
                                    Edit
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this product?')">
                                    @csrf @method('DELETE')
                                    <button class="text-[10px] tracking-wider uppercase font-light text-gray-500 hover:text-red-400 transition-colors">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="py-12 text-center text-xs font-light text-gray-500">No products found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-white/5">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
