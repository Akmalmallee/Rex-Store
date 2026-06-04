@extends('layouts.admin')
@section('title', 'Edit User')
@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit User</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Update user information</p>
    </div>

    <div class="bg-white dark:bg-gray-800/50 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/50">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-[#C8A951] focus:ring-[#C8A951] @error('name') border-red-500 @enderror">
                @error('name') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-[#C8A951] focus:ring-[#C8A951] @error('email') border-red-500 @enderror">
                @error('email') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                <input type="password" id="password" name="password"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-[#C8A951] focus:ring-[#C8A951] @error('password') border-red-500 @enderror">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave empty to keep current password.</p>
                @error('password') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-[#C8A951] focus:ring-[#C8A951]">
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role</label>
                <select id="role" name="role" required
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-[#C8A951] focus:ring-[#C8A951] @error('role') border-red-500 @enderror">
                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-[#C8A951] focus:ring-[#C8A951] @error('phone') border-red-500 @enderror">
                @error('phone') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
                <textarea id="address" name="address" rows="3"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-[#C8A951] focus:ring-[#C8A951] @error('address') border-red-500 @enderror">{{ old('address', $user->address) }}</textarea>
                @error('address') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="avatar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Avatar</label>
                @if($user->avatar)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-full object-cover">
                </div>
                @endif
                <input type="file" id="avatar" name="avatar" accept="image/jpeg,image/png,image/jpg,image/webp"
                    class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-[#C8A951]/10 file:text-[#C8A951] hover:file:bg-[#C8A951]/20 transition-colors">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave empty to keep current avatar. JPEG, PNG, JPG, or WebP. Max 2MB.</p>
                @error('avatar') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700/50">
                <button type="submit" class="px-6 py-2.5 bg-[#C8A951] hover:bg-[#b89a42] text-white text-sm font-medium rounded-xl transition-colors duration-200 shadow-sm">
                    Update User
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-6 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
