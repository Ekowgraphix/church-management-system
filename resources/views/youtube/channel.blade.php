@extends('layouts.media')
@section('title', 'Church YouTube Channel')
@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-black text-white">
                <i class="fab fa-youtube text-red-500 mr-3"></i>Our YouTube Channel
            </h1>
            <p class="text-gray-400 mt-2">Latest uploads from our church</p>
        </div>
        <a href="{{ route('media.youtube.search') }}" class="px-6 py-3 bg-white/10 hover:bg-white/20 rounded-xl text-white font-semibold">
            <i class="fas fa-search mr-2"></i>Search Videos
        </a>
    </div>

    <!-- Error Message -->
    @if(isset($error))
    <div class="stat-card bg-orange-500/10 border border-orange-500/30">
        <div class="flex items-start space-x-4">
            <i class="fas fa-info-circle text-orange-400 text-2xl"></i>
            <div>
                <h3 class="text-white font-bold mb-2">Setup Required</h3>
                <p class="text-gray-300 text-sm mb-3">{{ $error }}</p>
                <p class="text-gray-400 text-sm">To display your church's YouTube channel videos, add this to your <code class="bg-white/10 px-2 py-1 rounded">.env</code> file:</p>
                <div class="mt-2 p-3 bg-black/30 rounded-lg font-mono text-sm text-green-400">
                    YOUTUBE_CHANNEL_ID=your_channel_id_here
                </div>
                <p class="text-gray-400 text-xs mt-2">
                    <i class="fas fa-question-circle mr-1"></i>
                    Find your Channel ID at: <a href="https://www.youtube.com/account_advanced" target="_blank" class="text-blue-400 hover:underline">YouTube Settings</a>
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Video Grid -->
    @if(!isset($error) && count($videos) > 0)
    <div class="stat-card">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-white">
                <i class="fas fa-film mr-2 text-red-400"></i>Latest Videos
            </h2>
            <span class="px-3 py-1 bg-red-500/20 rounded-lg text-red-400 text-sm font-semibold">
                <i class="fas fa-video mr-1"></i>{{ count($videos) }} Videos
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($videos as $video)
        <div class="stat-card group hover:scale-105 transition-all cursor-pointer">
            <div class="relative overflow-hidden rounded-lg mb-3">
                <!-- Thumbnail -->
                <img src="{{ $video['snippet']['thumbnails']['high']['url'] ?? $video['snippet']['thumbnails']['medium']['url'] }}" 
                     alt="{{ $video['snippet']['title'] }}"
                     class="w-full h-48 object-cover">
                
                <!-- Play Overlay -->
                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-play text-white text-2xl ml-1"></i>
                    </div>
                </div>
                
                <!-- Date Badge -->
                <div class="absolute top-2 right-2 px-2 py-1 bg-black/80 rounded text-white text-xs font-semibold">
                    <i class="far fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($video['snippet']['publishedAt'])->format('M d') }}
                </div>
            </div>

            <!-- Video Info -->
            <h3 class="text-white font-bold text-sm mb-2 line-clamp-2 group-hover:text-red-400 transition-colors">
                {{ $video['snippet']['title'] }}
            </h3>
            
            <p class="text-gray-400 text-xs mb-2">
                <i class="far fa-clock mr-1"></i>{{ \Carbon\Carbon::parse($video['snippet']['publishedAt'])->diffForHumans() }}
            </p>
            
            <p class="text-gray-500 text-xs mb-3 line-clamp-2">
                {{ Str::limit($video['snippet']['description'], 100) }}
            </p>

            <!-- Action Buttons -->
            <div class="flex space-x-2">
                <a href="{{ route('media.youtube.show', $video['id']['videoId']) }}" 
                   class="flex-1 px-3 py-2 bg-red-500/20 rounded-lg text-red-400 hover:bg-red-500/30 text-center text-sm font-semibold transition-colors">
                    <i class="fas fa-play mr-1"></i>Watch
                </a>
                <button onclick="shareVideo('{{ $video['id']['videoId'] }}', '{{ addslashes($video['snippet']['title']) }}')" 
                        class="px-3 py-2 bg-white/10 rounded-lg text-gray-400 hover:bg-white/20 hover:text-white transition-colors">
                    <i class="fas fa-share-alt"></i>
                </button>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Load More -->
    <div class="text-center">
        <button onclick="loadMoreVideos()" class="px-8 py-3 bg-gradient-to-r from-red-600 to-red-700 rounded-xl text-white font-semibold hover:shadow-lg">
            <i class="fas fa-plus-circle mr-2"></i>Load More Videos
        </button>
    </div>
    @endif

    <!-- Empty State -->
    @if(!isset($error) && count($videos) == 0)
    <div class="stat-card text-center py-20">
        <i class="fab fa-youtube text-8xl text-gray-600 mb-4"></i>
        <h3 class="text-2xl font-bold text-white mb-2">No videos yet</h3>
        <p class="text-gray-400 mb-6">Your church hasn't uploaded any videos to YouTube yet</p>
        <a href="https://www.youtube.com/upload" target="_blank" class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 rounded-xl text-white font-semibold">
            <i class="fas fa-upload mr-2"></i>Upload First Video
        </a>
    </div>
    @endif

    <!-- Channel Stats (Optional) -->
    @if(!isset($error) && count($videos) > 0)
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="stat-card">
            <div class="flex items-center justify-between mb-2">
                <span class="text-gray-400 text-sm">Total Videos</span>
                <i class="fas fa-video text-red-400"></i>
            </div>
            <p class="text-3xl font-bold text-white">{{ count($videos) }}+</p>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between mb-2">
                <span class="text-gray-400 text-sm">Platform</span>
                <i class="fab fa-youtube text-red-400"></i>
            </div>
            <p class="text-3xl font-bold text-white">YouTube</p>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between mb-2">
                <span class="text-gray-400 text-sm">Latest Upload</span>
                <i class="far fa-calendar text-green-400"></i>
            </div>
            <p class="text-lg font-bold text-white">{{ \Carbon\Carbon::parse($videos[0]['snippet']['publishedAt'])->format('M d, Y') }}</p>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between mb-2">
                <span class="text-gray-400 text-sm">Content Type</span>
                <i class="fas fa-church text-purple-400"></i>
            </div>
            <p class="text-lg font-bold text-white">Ministry</p>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
function shareVideo(videoId, title) {
    const url = `https://www.youtube.com/watch?v=${videoId}`;
    
    if (navigator.share) {
        navigator.share({
            title: title,
            text: 'Check out this video from our church!',
            url: url
        }).then(() => {
            console.log('✅ Shared successfully');
        }).catch((error) => {
            copyToClipboard(url);
        });
    } else {
        copyToClipboard(url);
    }
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('✅ Video link copied to clipboard!\n\n' + text);
    }).catch(() => {
        alert('Video URL: ' + text);
    });
}

function loadMoreVideos() {
    alert('🔄 Load more functionality\n\nThis will load the next page of videos from your channel.\n\n(Requires pagination implementation)');
}
</script>
@endpush
@endsection
