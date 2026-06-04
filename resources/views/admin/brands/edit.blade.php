@extends('layouts.admin')
@section('title', 'Edit Brand')
@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Brand</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Update brand information</p>
    </div>

    <div class="bg-white dark:bg-gray-800/50 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/50">
        <form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Brand Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $brand->name) }}" required
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-[#C8A951] focus:ring-[#C8A951] @error('name') border-red-500 @enderror">
                @error('name') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Slug</label>
                <input type="text" id="slug" name="slug" value="{{ old('slug', $brand->slug) }}" required
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-[#C8A951] focus:ring-[#C8A951] @error('slug') border-red-500 @enderror">
                @error('slug') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Logo</label>
                @if($brand->logo)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="w-20 h-20 rounded-lg object-cover">
                </div>
                @endif
                <input type="file" id="logo" name="logo" accept="image/jpeg,image/png,image/jpg,image/webp"
                    class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-[#C8A951]/10 file:text-[#C8A951] hover:file:bg-[#C8A951]/20 transition-colors">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave empty to keep current logo. JPEG, PNG, JPG, or WebP. Max 2MB.</p>
                @error('logo') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $brand->is_active) ? 'checked' : '' }}
                    class="rounded border-gray-300 dark:border-gray-600 text-[#C8A951] focus:ring-[#C8A951]">
                <label for="is_active" class="text-sm font-medium text-gray-700 dark:text-gray-300">Active</label>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700/50">
                <button type="submit" class="px-6 py-2.5 bg-[#C8A951] hover:bg-[#b89a42] text-white text-sm font-medium rounded-xl transition-colors duration-200 shadow-sm">
                    Update Brand
                </button>
                <a href="{{ route('admin.brands.index') }}" class="px-6 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
