@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <a href="{{ route('admin.products.index') }}" class="text-xs tracking-widest uppercase font-light text-gray-500 hover:text-[#C8A951]">&larr; Back to Products</a>
        <h1 class="text-3xl font-light tracking-tight text-white mt-2">Edit Product</h1>
    </div>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="glass-card p-8 space-y-8">
        @method('PATCH')
        @csrf 
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <label class="label-luxury">Product Name</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" class="input-luxury" required>
            </div>
            <div>
                <label class="label-luxury">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" class="input-luxury">
            </div>
            <div>
                <label class="label-luxury">Category</label>
                <select name="category_id" class="bg-transparent border-0 border-b border-white/10 text-white text-xs font-light py-3 w-full focus:border-[#C8A951] focus:ring-0 focus:outline-none" required>
                    @foreach(App\Models\Category::all() as $cat)
                    <option value="{{ $cat->id }}" {{ (old('category_id', $product->category_id) == $cat->id) ? 'selected' : '' }} class="bg-[#0a0a0a]">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label-luxury">Brand</label>
                <select name="brand_id" class="bg-transparent border-0 border-b border-white/10 text-white text-xs font-light py-3 w-full focus:border-[#C8A951] focus:ring-0 focus:outline-none">
                    <option value="" class="bg-[#0a0a0a]">Select Brand</option>
                    @foreach(App\Models\Brand::all() as $brand)
                    <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }} class="bg-[#0a0a0a]">{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label-luxury">Price</label>
                <input type="number" name="price" value="{{ old('price', $product->price) }}" class="input-luxury" required>
            </div>
            <div>
                <label class="label-luxury">Discount (%)</label>
                <input type="number" name="discount" value="{{ old('discount', $product->discount) }}" class="input-luxury" min="0" max="100">
            </div>
            <div>
                <label class="label-luxury">Stock</label>
                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="input-luxury" required>
            </div>
            <div>
                <label class="label-luxury">Status</label>
                <select name="is_active" class="bg-transparent border-0 border-b border-white/10 text-white text-xs font-light py-3 w-full focus:border-[#C8A951] focus:ring-0 focus:outline-none">
                    <option value="1" {{ old('is_active', $product->is_active) == 1 ? 'selected' : '' }} class="bg-[#0a0a0a]">Active</option>
                    <option value="0" {{ old('is_active', $product->is_active) == 0 ? 'selected' : '' }} class="bg-[#0a0a0a]">Inactive</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="label-luxury">Description</label>
                <textarea name="description" rows="4" class="input-luxury">{{ old('description', $product->description) }}</textarea>
            </div>
            <div>
                <label class="label-luxury">Thumbnail</label>
                @if($product->thumbnail)
                <div class="w-20 h-20 overflow-hidden mb-2 bg-white/5">
                    @php $thumbUrl = str_starts_with($product->thumbnail, 'http') ? $product->thumbnail : Storage::url($product->thumbnail); @endphp
                    <img src="{{ $thumbUrl . '?t=' . $product->updated_at->timestamp }}" alt="" class="w-full h-full object-cover">
                </div>
                @endif
                <input type="file" name="thumbnail" accept="image/*" class="text-xs font-light text-gray-500 file:border-0 file:text-xs file:tracking-wider file:bg-[#C8A951]/10 file:text-[#C8A951] hover:file:bg-[#C8A951]/20 file:rounded-none file:px-3 file:py-1.5 file:cursor-pointer">
            </div>
            <div>
                <label class="label-luxury">Additional Images</label>
                @if($product->images->isNotEmpty())
                <div class="flex flex-wrap gap-2 mb-2">
                    @foreach($product->images as $img)
                    <div class="w-16 h-16 overflow-hidden bg-white/5 relative group">
                        <img src="{{ str_starts_with($img->image, 'http') ? $img->image : Storage::url($img->image) . '?t=' . $product->updated_at->timestamp }}" alt="" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <label class="text-white text-xs cursor-pointer">
                                <input type="checkbox" name="delete_images[]" value="{{ $img->id }}" class="sr-only">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
                <p class="text-xs font-light text-gray-600 mb-1">Hover and click X to mark for deletion</p>
                @endif
                <input type="file" name="images[]" multiple accept="image/*" class="text-xs font-light text-gray-500 file:border-0 file:text-xs file:tracking-wider file:bg-[#C8A951]/10 file:text-[#C8A951] hover:file:bg-[#C8A951]/20 file:rounded-none file:px-3 file:py-1.5 file:cursor-pointer">
            </div>
            <div>
                <label class="label-luxury">Sizes (comma separated)</label>
                <input type="text" name="sizes" value="{{ old('sizes', $product->sizes->pluck('size')->join(', ')) }}" placeholder="S, M, L, XL" class="input-luxury">
            </div>
            <div>
                <label class="label-luxury">Colors (comma separated)</label>
                <input type="text" name="colors" value="{{ old('colors', $product->colors->pluck('color')->join(', ')) }}" placeholder="Hitam, Putih, Navy" class="input-luxury">
            </div>
        </div>

        <!-- 3D Model -->
        <div class="pt-4 border-t border-white/5">
            <h3 class="text-xs tracking-widest uppercase font-light text-gray-300 mb-4">3D Fashion Model</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="label-luxury">3D Model File (GLB/GLTF)</label>
                    @if($product->productModel)
                    <div class="mb-3 p-3 glass-card flex items-center gap-3">
                        <svg class="w-8 h-8 text-[#C8A951]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <div>
                            <p class="text-xs font-light text-white/80">3D model uploaded</p>
                            <p class="text-[10px] font-light text-gray-500">{{ basename($product->productModel->model_file) }}</p>
                        </div>
                    </div>
                    @endif
                    <input type="file" name="model_file" accept=".glb,.gltf" class="text-xs font-light text-gray-500 file:border-0 file:text-xs file:tracking-wider file:bg-[#C8A951]/10 file:text-[#C8A951] hover:file:bg-[#C8A951]/20 file:rounded-none file:px-3 file:py-1.5 file:cursor-pointer">
                    <p class="text-[10px] font-light text-gray-600 mt-1">Upload replaces existing model. Max 50MB. GLB/GLTF only.</p>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-white/5">
            <a href="{{ route('admin.products.index') }}" class="btn-luxury-outline px-6 py-3 text-xs">Cancel</a>
            <button type="submit" class="btn-luxury px-6 py-3 text-xs">Update Product</button>
        </div>
    </form>
</div>
@endsection
