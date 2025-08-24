@extends('layouts.app')

@section('title', 'User Profile - Matrimony')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Profile Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex items-center space-x-6">
                <div class="w-24 h-24 bg-gradient-to-br from-purple-400 to-blue-500 rounded-full flex items-center justify-center">
                    <span class="text-3xl font-bold text-white">JD</span>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">John Doe</h1>
                    <p class="text-gray-600 text-lg">Software Engineer</p>
                    <p class="text-gray-500">Age: 28 | Location: New York, USA</p>
                </div>
            </div>
        </div>

        <!-- Profile Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Personal Information -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Personal Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <p class="text-gray-900">John Doe</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                            <p class="text-gray-900">January 15, 1996</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Religion</label>
                            <p class="text-gray-900">Christian</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Marital Status</label>
                            <p class="block text-sm font-medium text-green-600">Single</p>
                        </div>
                    </div>
                </div>

                <!-- About Me -->
                <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">About Me</h2>
                    <p class="text-gray-700 leading-relaxed">
                        I am a passionate software engineer with a love for technology and innovation. 
                        I enjoy reading, traveling, and meeting new people. I'm looking for someone 
                        who shares similar values and is ready for a committed relationship.
                    </p>
                </div>

                <!-- Education & Career -->
                <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Education & Career</h2>
                    <div class="space-y-4">
                        <div>
                            <h3 class="font-medium text-gray-900">Bachelor of Science in Computer Science</h3>
                            <p class="text-gray-600">Stanford University, 2018</p>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900">Software Engineer</h3>
                            <p class="text-gray-600">Tech Company Inc., 2018 - Present</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Contact Information -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h3>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-gray-700">john.doe@email.com</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span class="text-gray-700">+1 (555) 123-4567</span>
                        </div>
                    </div>
                </div>

                <!-- Preferences -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Preferences</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Age Range</label>
                            <p class="text-gray-900">25 - 35</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                            <p class="text-gray-900">Within 50 miles</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                    <div class="space-y-3">
                        <button class="w-full bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 transition-colors">
                            Send Interest
                        </button>
                        <button class="w-full bg-gray-100 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-200 transition-colors">
                            Add to Shortlist
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 