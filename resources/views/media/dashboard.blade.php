@extends('layouts.media')

@section('title', 'Media Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-black text-white mb-2">
                <i class="fas fa-film text-green-400 mr-3"></i>
                Media Dashboard
            </h1>
            <p class="text-gray-400">Welcome back, {{ $user->name }}! Let's create amazing content today.</p>
        </div>
        
        <!-- Quick Actions -->
        <div class="flex space-x-3">
            <button class="px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 rounded-xl text-white font-semibold hover:from-green-600 hover:to-green-700 transition-all shadow-lg">
                <i class="fas fa-upload mr-2"></i>
                Upload Media
            </button>
            <button class="px-4 py-2 bg-white/10 rounded-xl text-white font-semibold hover:bg-white/20 transition-all">
                <i class="fas fa-broadcast-tower mr-2"></i>
                Start Livestream
            </button>
        </div>
    </div>
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Videos Uploaded -->
        <div class="stat-card">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl gradient-green flex items-center justify-center">
                    <i class="fas fa-video text-white text-xl"></i>
                </div>
                <span class="text-xs text-gray-400">This Month</span>
            </div>
            <h3 class="text-3xl font-bold text-white mb-1">{{ $stats['videos_uploaded'] }}</h3>
            <p class="text-sm text-gray-400">Videos Uploaded</p>
            <div class="mt-3 flex items-center text-green-400 text-xs">
                <i class="fas fa-arrow-up mr-1"></i>
                <span>12% from last month</span>
            </div>
        </div>
        
        <!-- Photos Published -->
        <div class="stat-card">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl gradient-blue flex items-center justify-center">
                    <i class="fas fa-camera text-white text-xl"></i>
                </div>
                <span class="text-xs text-gray-400">This Month</span>
            </div>
            <h3 class="text-3xl font-bold text-white mb-1">{{ $stats['photos_published'] }}</h3>
            <p class="text-sm text-gray-400">Photos Published</p>
            <div class="mt-3 flex items-center text-blue-400 text-xs">
                <i class="fas fa-arrow-up mr-1"></i>
                <span>8% from last month</span>
            </div>
        </div>
        
        <!-- Announcements Posted -->
        <div class="stat-card">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl gradient-purple flex items-center justify-center">
                    <i class="fas fa-bullhorn text-white text-xl"></i>
                </div>
                <span class="text-xs text-gray-400">This Week</span>
            </div>
            <h3 class="text-3xl font-bold text-white mb-1">{{ $stats['announcements_posted'] }}</h3>
            <p class="text-sm text-gray-400">Announcements</p>
            <div class="mt-3 flex items-center text-purple-400 text-xs">
                <i class="fas fa-check mr-1"></i>
                <span>All scheduled</span>
            </div>
        </div>
        
        <!-- Upcoming Events -->
        <div class="stat-card">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl gradient-orange flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-white text-xl"></i>
                </div>
                <span class="text-xs text-gray-400">Next 7 Days</span>
            </div>
            <h3 class="text-3xl font-bold text-white mb-1">{{ $stats['upcoming_events'] }}</h3>
            <p class="text-sm text-gray-400">Events to Cover</p>
            <div class="mt-3 flex items-center text-orange-400 text-xs">
                <i class="fas fa-calendar-check mr-1"></i>
                <span>Ready to shoot</span>
            </div>
        </div>
    </div>
    
    <!-- Livestream Status -->
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 rounded-xl gradient-red flex items-center justify-center">
                    <i class="fas fa-broadcast-tower text-white text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">Livestream Status</h3>
                    <p class="text-gray-400 text-sm">Real-time broadcasting control</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 rounded-full {{ $stats['livestream_status'] === 'online' ? 'bg-green-400 animate-pulse' : 'bg-gray-500' }}"></div>
                    <span class="text-white font-semibold">{{ ucfirst($stats['livestream_status']) }}</span>
                </div>
                <a href="{{ route('media.livestream') }}" class="px-6 py-2 bg-red-500 hover:bg-red-600 rounded-xl text-white font-semibold transition-all">
                    <i class="fas fa-play mr-2"></i>
                    {{ $stats['livestream_status'] === 'online' ? 'Manage Stream' : 'Start Livestream' }}
                </a>
            </div>
        </div>
    </div>
    
    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Uploads -->
        <div class="lg:col-span-2 stat-card">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-clock text-green-400 mr-2"></i>
                    Recent Uploads
                </h3>
                <a href="{{ route('media.library') }}" class="text-green-400 text-sm hover:underline">View All →</a>
            </div>
            
            @if($recentUploads->count() > 0)
                <div class="space-y-3">
                    @foreach($recentUploads as $upload)
                        <div class="flex items-center space-x-4 p-3 bg-white/5 rounded-xl hover:bg-white/10 transition-all">
                            <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                                <i class="fas fa-file-{{ $upload->type }} text-white text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-white font-semibold">{{ $upload->name }}</h4>
                                <p class="text-gray-400 text-xs">{{ $upload->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="text-gray-400 text-sm">{{ $upload->size }}</div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-white/5 rounded-xl">
                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-500 mb-3"></i>
                    <p class="text-gray-400">No recent uploads</p>
                    <button class="mt-4 px-6 py-2 gradient-green rounded-xl text-white font-semibold">
                        <i class="fas fa-upload mr-2"></i>
                        Upload Your First Media
                    </button>
                </div>
            @endif
        </div>
        
        <!-- Quick Actions & AI Assistant -->
        <div class="space-y-6">
            <!-- Upload Shortcuts -->
            <div class="stat-card">
                <h3 class="text-lg font-bold text-white mb-4">
                    <i class="fas fa-bolt text-yellow-400 mr-2"></i>
                    Quick Actions
                </h3>
                <div class="space-y-2">
                    <button class="w-full px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 rounded-xl text-white text-sm font-semibold hover:from-green-600 hover:to-green-700 transition-all flex items-center justify-between">
                        <span><i class="fas fa-video mr-2"></i>New Video</span>
                        <i class="fas fa-chevron-right text-xs"></i>
                    </button>
                    <button class="w-full px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl text-white text-sm font-semibold hover:from-blue-600 hover:to-blue-700 transition-all flex items-center justify-between">
                        <span><i class="fas fa-image mr-2"></i>New Image</span>
                        <i class="fas fa-chevron-right text-xs"></i>
                    </button>
                    <button class="w-full px-4 py-3 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl text-white text-sm font-semibold hover:from-purple-600 hover:to-purple-700 transition-all flex items-center justify-between">
                        <span><i class="fas fa-palette mr-2"></i>Create Banner</span>
                        <i class="fas fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>
            
            <!-- AI Assistant Highlights -->
            <div class="stat-card border-2 border-purple-500/30">
                <h3 class="text-lg font-bold text-white mb-4">
                    <i class="fas fa-brain text-purple-400 mr-2"></i>
                    AI Assistant
                </h3>
                <div class="space-y-3">
                    <div class="p-3 bg-purple-500/10 rounded-lg border border-purple-500/20">
                        <p class="text-purple-300 text-xs font-semibold mb-1">Top Performance</p>
                        <p class="text-white text-sm">Faith in Action Sermon</p>
                        <p class="text-gray-400 text-xs">1,234 views this week</p>
                    </div>
                    <div class="p-3 bg-green-500/10 rounded-lg border border-green-500/20">
                        <p class="text-green-300 text-xs font-semibold mb-1">Suggested Clip</p>
                        <p class="text-white text-sm">Youth Sunday Highlight</p>
                        <p class="text-gray-400 text-xs">Perfect for social media</p>
                    </div>
                </div>
                <a href="{{ route('media.ai-tools') }}" class="mt-4 block text-center px-4 py-2 bg-purple-500/20 hover:bg-purple-500/30 rounded-xl text-purple-300 text-sm font-semibold transition-all">
                    Explore AI Tools →
                </a>
            </div>
        </div>
    </div>
    
    <!-- Storage & Performance -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Storage Usage -->
        <div class="stat-card">
            <h3 class="text-lg font-bold text-white mb-4">
                <i class="fas fa-hdd text-cyan-400 mr-2"></i>
                Storage Usage
            </h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-gray-400 text-sm">Used</span>
                    <span class="text-white font-semibold">{{ $stats['storage_used'] }}</span>
                </div>
                <div class="w-full h-3 bg-white/10 rounded-full overflow-hidden">
                    <div class="h-full gradient-cyan" style="width: 35%"></div>
                </div>
                <div class="flex items-center justify-between text-xs text-gray-400">
                    <span>Total: 10 GB</span>
                    <span>65% available</span>
                </div>
            </div>
        </div>
        
        <!-- Total Views -->
        <div class="stat-card">
            <h3 class="text-lg font-bold text-white mb-4">
                <i class="fas fa-eye text-orange-400 mr-2"></i>
                Total Views
            </h3>
            <div class="flex items-center space-x-4">
                <div class="text-4xl font-bold text-white">{{ number_format($stats['total_views']) }}</div>
                <div class="flex items-center text-green-400 text-sm">
                    <i class="fas fa-arrow-up mr-1"></i>
                    <span>+23%</span>
                </div>
            </div>
            <p class="text-gray-400 text-sm mt-2">Across all media content</p>
        </div>
    </div>
</div>
@endsection
