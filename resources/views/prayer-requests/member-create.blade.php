@extends('layouts.member-portal')

@section('content')
<div>
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('prayer-requests.index') }}" class="text-green-400 hover:text-green-300 flex items-center space-x-2">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Prayer Requests</span>
        </a>
    </div>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-white mb-2">🙏 Submit Prayer Request</h1>
        <p class="text-gray-300">Share your prayer need with the church family</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-lg p-8 max-w-3xl">
        <form action="{{ route('prayer-requests.store') }}" method="POST">
            @csrf

            <!-- Title -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Prayer Request Title <span class="text-red-500">*</span></label>
                <input type="text" 
                       name="title" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-indigo-500" 
                       placeholder="Brief title for your prayer request"
                       value="{{ old('title') }}"
                       required>
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Category <span class="text-red-500">*</span></label>
                <select name="category" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-indigo-500"
                        required>
                    <option value="">Select category...</option>
                    <option value="health" {{ old('category') == 'health' ? 'selected' : '' }}>Health</option>
                    <option value="family" {{ old('category') == 'family' ? 'selected' : '' }}>Family</option>
                    <option value="financial" {{ old('category') == 'financial' ? 'selected' : '' }}>Financial</option>
                    <option value="spiritual" {{ old('category') == 'spiritual' ? 'selected' : '' }}>Spiritual</option>
                    <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('category')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Prayer Request Details <span class="text-red-500">*</span></label>
                <textarea name="description" 
                          rows="6" 
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-indigo-500" 
                          placeholder="Share the details of your prayer request..."
                          required>{{ old('description') }}</textarea>
                <p class="text-sm text-gray-500 mt-1">Be specific so others can pray effectively for your need</p>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Options -->
            <div class="mb-6 space-y-4">
                <div class="flex items-center">
                    <input type="checkbox" 
                           name="is_public" 
                           id="is_public" 
                           value="1"
                           {{ old('is_public') ? 'checked' : '' }}
                           class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <label for="is_public" class="ml-3 text-gray-700">
                        <span class="font-semibold">Make this public</span>
                        <p class="text-sm text-gray-500">Allow all church members to see and pray for this request</p>
                    </label>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" 
                           name="is_urgent" 
                           id="is_urgent" 
                           value="1"
                           {{ old('is_urgent') ? 'checked' : '' }}
                           class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                    <label for="is_urgent" class="ml-3 text-gray-700">
                        <span class="font-semibold text-red-600">Mark as urgent</span>
                        <p class="text-sm text-gray-500">This needs immediate prayer attention</p>
                    </label>
                </div>
            </div>

            <!-- Information Box -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg mb-6">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-600 text-lg mt-1 mr-3"></i>
                    <div>
                        <p class="text-blue-800 font-semibold mb-1">Your privacy matters</p>
                        <p class="text-blue-700 text-sm">
                            If you choose to keep this private, only church leaders will be able to see and pray for your request.
                            Public requests are shared with all church members.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center space-x-4">
                <button type="submit" class="flex-1 bg-indigo-600 text-white py-4 rounded-xl font-bold text-lg hover:bg-indigo-700 transition shadow-lg">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Submit Prayer Request
                </button>
                <a href="{{ route('prayer-requests.index') }}" class="px-8 py-4 border-2 border-gray-300 text-gray-700 rounded-xl font-bold hover:bg-gray-50 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
