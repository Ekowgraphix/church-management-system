@extends('layouts.app')

@section('page-title', 'Media Details')
@section('page-subtitle', 'View media file information')

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-black text-white mb-2">🖼️ Media Details</h1>
            <p class="text-green-200">{{ $media->title }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('media.edit', $media) }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('media.index') }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Main Media Display -->
        <div class="lg:col-span-2">
            <div class="glass-card rounded-3xl p-6">
                <div class="aspect-video bg-white/5 rounded-2xl overflow-hidden">
                    @if($media->file_type === 'image')
                        <img src="{{ asset('storage/' . $media->file_path) }}" alt="{{ $media->title }}" class="w-full h-full object-contain">
                    @else
                        <video controls class="w-full h-full">
                            <source src="{{ asset('storage/' . $media->file_path) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    @endif
                </div>

                <!-- Title and Description -->
                <div class="mt-6">
                    <h2 class="text-3xl font-black text-white mb-4">{{ $media->title }}</h2>
                    
                    @if($media->description)
                        <div class="glass-card rounded-2xl p-4 mb-4">
                            <p class="text-white/80 leading-relaxed">{{ $media->description }}</p>
                        </div>
                    @endif

                    @if($media->tags)
                        <div class="flex flex-wrap gap-2">
                            @foreach(explode(',', $media->tags) as $tag)
                                <span class="glass-card px-4 py-2 rounded-lg text-sm text-white">
                                    <i class="fas fa-tag mr-1"></i>{{ trim($tag) }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-3 mt-6">
                    <a href="{{ asset('storage/' . $media->file_path) }}" download class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all flex-1 text-center">
                        <i class="fas fa-download mr-2"></i>Download
                    </a>
                    <a href="{{ asset('storage/' . $media->file_path) }}" target="_blank" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all flex-1 text-center">
                        <i class="fas fa-external-link-alt mr-2"></i>Open in New Tab
                    </a>
                </div>
            </div>
        </div>

        <!-- Details Sidebar -->
        <div class="space-y-6">
            
            <!-- File Information -->
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-xl font-black text-white mb-4">
                    <i class="fas fa-info-circle mr-2"></i>File Information
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-white/60 text-sm mb-1">File Type</p>
                        <p class="text-white font-semibold">
                            <span class="px-3 py-1 rounded-lg {{ $media->file_type === 'image' ? 'bg-blue-500' : 'bg-pink-500' }} text-white text-sm">
                                {{ strtoupper($media->file_type) }}
                            </span>
                        </p>
                    </div>

                    @if($media->category)
                        <div>
                            <p class="text-white/60 text-sm mb-1">Category</p>
                            <p class="text-white font-semibold">
                                <i class="fas fa-tag mr-2 text-purple-400"></i>{{ $media->category }}
                            </p>
                        </div>
                    @endif

                    @if($media->event_name)
                        <div>
                            <p class="text-white/60 text-sm mb-1">Event</p>
                            <p class="text-white font-semibold">
                                <i class="fas fa-calendar-alt mr-2 text-green-400"></i>{{ $media->event_name }}
                            </p>
                        </div>
                    @endif

                    <div>
                        <p class="text-white/60 text-sm mb-1">File Size</p>
                        <p class="text-white font-semibold">
                            <i class="fas fa-hdd mr-2 text-orange-400"></i>{{ number_format($media->file_size / 1048576, 2) }} MB
                        </p>
                    </div>

                    <div>
                        <p class="text-white/60 text-sm mb-1">Uploaded By</p>
                        <p class="text-white font-semibold">
                            <i class="fas fa-user mr-2 text-blue-400"></i>{{ $media->uploaded_by }}
                        </p>
                    </div>

                    <div>
                        <p class="text-white/60 text-sm mb-1">Upload Date</p>
                        <p class="text-white font-semibold">
                            <i class="fas fa-clock mr-2 text-cyan-400"></i>{{ $media->created_at->format('M d, Y g:i A') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-white/60 text-sm mb-1">Last Updated</p>
                        <p class="text-white font-semibold">
                            <i class="fas fa-sync mr-2 text-pink-400"></i>{{ $media->updated_at->format('M d, Y g:i A') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Delete Button -->
            <div class="glass-card rounded-2xl p-6 bg-red-500/10 border border-red-500/30">
                <h3 class="text-xl font-black text-white mb-4">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Danger Zone
                </h3>
                <p class="text-white/70 text-sm mb-4">Once you delete this media file, it cannot be recovered.</p>
                
                <form action="{{ route('media.destroy', $media) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this media file? This action cannot be undone!')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full glass-card px-6 py-3 rounded-xl font-semibold text-white bg-red-500/20 hover:bg-red-500/30 transition-all">
                        <i class="fas fa-trash mr-2"></i>Delete Media File
                    </button>
                </form>
            </div>

        </div>
    </div>

</div>
@endsection
