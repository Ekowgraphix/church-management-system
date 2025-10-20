@extends('layouts.app')

@section('page-title', 'Upload Media')
@section('page-subtitle', 'Add new photos or videos')

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-black text-white mb-2">📤 Upload Media</h1>
            <p class="text-green-200">Upload photos or videos to the church library</p>
        </div>
        <a href="{{ route('media.index') }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
            <i class="fas fa-arrow-left mr-2"></i>Back to Library
        </a>
    </div>

    <!-- Upload Form -->
    <div class="glass-card rounded-3xl p-8">
        <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- File Upload -->
            <div>
                <label class="block text-white font-semibold mb-3 text-lg">
                    <i class="fas fa-file-upload mr-2"></i>Media File *
                </label>
                <input type="file" name="file_path" accept="image/*,video/*" required
                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:border-green-400 transition">
                <p class="text-white/50 text-sm mt-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    Accepted: Images (JPG, PNG, GIF) and Videos (MP4, MOV, AVI) - Max 50MB
                </p>
                @error('file_path')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- File Type -->
            <div>
                <label class="block text-white font-semibold mb-3">
                    <i class="fas fa-file-image mr-2"></i>File Type *
                </label>
                <select name="file_type" required
                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-400 transition">
                    <option value="">Select Type</option>
                    <option value="image">Image/Photo</option>
                    <option value="video">Video</option>
                </select>
                @error('file_type')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Title -->
            <div>
                <label class="block text-white font-semibold mb-3">
                    <i class="fas fa-heading mr-2"></i>Title *
                </label>
                <input type="text" name="title" value="{{ old('title') }}" required
                    placeholder="e.g., Sunday Service Worship"
                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:border-green-400 transition">
                @error('title')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div>
                <label class="block text-white font-semibold mb-3">
                    <i class="fas fa-tag mr-2"></i>Category
                </label>
                <input type="text" name="category" value="{{ old('category') }}"
                    placeholder="e.g., Worship, Events, Youth"
                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:border-green-400 transition">
                @error('category')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Event Name -->
            <div>
                <label class="block text-white font-semibold mb-3">
                    <i class="fas fa-calendar-alt mr-2"></i>Event Name
                </label>
                <input type="text" name="event_name" value="{{ old('event_name') }}"
                    placeholder="e.g., Christmas Service 2025"
                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:border-green-400 transition">
                @error('event_name')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-white font-semibold mb-3">
                    <i class="fas fa-align-left mr-2"></i>Description
                </label>
                <textarea name="description" rows="4"
                    placeholder="Add a description for this media file..."
                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:border-green-400 transition">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tags -->
            <div>
                <label class="block text-white font-semibold mb-3">
                    <i class="fas fa-tags mr-2"></i>Tags
                </label>
                <input type="text" name="tags" value="{{ old('tags') }}"
                    placeholder="e.g., worship, praise, testimonies (comma separated)"
                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:border-green-400 transition">
                <p class="text-white/50 text-sm mt-2">Separate tags with commas</p>
                @error('tags')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center space-x-4 pt-6">
                <button type="submit" class="glass-card px-8 py-4 rounded-xl font-bold text-white bg-gradient-to-r from-green-500/20 to-blue-500/20 hover:scale-105 transition-all">
                    <i class="fas fa-upload mr-2"></i>Upload Media
                </button>
                <a href="{{ route('media.index') }}" class="glass-card px-8 py-4 rounded-xl font-semibold text-white hover:bg-white/10 transition">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>

</div>
@endsection
