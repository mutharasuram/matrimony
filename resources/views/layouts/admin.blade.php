@extends('layouts.app')

@section('title', 'Admin - ' . ($title ?? 'Dashboard'))

@push('styles')
<style>
    .admin-sidebar {
        background: linear-gradient(180deg, #1e293b 0%, #334155 100%);
    }
    .admin-content {
        margin-left: 16rem; /* 256px */
    }
    @media (max-width: 768px) {
        .admin-content {
            margin-left: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="flex bg-gray-50">
    <!-- Admin Sidebar -->
    <div id="adminSidebar" class="admin-sidebar w-64 flex-shrink-0 fixed top-16 h-[calc(100vh-4rem)] z-40 transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-in-out">
        <div class="flex items-center justify-center h-16 bg-gray-800">
            <h1 class="text-white text-xl font-bold">Admin Panel</h1>
        </div>

        <!-- Close (mobile) -->
        <button id="sidebarClose" class="absolute top-4 right-4 md:hidden text-gray-300 hover:text-white focus:outline-none" aria-label="Close sidebar">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <nav class="mt-8">
            <div class="px-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-white {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : 'hover:bg-gray-700' }} rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                    </svg>
                    Dashboard
                </a>
                
                <a href="{{ route('admin.users') }}" class="flex items-center px-4 py-2 {{ request()->routeIs('admin.users') ? 'text-white bg-gray-700' : 'text-gray-300 hover:text-white hover:bg-gray-700' }} rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    Users
                </a>
            </div>
        </nav>
    </div>
    <!-- Overlay (mobile) -->
    <div id="sidebarOverlay" class="fixed inset-x-0 top-16 bottom-0 bg-black bg-opacity-50 z-30 hidden md:hidden"></div>

    <!-- Main Admin Content -->
    <div class="admin-content flex-1 flex flex-col overflow-hidden">
        <!-- Admin Header -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="flex items-center justify-between px-6 py-4">
                <div class="flex items-center space-x-3">
                    <!-- Hamburger (mobile) -->
                    <button id="sidebarToggle" class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500" aria-label="Open sidebar">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">@yield('page-title', 'Dashboard')</h2>
                        <p class="text-gray-600">@yield('page-description', 'Welcome to the admin panel')</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <span class="text-gray-700 text-sm">
                            {{ date('M d, Y') }}
                        </span>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <span class="text-gray-700 text-sm">Welcome, {{ Auth::user()->name ?? 'Admin' }}</span>
                        <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Admin Main Content Area -->
        <main class="flex-1 overflow-y-auto p-6">
            @yield('admin-content')
        </main>
    </div>
</div>
@endsection 

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggleBtn = document.getElementById('sidebarToggle');
        const closeBtn = document.getElementById('sidebarClose');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        toggleBtn && toggleBtn.addEventListener('click', openSidebar);
        closeBtn && closeBtn.addEventListener('click', closeSidebar);
        overlay && overlay.addEventListener('click', closeSidebar);

        // Ensure sidebar is closed when resizing to mobile and overlay is cleared on desktop
        window.addEventListener('resize', function () {
            if (window.innerWidth >= 768) {
                closeSidebar();
            }
        });
    });
</script>
@endpush