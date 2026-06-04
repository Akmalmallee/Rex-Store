@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Categories</h1>
            <p class="text-gray-500 dark:text-gray-400">Manage product categories</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="bg-[#C8A951] hover:bg-[#b8963e] text-white px-4 py-2 rounded-xl text-sm font-medium transition-colors inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Category
        </a>
    </div>

    <div class="bg-white dark:bg-[#1a1a1a] rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900">
                    <th class="text-left py-3 px-4 font-medium">Name</th>
                    <th class="text-left py-3 px-4 font-medium">Slug</th>
                    <th class="text-left py-3 px-4 font-medium">Products</th>
                    <th class="text-left py-3 px-4 font-medium">Status</th>
                    <th class="text-right py-3 px-4 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                    <td class="py-3 px-4 font-medium">{{ $category->name }}</td>
                    <td class="py-3 px-4 text-gray-500">{{ $category->slug }}</td>
                    <td class="py-3 px-4">{{ $category->products_count ?? $category->products->count() }}</td>
                    <td class="py-3 px-4">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                                @csrf @method('DELETE')
                                <button class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-12 text-center text-gray-500">No categories found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
