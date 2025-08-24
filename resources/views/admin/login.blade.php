@extends('layouts.app')

@section('title', 'Admin Login - Matrimony')

@push('styles')
<style>
    .gradient-bg {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .glass-effect {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
</style>
@endpush

@section('content')
<div class="gradient-bg min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        <div class="glass-effect rounded-2xl p-8 shadow-2xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">Admin Panel</h1>
                <p class="text-white/80">Matrimony Management System</p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-6">
                @csrf
                
                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-white mb-2">
                        Email Address
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent transition-all duration-200"
                        placeholder="Enter your email"
                        required
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-white mb-2">
                        Password
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent transition-all duration-200"
                        placeholder="Enter your password"
                        required
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        id="remember" 
                        name="remember" 
                        class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded"
                    >
                    <label for="remember" class="ml-2 block text-sm text-white">
                        Remember me
                    </label>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full bg-white text-purple-600 py-3 px-4 rounded-lg font-semibold hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-white/50 transition-all duration-200 transform hover:scale-105"
                >
                    Sign In
                </button>
            </form>

            <!-- Footer -->
            <div class="mt-8 text-center">
                <p class="text-white/60 text-sm">
                    © 2024 Matrimony Admin Panel. All rights reserved.
                </p>
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endsection 