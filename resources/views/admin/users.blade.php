@extends('layouts.admin')

@section('title', 'Users')
@section('page-title', 'Users')
@section('page-description', 'Manage registered users')

@section('admin-content')
<div class="space-y-6">
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-lg shadow-sm p-6">
        <form method="GET" action="{{ route('admin.users') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, mobile, or M-ID" class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Account</label>
                <select name="account" class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Active</option>
                    <option value="inactive" {{ request('account') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="all" {{ request('account') === 'all' ? 'selected' : '' }}>All</option>
                </select>
            </div>

            <div class="flex items-end space-x-3">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Apply</button>
                <a href="{{ route('admin.users') }}" class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-50">Reset</a>
                <a href="{{ route('admin.users.create') }}" class="ml-auto px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">AddUser</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">All Users</h3>
            <p class="text-sm text-gray-500">Total: {{ $users->total() }}</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            @php $nameOrder = (request('sort_by') === 'name' && request('sort_order') === 'asc') ? 'desc' : 'asc'; @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_order' => $nameOrder]) }}" class="inline-flex items-center">
                                Name
                                @if(request('sort_by') === 'name')
                                    <span class="ml-1 text-gray-400 text-xs">{{ request('sort_order') === 'asc' ? '▲' : '▼' }}</span>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            @php $emailOrder = (request('sort_by') === 'email' && request('sort_order') === 'asc') ? 'desc' : 'asc'; @endphp
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'email', 'sort_order' => $emailOrder]) }}" class="inline-flex items-center">
                                Email
                                @if(request('sort_by') === 'email')
                                    <span class="ml-1 text-gray-400 text-xs">{{ request('sort_order') === 'asc' ? '▲' : '▼' }}</span>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mobile</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">M-ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <div class="hidden">{{ $profile = $user->profile }}</div>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-sm font-medium text-gray-600">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        @if($profile && $profile->gender)
                                            <div class="text-xs text-gray-500">{{ ucfirst($profile->gender) }}</div>
                                        @endif
                                        @if(method_exists($user, 'trashed') && $user->trashed())
                                            <div class="text-xs text-red-600">Inactive</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->mobile ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->m_id ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at?->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm space-x-2">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="px-3 py-1 rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Edit</a>
                                @php $isSelf = auth()->check() && (int)auth()->id() === (int)$user->id; @endphp
                                @if(method_exists($user, 'trashed') && $user->trashed())
                                    <form method="POST" action="{{ route('admin.users.activate', $user->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 rounded-md text-white bg-green-600 hover:bg-green-700">Activate</button>
                                    </form>
                                @else
                                    @unless($isSelf)
                                        <form method="POST" action="{{ route('admin.users.deactivate', $user->id) }}" class="inline" onsubmit="return confirm('Deactivate this user?');">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 rounded-md text-white bg-red-600 hover:bg-red-700">Deactivate</button>
                                        </form>
                                    @endunless
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection