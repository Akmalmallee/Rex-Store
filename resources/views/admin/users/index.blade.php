@extends('layouts.admin')

@section('title', 'Users')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold">Users</h1>
        <p class="text-gray-500 dark:text-gray-400">Manage registered users</p>
    </div>

    <div class="bg-white dark:bg-[#1a1a1a] rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900">
                    <th class="text-left py-3 px-4 font-medium">User</th>
                    <th class="text-left py-3 px-4 font-medium">Email</th>
                    <th class="text-left py-3 px-4 font-medium">Role</th>
                    <th class="text-left py-3 px-4 font-medium">Orders</th>
                    <th class="text-left py-3 px-4 font-medium">Joined</th>
                    <th class="text-right py-3 px-4 font-medium">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-[#C8A951]/20 text-[#C8A951] flex items-center justify-center text-sm font-medium shrink-0">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <span class="font-medium">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="py-3 px-4 text-gray-500">{{ $user->email }}</td>
                    <td class="py-3 px-4">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $user->is_admin ? 'bg-[#C8A951]/10 text-[#C8A951]' : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' }}">
                            {{ $user->is_admin ? 'Admin' : 'Customer' }}
                        </span>
                    </td>
                    <td class="py-3 px-4">{{ $user->orders_count ?? $user->orders->count() }}</td>
                    <td class="py-3 px-4 text-gray-500 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="py-3 px-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Delete this user?')">
                                @csrf @method('DELETE')
                                <button class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-12 text-center text-gray-500">No users found</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-gray-200 dark:border-gray-800">{{ $users->links() }}</div>
    </div>
</div>
@endsection
