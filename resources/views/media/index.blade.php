@extends('layouts.app')

@section('page-title', 'Media Library')
@section('page-subtitle', 'Manage church photos and videos')

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="glass-card rounded-3xl p-8 bg-gradient-to-r from-purple-400/10 to-pink-400/10">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-black text-white mb-2">
                    📸 Media Library
                </h1>
                <p class="text-purple-200 text-lg">Manage church photos, videos, and media team</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('media.create') }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                    <i class="fas fa-upload mr-2"></i>Upload Media
                </a>
                <a href="{{ route('media.team') }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                    <i class="fas fa-users mr-2"></i>Media Team
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="glass-card rounded-2xl p-6">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-photo-video text-purple-300 text-2xl"></i>
                <span class="text-3xl font-black text-white">{{ $media->total() }}</span>
            </div>
            <p class="text-white/70 font-medium">Total Files</p>
        </div>

        <div class="glass-card rounded-2xl p-6">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-image text-blue-300 text-2xl"></i>
                <span class="text-3xl font-black text-white">{{ $media->where('file_type', 'image')->count() }}</span>
            </div>
            <p class="text-white/70 font-medium">Images</p>
        </div>

        <div class="glass-card rounded-2xl p-6">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-video text-pink-300 text-2xl"></i>
                <span class="text-3xl font-black text-white">{{ $media->where('file_type', 'video')->count() }}</span>
            </div>
            <p class="text-white/70 font-medium">Videos</p>
        </div>

        <div class="glass-card rounded-2xl p-6">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-hdd text-orange-300 text-2xl"></i>
                <span class="text-3xl font-black text-white">{{ number_format($media->sum('file_size') / 1048576, 1) }}</span>
            </div>
            <p class="text-white/70 font-medium">MB Used</p>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="glass-card rounded-2xl p-4 bg-green-500/20 border border-green-500/30">
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-300 text-xl"></i>
                <span class="text-white font-semibold">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Media Grid -->
    <div class="glass-card rounded-3xl p-8">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-black text-white">All Media Files</h2>
            <div class="text-white/60">{{ $media->total() }} files total</div>
        </div>

        @if($media->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($media as $item)
                    <div class="glass-card rounded-2xl overflow-hidden hover:scale-105 transition-all group">
                        <!-- Media Preview -->
                        <div class="aspect-square bg-white/5 relative overflow-hidden">
                            @if($item->file_type === 'image')
                                <img src="{{ asset('storage/' . $item->file_path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover" onerror="this.src='{{ asset('images/no-image.png') }}'; this.onerror=null;">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-500/20 to-pink-500/20">
                                    <i class="fas fa-play-circle text-white text-6xl opacity-70"></i>
                                </div>
                            @endif
                            
                            <!-- Type Badge -->
                            <div class="absolute top-3 right-3">
                                <span class="px-3 py-1 rounded-lg text-xs font-bold text-white {{ $item->file_type === 'image' ? 'bg-blue-500' : 'bg-pink-500' }}">
                                    {{ strtoupper($item->file_type) }}
                                </span>
                            </div>
                        </div>

                        <!-- Media Info -->
                        <div class="p-4">
                            <h3 class="text-white font-bold text-lg mb-2 truncate">{{ $item->title }}</h3>
                            
                            @if($item->category)
                                <p class="text-white/60 text-sm mb-2">
                                    <i class="fas fa-tag mr-1"></i>{{ $item->category }}
                                </p>
                            @endif

                            <p class="text-white/50 text-xs mb-3">
                                <i class="fas fa-user mr-1"></i>{{ $item->uploaded_by }}
                                <span class="mx-1">•</span>
                                {{ $item->created_at->format('M d, Y') }}
                            </p>

                            <!-- Actions -->
                            <div class="flex space-x-2">
                                <a href="{{ route('media.show', $item) }}" class="flex-1 glass-card px-3 py-2 rounded-lg text-center text-white hover:bg-white/10 transition text-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('media.edit', $item) }}" class="flex-1 glass-card px-3 py-2 rounded-lg text-center text-white hover:bg-white/10 transition text-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('media.destroy', $item) }}" method="POST" class="flex-1" onsubmit="return confirm('Delete this media file?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full glass-card px-3 py-2 rounded-lg text-white hover:bg-red-500/20 transition text-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $media->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <i class="fas fa-photo-video text-white/20 text-6xl mb-4"></i>
                <p class="text-white/60 text-lg mb-6">No media files yet</p>
                <a href="{{ route('media.create') }}" class="glass-card px-8 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all inline-block">
                    <i class="fas fa-upload mr-2"></i>Upload Your First Media
                </a>
            </div>
        @endif
    </div>

</div>
@endsection
