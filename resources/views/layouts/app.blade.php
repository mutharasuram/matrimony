<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Matrimony')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Styles -->
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .sidebar {
            background: linear-gradient(180deg, #1e293b 0%, #334155 100%);
        }
        .card-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>

    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Navigation Header -->
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <a href="{{ route('welcome') }}" class="text-2xl font-bold text-purple-600">
                            Matrimony
                        </a>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium">
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('admin.login') }}" class="text-gray-700 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium">
                            Admin Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-gray-300">
                    © {{ date('Y') }} Matrimony. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html> 