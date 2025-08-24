@extends('layouts.app')

@section('title', 'Welcome - Matrimony')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 to-blue-50">
    <!-- Hero Section -->
    <div class="relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                    Welcome to 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-blue-600">
                        Matrimony
                    </span>
                </h1>
                <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                    Find your perfect match with our comprehensive matrimony platform. 
                    Connect with like-minded individuals and build meaningful relationships.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('admin.login') }}" 
                       class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 transition-colors">
                        Admin Login
                    </a>
                    <a href="#" 
                       class="inline-flex items-center px-8 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        Learn More
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    Why Choose Matrimony?
                </h2>
                <p class="text-xl text-gray-600">
                    Our platform offers the best features for finding your life partner
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Verified Profiles</h3>
                    <p class="text-gray-600">All profiles are verified to ensure authenticity and trust</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Advanced Matching</h3>
                    <p class="text-gray-600">Smart algorithms to find your perfect compatibility match</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Privacy & Security</h3>
                    <p class="text-gray-600">Your privacy and security are our top priorities</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
