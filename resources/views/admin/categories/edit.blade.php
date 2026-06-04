@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('admin.categories.index') }}" class="text-sm text-gray-500 hover:text-[#C8A951]">&larr; Back</a>
    <h1 class="text-2xl font-bold mt-2 mb-6">Edit Category</h1>
    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-[#1a1a1a] rounded-xl p-6 shadow-sm space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-medium mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" class="input-field" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $category->slug) }}" class="input-field">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Description</label>
            <textarea name="description" rows="3" class="input-field">{{ old('description', $category->description) }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Image</label>
            @if($category->image)
            <div class="w-20 h-20 rounded-xl overflow-hidden mb-2 bg-gray-100">
                <img src="{{ str_starts_with($category->image, 'http') ? $category->image : Storage::url($category->image) }}" alt="" class="w-full h-full object-cover">
            </div>
            @endif
            <input type="file" name="image" accept="image/*" class="input-field">
        </div>
        <div>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-[#C8A951]">
                <span class="text-sm">Active</span>
            </label>
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.categories.index') }}" class="px-6 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl text-sm">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-[#C8A951] hover:bg-[#b8963e] text-white rounded-xl text-sm">Update</button>
        </div>
    </form>
</div>
@endsection
