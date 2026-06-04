<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">Payment Accounts</h2>
                        <a href="{{ route('admin.payment-accounts.create') }}" class="btn-primary">Add Payment Account</a>
                    </div>

                    @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left">Method</th>
                                    <th class="px-4 py-2 text-left">Account Name</th>
                                    <th class="px-4 py-2 text-left">Account Number</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                    <th class="px-4 py-2 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($accounts as $account)
                                <tr class="border-b">
                                    <td class="px-4 py-2">{{ $account->method }}</td>
                                    <td class="px-4 py-2">{{ $account->account_name }}</td>
                                    <td class="px-4 py-2 font-mono">{{ $account->account_number }}</td>
                                    <td class="px-4 py-2">
                                        <span class="px-3 py-1 rounded text-xs {{ $account->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $account->active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <a href="{{ route('admin.payment-accounts.edit', $account) }}" class="text-blue-600 hover:text-blue-800 mr-3">Edit</a>
                                        <form action="{{ route('admin.payment-accounts.destroy', $account) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
