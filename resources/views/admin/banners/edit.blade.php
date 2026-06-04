@extends('layouts.admin')
@section('title', 'Edit Banner')
@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Banner</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Update banner information</p>
    </div>

    <div class="bg-white dark:bg-gray-800/50 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/50">
        <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
                <input type="text" id="title" name="title" value="{{ old('title', $banner->title) }}" required
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-[#C8A951] focus:ring-[#C8A951] @error('title') border-red-500 @enderror">
                @error('title') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="subtitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subtitle</label>
                <input type="text" id="subtitle" name="subtitle" value="{{ old('subtitle', $banner->subtitle) }}"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-[#C8A951] focus:ring-[#C8A951] @error('subtitle') border-red-500 @enderror">
                @error('subtitle') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Image</label>
                @if($banner->image)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}" class="w-48 h-28 rounded-lg object-cover">
                </div>
                @endif
                <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/webp"
                    class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-[#C8A951]/10 file:text-[#C8A951] hover:file:bg-[#C8A951]/20 transition-colors">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave empty to keep current image. JPEG, PNG, JPG, or WebP. Max 2MB.</p>
                @error('image') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="link" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Link URL</label>
                <input type="text" id="link" name="link" value="{{ old('link', $banner->link) }}"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-[#C8A951] focus:ring-[#C8A951] @error('link') border-red-500 @enderror">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Optional. Where the banner should link to.</p>
                @error('link') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $banner->is_active) ? 'checked' : '' }}
                    class="rounded border-gray-300 dark:border-gray-600 text-[#C8A951] focus:ring-[#C8A951]">
                <label for="is_active" class="text-sm font-medium text-gray-700 dark:text-gray-300">Active</label>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700/50">
                <button type="submit" class="px-6 py-2.5 bg-[#C8A951] hover:bg-[#b89a42] text-white text-sm font-medium rounded-xl transition-colors duration-200 shadow-sm">
                    Update Banner
                </button>
                <a href="{{ route('admin.banners.index') }}" class="px-6 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
