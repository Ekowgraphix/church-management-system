@extends('layouts.app')

@section('page-title', 'Edit Media')
@section('page-subtitle', 'Update media file details')

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-black text-white mb-2">✏️ Edit Media</h1>
            <p class="text-green-200">Update media file information</p>
        </div>
        <a href="{{ route('media.index') }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
            <i class="fas fa-arrow-left mr-2"></i>Back to Library
        </a>
    </div>

    <!-- Edit Form -->
    <div class="glass-card rounded-3xl p-8">
        <form action="{{ route('media.update', $media) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Current File Preview -->
            <div class="mb-6">
                <label class="block text-white font-semibold mb-3">Current File</label>
                <div class="glass-card rounded-2xl p-4 inline-block">
                    @if($media->file_type === 'image')
                        <img src="{{ Storage::url($media->file_path) }}" alt="{{ $media->title }}" class="max-w-xs rounded-xl">
                    @else
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-video text-pink-400 text-3xl"></i>
                            <div>
                                <p class="text-white font-semibold">Video File</p>
                                <p class="text-white/60 text-sm">{{ basename($media->file_path) }}</p>
                            </div>
                        </div>
                    @endif
                </div>
                <p class="text-white/50 text-sm mt-2">
                    <i class="fas fa-info-circle mr-1"></i>Note: You cannot change the file itself, only its details
                </p>
            </div>

            <!-- Title -->
            <div>
                <label class="block text-white font-semibold mb-3">
                    <i class="fas fa-heading mr-2"></i>Title *
                </label>
                <input type="text" name="title" value="{{ old('title', $media->title) }}" required
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
                <input type="text" name="category" value="{{ old('category', $media->category) }}"
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
                <input type="text" name="event_name" value="{{ old('event_name', $media->event_name) }}"
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
                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:border-green-400 transition">{{ old('description', $media->description) }}</textarea>
                @error('description')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tags -->
            <div>
                <label class="block text-white font-semibold mb-3">
                    <i class="fas fa-tags mr-2"></i>Tags
                </label>
                <input type="text" name="tags" value="{{ old('tags', $media->tags) }}"
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
                    <i class="fas fa-save mr-2"></i>Update Media
                </button>
                <a href="{{ route('media.index') }}" class="glass-card px-8 py-4 rounded-xl font-semibold text-white hover:bg-white/10 transition">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>

</div>
@endsection
