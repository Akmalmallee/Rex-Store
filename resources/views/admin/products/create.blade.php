@extends('layouts.admin')

@section('title', 'Create Product')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <a href="{{ route('admin.products.index') }}" class="text-xs tracking-widest uppercase font-light text-gray-500 hover:text-[#C8A951]">&larr; Back to Products</a>
        <h1 class="text-3xl font-light tracking-tight text-white mt-2">Create Product</h1>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="glass-card p-8 space-y-8">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <label class="label-luxury">Product Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="input-luxury" required>
                @error('name') <p class="text-red-400 text-[10px] font-light mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="label-luxury">Slug</label>
                <input type="text" name="slug" value="{{ old('slug') }}" class="input-luxury">
                @error('slug') <p class="text-red-400 text-[10px] font-light mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="label-luxury">Category</label>
                <select name="category_id" class="bg-transparent border-0 border-b border-white/10 text-white text-xs font-light py-3 w-full focus:border-[#C8A951] focus:ring-0 focus:outline-none" required>
                    <option value="" class="bg-[#0a0a0a]">Select Category</option>
                    @foreach(App\Models\Category::all() as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }} class="bg-[#0a0a0a]">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label-luxury">Brand</label>
                <select name="brand_id" class="bg-transparent border-0 border-b border-white/10 text-white text-xs font-light py-3 w-full focus:border-[#C8A951] focus:ring-0 focus:outline-none">
                    <option value="" class="bg-[#0a0a0a]">Select Brand</option>
                    @foreach(App\Models\Brand::all() as $brand)
                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }} class="bg-[#0a0a0a]">{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label-luxury">Price</label>
                <input type="number" name="price" value="{{ old('price') }}" class="input-luxury" required>
            </div>
            <div>
                <label class="label-luxury">Discount (%)</label>
                <input type="number" name="discount" value="{{ old('discount', 0) }}" class="input-luxury" min="0" max="100">
            </div>
            <div>
                <label class="label-luxury">Stock</label>
                <input type="number" name="stock" value="{{ old('stock', 0) }}" class="input-luxury" required>
            </div>
            <div>
                <label class="label-luxury">Status</label>
                <select name="is_active" class="bg-transparent border-0 border-b border-white/10 text-white text-xs font-light py-3 w-full focus:border-[#C8A951] focus:ring-0 focus:outline-none">
                    <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }} class="bg-[#0a0a0a]">Active</option>
                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }} class="bg-[#0a0a0a]">Inactive</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="label-luxury">Description</label>
                <textarea name="description" rows="4" class="input-luxury">{{ old('description') }}</textarea>
            </div>
            <div>
                <label class="label-luxury">Thumbnail</label>
                <input type="file" name="thumbnail" accept="image/*" class="text-xs font-light text-gray-500 file:border-0 file:text-xs file:tracking-wider file:bg-[#C8A951]/10 file:text-[#C8A951] hover:file:bg-[#C8A951]/20 file:rounded-none file:px-3 file:py-1.5 file:cursor-pointer">
            </div>
            <div>
                <label class="label-luxury">Additional Images (multiple)</label>
                <input type="file" name="images[]" multiple accept="image/*" class="text-xs font-light text-gray-500 file:border-0 file:text-xs file:tracking-wider file:bg-[#C8A951]/10 file:text-[#C8A951] hover:file:bg-[#C8A951]/20 file:rounded-none file:px-3 file:py-1.5 file:cursor-pointer">
            </div>
            <div>
                <label class="label-luxury">Sizes (comma separated)</label>
                <input type="text" name="sizes" value="{{ old('sizes') }}" placeholder="S, M, L, XL" class="input-luxury">
            </div>
            <div>
                <label class="label-luxury">Colors (comma separated)</label>
                <input type="text" name="colors" value="{{ old('colors') }}" placeholder="Hitam, Putih, Navy" class="input-luxury">
            </div>
        </div>

        <!-- 3D Model -->
        <div class="pt-4 border-t border-white/5">
            <h3 class="text-xs tracking-widest uppercase font-light text-gray-300 mb-4">3D Fashion Model</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="label-luxury">3D Model File (GLB/GLTF)</label>
                    <input type="file" name="model_file" accept=".glb,.gltf" class="text-xs font-light text-gray-500 file:border-0 file:text-xs file:tracking-wider file:bg-[#C8A951]/10 file:text-[#C8A951] hover:file:bg-[#C8A951]/20 file:rounded-none file:px-3 file:py-1.5 file:cursor-pointer">
                    <p class="text-[10px] font-light text-gray-600 mt-1">Upload a 3D model to enable interactive preview. Max 50MB.</p>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-white/5">
            <a href="{{ route('admin.products.index') }}" class="btn-luxury-outline px-6 py-3 text-xs">Cancel</a>
            <button type="submit" class="btn-luxury px-6 py-3 text-xs">Save Product</button>
        </div>
    </form>
</div>
@endsection
