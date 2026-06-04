@extends('layouts.admin')

@section('title', 'Create Product')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-500 hover:text-[#C8A951]">&larr; Back to Products</a>
        <h1 class="text-2xl font-bold mt-2">Create Product</h1>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-[#1a1a1a] rounded-xl p-6 shadow-sm space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">Product Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="input-field" required>
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Slug</label>
                <input type="text" name="slug" value="{{ old('slug') }}" class="input-field">
                @error('slug') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Category</label>
                <select name="category_id" class="input-field" required>
                    <option value="">Select Category</option>
                    @foreach(App\Models\Category::all() as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Brand</label>
                <select name="brand_id" class="input-field">
                    <option value="">Select Brand</option>
                    @foreach(App\Models\Brand::all() as $brand)
                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Price</label>
                <input type="number" name="price" value="{{ old('price') }}" class="input-field" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Discount (%)</label>
                <input type="number" name="discount" value="{{ old('discount', 0) }}" class="input-field" min="0" max="100">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Stock</label>
                <input type="number" name="stock" value="{{ old('stock', 0) }}" class="input-field" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Status</label>
                <select name="is_active" class="input-field">
                    <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Description</label>
                <textarea name="description" rows="4" class="input-field">{{ old('description') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Thumbnail</label>
                <input type="file" name="thumbnail" accept="image/*" class="input-field">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Additional Images (multiple)</label>
                <input type="file" name="images[]" multiple accept="image/*" class="input-field">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Sizes (comma separated)</label>
                <input type="text" name="sizes" value="{{ old('sizes') }}" placeholder="S, M, L, XL" class="input-field">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Colors (comma separated)</label>
                <input type="text" name="colors" value="{{ old('colors') }}" placeholder="Hitam, Putih, Navy" class="input-field">
            </div>
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.products.index') }}" class="px-6 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl text-sm">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-[#C8A951] hover:bg-[#b8963e] text-white rounded-xl text-sm transition-colors">Save Product</button>
        </div>
    </form>
</div>
@endsection
