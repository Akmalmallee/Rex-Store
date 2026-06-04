@extends('layouts.admin')
@section('title', 'Create Coupon')
@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create Coupon</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Add a new discount coupon</p>
    </div>

    <div class="bg-white dark:bg-gray-800/50 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/50">
        <form action="{{ route('admin.coupons.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Coupon Code</label>
                <input type="text" id="code" name="code" value="{{ old('code') }}" required
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-[#C8A951] focus:ring-[#C8A951] uppercase @error('code') border-red-500 @enderror">
                @error('code') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Discount Type</label>
                <select id="type" name="type" required
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-[#C8A951] focus:ring-[#C8A951] @error('type') border-red-500 @enderror">
                    <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                    <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed</option>
                </select>
                @error('type') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="value" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Value</label>
                <input type="number" id="value" name="value" value="{{ old('value') }}" required min="0" step="0.01"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-[#C8A951] focus:ring-[#C8A951] @error('value') border-red-500 @enderror">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Enter percentage (e.g. 10) or fixed amount (e.g. 50000)</p>
                @error('value') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="min_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Minimum Order</label>
                <input type="number" id="min_order" name="min_order" value="{{ old('min_order') }}" min="0" step="0.01"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-[#C8A951] focus:ring-[#C8A951] @error('min_order') border-red-500 @enderror">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave empty for no minimum order.</p>
                @error('min_order') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="usage_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Usage Limit</label>
                <input type="number" id="usage_limit" name="usage_limit" value="{{ old('usage_limit') }}" min="1"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-[#C8A951] focus:ring-[#C8A951] @error('usage_limit') border-red-500 @enderror">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave empty for unlimited usage.</p>
                @error('usage_limit') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="expires_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Expires At</label>
                <input type="date" id="expires_at" name="expires_at" value="{{ old('expires_at') }}"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-[#C8A951] focus:ring-[#C8A951] @error('expires_at') border-red-500 @enderror">
                @error('expires_at') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                    class="rounded border-gray-300 dark:border-gray-600 text-[#C8A951] focus:ring-[#C8A951]">
                <label for="is_active" class="text-sm font-medium text-gray-700 dark:text-gray-300">Active</label>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700/50">
                <button type="submit" class="px-6 py-2.5 bg-[#C8A951] hover:bg-[#b89a42] text-white text-sm font-medium rounded-xl transition-colors duration-200 shadow-sm">
                    Create Coupon
                </button>
                <a href="{{ route('admin.coupons.index') }}" class="px-6 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
