@extends('layouts.admin')
@section('title', 'Settings')
@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Settings</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage application settings</p>
    </div>

    <div class="bg-white dark:bg-gray-800/50 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/50">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <div>
                <label for="app_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">App Name</label>
                <input type="text" id="app_name" name="app_name" value="{{ old('app_name', config('app.name')) }}" required
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-[#C8A951] focus:ring-[#C8A951] @error('app_name') border-red-500 @enderror">
                @error('app_name') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="app_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">App Description</label>
                <textarea id="app_description" name="app_description" rows="4"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-[#C8A951] focus:ring-[#C8A951] @error('app_description') border-red-500 @enderror">{{ old('app_description', config('app.description')) }}</textarea>
                @error('app_description') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 border-t border-gray-200 dark:border-gray-700/50">
                <button type="submit" class="px-6 py-2.5 bg-[#C8A951] hover:bg-[#b89a42] text-white text-sm font-medium rounded-xl transition-colors duration-200 shadow-sm">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
