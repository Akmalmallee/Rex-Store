@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-500 hover:text-[#C8A951]">&larr; Back to Products</a>
        <h1 class="text-2xl font-bold mt-2">Edit Product</h1>
    </div>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-[#1a1a1a] rounded-xl p-6 shadow-sm space-y-6">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">Product Name</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" class="input-field" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" class="input-field">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Category</label>
                <select name="category_id" class="input-field" required>
                    @foreach(App\Models\Category::all() as $cat)
                    <option value="{{ $cat->id }}" {{ (old('category_id', $product->category_id) == $cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Brand</label>
                <select name="brand_id" class="input-field">
                    <option value="">Select Brand</option>
                    @foreach(App\Models\Brand::all() as $brand)
                    <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Price</label>
                <input type="number" name="price" value="{{ old('price', $product->price) }}" class="input-field" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Discount (%)</label>
                <input type="number" name="discount" value="{{ old('discount', $product->discount) }}" class="input-field" min="0" max="100">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Stock</label>
                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="input-field" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Status</label>
                <select name="is_active" class="input-field">
                    <option value="1" {{ old('is_active', $product->is_active) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('is_active', $product->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Description</label>
                <textarea name="description" rows="4" class="input-field">{{ old('description', $product->description) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Thumbnail</label>
                @if($product->thumbnail)
                <div class="w-20 h-20 rounded-xl overflow-hidden mb-2 bg-gray-100">
                    @php $thumbUrl = str_starts_with($product->thumbnail, 'http') ? $product->thumbnail : Storage::url($product->thumbnail); @endphp
                    <img src="{{ $thumbUrl . '?t=' . $product->updated_at->timestamp }}" alt="" class="w-full h-full object-cover">
                </div>
                @endif
                <input type="file" name="thumbnail" accept="image/*" class="input-field">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Additional Images</label>
                @if($product->images->isNotEmpty())
                <div class="flex flex-wrap gap-2 mb-2">
                    @foreach($product->images as $img)
                    <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-100 relative group">
                        <img src="{{ str_starts_with($img->image, 'http') ? $img->image : Storage::url($img->image) . '?t=' . $product->updated_at->timestamp }}" alt="" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <label class="text-white text-xs cursor-pointer">
                                <input type="checkbox" name="delete_images[]" value="{{ $img->id }}" class="sr-only">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
                <p class="text-xs text-gray-400 mb-1">Hover over image and click X to mark for deletion</p>
                @endif
                <input type="file" name="images[]" multiple accept="image/*" class="input-field">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Sizes</label>
                <input type="text" name="sizes" value="{{ old('sizes', $product->sizes->pluck('size')->join(', ')) }}" placeholder="S, M, L, XL" class="input-field">
            </div>
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.products.index') }}" class="px-6 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl text-sm">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-[#C8A951] hover:bg-[#b8963e] text-white rounded-xl text-sm transition-colors">Update Product</button>
        </div>
    </form>
</div>
@endsection
