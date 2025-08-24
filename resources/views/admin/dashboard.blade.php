@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')
@section('page-description', 'Welcome back, ' . (Auth::user()->name ?? 'Admin') . '!')

@section('admin-content')
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Users</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalUsers ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Profiles</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalProfiles ?? 0 }}</p>
            </div>
        </div>
    </div> -->

    <!-- <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Products</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalProducts ?? 0 }}</p>
            </div>
        </div>
    </div> -->
</div>

<!-- Recent Activity -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Users -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Recent Users</h3>
        </div>
        <div class="p-6">
            @if(isset($recentUsers) && $recentUsers->count() > 0)
                <div class="space-y-4">
                    @foreach($recentUsers as $user)
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium text-gray-600">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $user->created_at->diffForHumans() }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">No recent users</p>
            @endif
        </div>
    </div>

    <!-- Recent Profiles -->
    <!-- <div class="bg-white rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Recent Profiles</h3>
        </div>
        <div class="p-6">
            @if(isset($recentProfiles) && $recentProfiles->count() > 0)
                <div class="space-y-4">
                    @foreach($recentProfiles as $profile)
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $profile->user->name ?? 'Unknown User' }}</p>
                                <p class="text-sm text-gray-500">Profile ID: {{ $profile->id }}</p>
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $profile->created_at->diffForHumans() }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">No recent profiles</p>
            @endif
        </div>
    </div> -->
</div>
@endsection 