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
                        <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg hover:bg-white/10 transition-all">
                            <div class="flex-1">
                                <p class="text-white font-semibold text-sm">{{ $content->title }}</p>
                                <p class="text-gray-400 text-xs">{{ ucfirst($content->type) }} • {{ $content->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-green-400 font-bold">{{ number_format($content->views_count) }}</p>
                                <p class="text-gray-500 text-xs">views</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400 text-center py-8">No data yet. Upload some files!</p>
            @endif
        </div>
        
        <!-- Type Breakdown -->
        <div class="stat-card">
            <h3 class="text-xl font-bold text-white mb-4">
                <i class="fas fa-chart-pie text-purple-400 mr-2"></i>
                Content by Type
            </h3>
            @if($typeBreakdown->count() > 0)
                <div class="space-y-3">
                    @foreach($typeBreakdown as $type)
                        <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-lg gradient-{{ $type->type === 'video' ? 'green' : ($type->type === 'image' ? 'blue' : 'purple') }} flex items-center justify-center">
                                    <i class="fas fa-{{ $type->type === 'video' ? 'video' : ($type->type === 'image' ? 'image' : 'file') }} text-white"></i>
                                </div>
                                <span class="text-white font-semibold">{{ ucfirst($type->type) }}</span>
                            </div>
                            <span class="text-white font-bold text-xl">{{ $type->count }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400 text-center py-8">No files uploaded yet</p>
            @endif
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
                <div class="p-4 bg-white/5 rounded-lg hover:bg-white/10 transition-all">
                    <p class="text-white font-semibold text-lg mb-2">{{ ucfirst($cat->category) }}</p>
                    <p class="text-gray-400 text-sm"><i class="fas fa-file mr-1"></i>{{ $cat->count }} files</p>
                    <p class="text-green-400 text-sm"><i class="fas fa-eye mr-1"></i>{{ number_format($cat->views) }} views</p>
                </div>
            @endforeach
        </div>
    </div>
    @endif
    
    <!-- Recent Activity -->
    <div class="stat-card">
        <h3 class="text-xl font-bold text-white mb-4">
            <i class="fas fa-clock text-orange-400 mr-2"></i>
            Recent Uploads
        </h3>
        @if($recentUploads->count() > 0)
            <div class="space-y-2">
                @foreach($recentUploads as $upload)
                    <div class="flex items-center space-x-3 p-3 bg-white/5 rounded-lg">
                        <i class="fas fa-{{ $upload->type === 'video' ? 'video' : ($upload->type === 'image' ? 'image' : 'file') }} text-2xl text-green-400"></i>
                        <div class="flex-1">
                            <p class="text-white font-semibold text-sm">{{ $upload->title }}</p>
                            <p class="text-gray-400 text-xs">by {{ $upload->uploader->name }} • {{ $upload->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="text-gray-500 text-sm">{{ number_format($upload->file_size / 1024, 1) }} KB</span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-400 text-center py-8">No recent uploads</p>
        @endif
    </div>
</div>
@endsection
