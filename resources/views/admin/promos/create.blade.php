@extends('layouts.admin')
@section('title', 'Create Promo')
@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create Promo</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Add a new promotional campaign</p>
    </div>

    <div class="bg-white dark:bg-gray-800/50 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/50">
        <form action="{{ route('admin.promos.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-[#C8A951] focus:ring-[#C8A951] @error('title') border-red-500 @enderror">
                @error('title') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                <textarea id="description" name="description" rows="3"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-[#C8A951] focus:ring-[#C8A951] @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Image</label>
                <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/webp" required
                    class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-[#C8A951]/10 file:text-[#C8A951] hover:file:bg-[#C8A951]/20 transition-colors @error('image') border-red-500 @enderror">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">JPEG, PNG, JPG, or WebP. Max 2MB.</p>
                @error('image') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="discount_percent" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Discount Percentage</label>
                <input type="number" id="discount_percent" name="discount_percent" value="{{ old('discount_percent') }}" required min="0" max="100"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-[#C8A951] focus:ring-[#C8A951] @error('discount_percent') border-red-500 @enderror">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Enter a value between 0 and 100.</p>
                @error('discount_percent') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="start_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Date</label>
                    <input type="datetime-local" id="start_at" name="start_at" value="{{ old('start_at') }}" required
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-[#C8A951] focus:ring-[#C8A951] @error('start_at') border-red-500 @enderror">
                    @error('start_at') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="end_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Date</label>
                    <input type="datetime-local" id="end_at" name="end_at" value="{{ old('end_at') }}" required
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-[#C8A951] focus:ring-[#C8A951] @error('end_at') border-red-500 @enderror">
                    @error('end_at') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                    class="rounded border-gray-300 dark:border-gray-600 text-[#C8A951] focus:ring-[#C8A951]">
                <label for="is_active" class="text-sm font-medium text-gray-700 dark:text-gray-300">Active</label>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700/50">
                <button type="submit" class="px-6 py-2.5 bg-[#C8A951] hover:bg-[#b89a42] text-white text-sm font-medium rounded-xl transition-colors duration-200 shadow-sm">
                    Create Promo
                </button>
                <a href="{{ route('admin.promos.index') }}" class="px-6 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
