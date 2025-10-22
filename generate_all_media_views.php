<?php

echo "==========================================\n";
echo "GENERATING ALL MEDIA PORTAL VIEWS\n";
echo "==========================================\n\n";

$baseDir = __DIR__ . '/resources/views/media/';

// Analytics View
echo "1. Creating analytics.blade.php...\n";
$analyticsView = <<<'BLADE'
@extends('layouts.media')

@section('title', 'Analytics')

@section('content')
<div class="space-y-6">
    <h1 class="text-4xl font-black text-white mb-2">
        <i class="fas fa-chart-line text-green-400 mr-3"></i>
        Analytics Dashboard
    </h1>
    
    <!-- Main Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="stat-card">
            <p class="text-gray-400 text-sm mb-1">Total Files</p>
            <p class="text-3xl font-bold text-white">{{ $stats['total_files'] }}</p>
        </div>
        <div class="stat-card">
            <p class="text-gray-400 text-sm mb-1">Total Views</p>
            <p class="text-3xl font-bold text-white">{{ number_format($stats['total_views']) }}</p>
        </div>
        <div class="stat-card">
            <p class="text-gray-400 text-sm mb-1">Downloads</p>
            <p class="text-3xl font-bold text-white">{{ number_format($stats['total_downloads']) }}</p>
        </div>
        <div class="stat-card">
            <p class="text-gray-400 text-sm mb-1">Storage</p>
            <p class="text-3xl font-bold text-white">{{ number_format($stats['total_storage'] / 1048576, 1) }} MB</p>
        </div>
        <div class="stat-card">
            <p class="text-gray-400 text-sm mb-1">This Month</p>
            <p class="text-3xl font-bold text-white">{{ $stats['uploads_this_month'] }}</p>
        </div>
        <div class="stat-card">
            <p class="text-gray-400 text-sm mb-1">This Week</p>
            <p class="text-3xl font-bold text-white">{{ $stats['uploads_this_week'] }}</p>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Content -->
        <div class="stat-card">
            <h3 class="text-xl font-bold text-white mb-4">
                <i class="fas fa-star text-yellow-400 mr-2"></i>
                Top Performing Content
            </h3>
            @if($topContent->count() > 0)
                <div class="space-y-3">
                    @foreach($topContent as $content)
                        <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                            <div class="flex-1">
                                <p class="text-white font-semibold text-sm">{{ $content->title }}</p>
                                <p class="text-gray-400 text-xs">{{ ucfirst($content->type) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-green-400 font-bold">{{ number_format($content->views_count) }}</p>
                                <p class="text-gray-500 text-xs">views</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400 text-center py-8">No data yet</p>
            @endif
        </div>
        
        <!-- Type Breakdown -->
        <div class="stat-card">
            <h3 class="text-xl font-bold text-white mb-4">
                <i class="fas fa-chart-pie text-purple-400 mr-2"></i>
                Content by Type
            </h3>
            <div class="space-y-3">
                @foreach($typeBreakdown as $type)
                    <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-{{ $type->type === 'video' ? 'video' : ($type->type === 'image' ? 'image' : 'file') }} text-2xl text-green-400"></i>
                            <span class="text-white font-semibold">{{ ucfirst($type->type) }}</span>
                        </div>
                        <span class="text-white font-bold">{{ $type->count }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- Category Performance -->
    @if($categoryBreakdown->count() > 0)
    <div class="stat-card">
        <h3 class="text-xl font-bold text-white mb-4">
            <i class="fas fa-tags text-blue-400 mr-2"></i>
            Performance by Category
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($categoryBreakdown as $cat)
                <div class="p-4 bg-white/5 rounded-lg">
                    <p class="text-white font-semibold mb-2">{{ ucfirst($cat->category) }}</p>
                    <p class="text-gray-400 text-sm">{{ $cat->count }} files</p>
                    <p class="text-green-400 text-sm">{{ number_format($cat->views) }} views</p>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
BLADE;
file_put_contents($baseDir . 'analytics.blade.php', $analyticsView);
echo "✅ Analytics view created\n";

// Gallery View
echo "\n2. Creating gallery.blade.php...\n";
$galleryView = <<<'BLADE'
@extends('layouts.media')

@section('title', 'Gallery Management')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-black text-white mb-2">
                <i class="fas fa-images text-green-400 mr-3"></i>
                Gallery Management
            </h1>
            <p class="text-gray-400">Create and manage photo albums</p>
        </div>
        <button onclick="openCreateModal()" class="px-6 py-3 gradient-green rounded-xl text-white font-semibold hover:shadow-lg transition-all">
            <i class="fas fa-plus mr-2"></i>
            Create Gallery
        </button>
    </div>
    
    @if($galleries->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($galleries as $gallery)
                <div class="stat-card p-0 overflow-hidden hover:scale-105 transition-transform">
                    <div class="h-48 bg-gradient-to-br from-gray-700 to-gray-800 flex items-center justify-center">
                        @if($gallery->cover_image)
                            <img src="{{ $gallery->cover_image }}" alt="{{ $gallery->name }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-images text-6xl text-gray-500"></i>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="text-white font-bold text-lg mb-2">{{ $gallery->name }}</h3>
                        <p class="text-gray-400 text-sm mb-3">{{ $gallery->description }}</p>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500"><i class="fas fa-image mr-1"></i>{{ $gallery->mediaFiles->count() }} photos</span>
                            <span class="text-gray-500"><i class="fas fa-eye mr-1"></i>{{ $gallery->views_count }} views</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{ $galleries->links() }}
    @else
        <div class="stat-card text-center py-20">
            <i class="fas fa-images text-6xl text-gray-500 mb-4"></i>
            <h2 class="text-2xl font-bold text-white mb-2">No Galleries Yet</h2>
            <p class="text-gray-400 mb-6">Create your first gallery to organize photos</p>
            <button onclick="openCreateModal()" class="px-6 py-3 gradient-green rounded-xl text-white font-semibold">
                <i class="fas fa-plus mr-2"></i>
                Create Gallery
            </button>
        </div>
    @endif
</div>

<!-- Create Modal -->
<div id="createModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
    <div class="stat-card max-w-lg w-full">
        <h2 class="text-2xl font-bold text-white mb-4">Create Gallery</h2>
        <form id="createForm" class="space-y-4">
            @csrf
            <div>
                <label class="block text-white font-semibold mb-2">Gallery Name *</label>
                <input type="text" name="name" required class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-400">
            </div>
            <div>
                <label class="block text-white font-semibold mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-400"></textarea>
            </div>
            <div class="flex items-center">
                <input type="checkbox" name="is_public" checked class="mr-2">
                <label class="text-white">Make gallery public</label>
            </div>
            <div class="flex space-x-3">
                <button type="submit" class="flex-1 px-6 py-3 gradient-green rounded-xl text-white font-semibold">Create</button>
                <button type="button" onclick="closeCreateModal()" class="px-6 py-3 bg-white/10 rounded-xl text-white">Cancel</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openCreateModal() {
        document.getElementById('createModal').classList.remove('hidden');
    }
    function closeCreateModal() {
        document.getElementById('createModal').classList.add('hidden');
    }
    document.getElementById('createForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const response = await fetch('{{ route("media.gallery.create") }}', {
            method: 'POST',
            body: formData,
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        });
        const data = await response.json();
        if (data.success) {
            window.location.reload();
        }
    });
</script>
@endpush
@endsection
BLADE;
file_put_contents($baseDir . 'gallery.blade.php', $galleryView);
echo "✅ Gallery view created\n";

echo "\n==========================================\n";
echo "✅ ALL VIEWS GENERATED!\n";
echo "==========================================\n\n";
echo "Generated views:\n";
echo "  ✅ analytics.blade.php\n";
echo "  ✅ gallery.blade.php\n\n";
echo "Run: php artisan view:clear\n";
BLADE;
file_put_contents(__DIR__ . '/generate_all_media_views.php', $content);
