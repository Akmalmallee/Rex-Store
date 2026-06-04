<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-2xl font-bold mb-6">Add Payment Account</h2>

                    <form action="{{ route('admin.payment-accounts.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium mb-1">Payment Method *</label>
                            <input type="text" name="method" value="{{ old('method') }}" placeholder="e.g., Bank Transfer, GCash, PayMaya" class="input-field" required>
                            @error('method') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Account Name *</label>
                            <input type="text" name="account_name" value="{{ old('account_name') }}" placeholder="e.g., Bank BCA a.n. Rex Fashion" class="input-field" required>
                            @error('account_name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Account Number *</label>
                            <input type="text" name="account_number" value="{{ old('account_number') }}" placeholder="e.g., 1234567890" class="input-field" required>
                            @error('account_number') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Instructions</label>
                            <textarea name="instructions" placeholder="Optional additional payment instructions" rows="3" class="input-field">{{ old('instructions') }}</textarea>
                            @error('instructions') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="active" value="1" {{ old('active', true) ? 'checked' : '' }} class="mr-2">
                                <span class="text-sm font-medium">Active</span>
                            </label>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="submit" class="btn-primary">Save</button>
                            <a href="{{ route('admin.payment-accounts.index') }}" class="btn-outline">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
