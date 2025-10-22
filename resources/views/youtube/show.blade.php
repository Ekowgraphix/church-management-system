@extends('layouts.media')
@section('title', $video['snippet']['title'] ?? 'Video Player')
@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('media.youtube.search') }}" class="inline-flex items-center px-4 py-2 bg-white/10 rounded-lg text-white hover:bg-white/20 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back to Search
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Video Player -->
        <div class="lg:col-span-2 space-y-4">
            <!-- Video Embed -->
            <div class="stat-card p-0 overflow-hidden">
                <div class="relative" style="padding-bottom: 56.25%;">
                    <iframe class="absolute top-0 left-0 w-full h-full"
                            src="https://www.youtube.com/embed/{{ $video['id'] }}?autoplay=0&rel=0"
                            title="{{ $video['snippet']['title'] }}"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                    </iframe>
                </div>
            </div>

            <!-- Video Info -->
            <div class="stat-card">
                <h1 class="text-2xl font-bold text-white mb-4">{{ $video['snippet']['title'] }}</h1>
                
                <div class="flex items-center justify-between mb-4 pb-4 border-b border-white/10">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-r from-red-500 to-pink-500 flex items-center justify-center">
                            <i class="fas fa-tv text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="text-white font-semibold">{{ $video['snippet']['channelTitle'] }}</p>
                            <p class="text-gray-400 text-sm">
                                <i class="far fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($video['snippet']['publishedAt'])->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                    
                    <a href="https://www.youtube.com/channel/{{ $video['snippet']['channelId'] }}" target="_blank" 
                       class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg text-white font-semibold transition-colors">
                        <i class="fas fa-external-link-alt mr-2"></i>View Channel
                    </a>
                </div>

                <!-- Stats -->
                @if(isset($video['statistics']))
                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div class="text-center p-3 bg-white/5 rounded-lg">
                        <p class="text-2xl font-bold text-white">{{ number_format($video['statistics']['viewCount']) }}</p>
                        <p class="text-gray-400 text-sm"><i class="fas fa-eye mr-1"></i>Views</p>
                    </div>
                    <div class="text-center p-3 bg-white/5 rounded-lg">
                        <p class="text-2xl font-bold text-green-400">{{ number_format($video['statistics']['likeCount'] ?? 0) }}</p>
                        <p class="text-gray-400 text-sm"><i class="fas fa-thumbs-up mr-1"></i>Likes</p>
                    </div>
                    <div class="text-center p-3 bg-white/5 rounded-lg">
                        <p class="text-2xl font-bold text-blue-400">{{ number_format($video['statistics']['commentCount'] ?? 0) }}</p>
                        <p class="text-gray-400 text-sm"><i class="fas fa-comments mr-1"></i>Comments</p>
                    </div>
                </div>
                @endif

                <!-- Description -->
                <div class="bg-white/5 rounded-lg p-4">
                    <h3 class="text-white font-bold mb-2 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-blue-400"></i>Description
                    </h3>
                    <p class="text-gray-300 text-sm whitespace-pre-line">{{ $video['snippet']['description'] ?? 'No description available.' }}</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-3 mt-4">
                    <button onclick="shareVideo('{{ $video['id'] }}', '{{ addslashes($video['snippet']['title']) }}')" 
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl text-white font-semibold hover:shadow-lg">
                        <i class="fas fa-share-alt mr-2"></i>Share Video
                    </button>
                    <a href="https://www.youtube.com/watch?v={{ $video['id'] }}" target="_blank"
                       class="flex-1 px-4 py-3 bg-gradient-to-r from-red-600 to-red-700 rounded-xl text-white font-semibold hover:shadow-lg text-center">
                        <i class="fab fa-youtube mr-2"></i>Watch on YouTube
                    </a>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-4">
            <!-- Tags -->
            @if(isset($video['snippet']['tags']) && count($video['snippet']['tags']) > 0)
            <div class="stat-card">
                <h3 class="text-white font-bold mb-3 flex items-center">
                    <i class="fas fa-tags mr-2 text-purple-400"></i>Tags
                </h3>
                <div class="flex flex-wrap gap-2">
                    @foreach(array_slice($video['snippet']['tags'], 0, 10) as $tag)
                    <span class="px-3 py-1 bg-purple-500/20 rounded-lg text-purple-400 text-xs">
                        #{{ $tag }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Video Details -->
            <div class="stat-card">
                <h3 class="text-white font-bold mb-3 flex items-center">
                    <i class="fas fa-list mr-2 text-orange-400"></i>Details
                </h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between py-2 border-b border-white/10">
                        <span class="text-gray-400">Video ID</span>
                        <span class="text-white font-mono">{{ $video['id'] }}</span>
                    </div>
                    @if(isset($video['contentDetails']['duration']))
                    <div class="flex justify-between py-2 border-b border-white/10">
                        <span class="text-gray-400">Duration</span>
                        <span class="text-white">{{ formatDuration($video['contentDetails']['duration']) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between py-2 border-b border-white/10">
                        <span class="text-gray-400">Published</span>
                        <span class="text-white">{{ \Carbon\Carbon::parse($video['snippet']['publishedAt'])->diffForHumans() }}</span>
                    </div>
                    @if(isset($video['snippet']['categoryId']))
                    <div class="flex justify-between py-2">
                        <span class="text-gray-400">Category</span>
                        <span class="text-white">{{ $video['snippet']['categoryId'] }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Search -->
            <div class="stat-card">
                <h3 class="text-white font-bold mb-3 flex items-center">
                    <i class="fas fa-search mr-2 text-green-400"></i>Quick Search
                </h3>
                <div class="space-y-2">
                    <a href="{{ route('media.youtube.search', ['q' => 'worship']) }}" class="block px-4 py-2 bg-white/5 rounded-lg text-white hover:bg-white/10 transition-colors">
                        <i class="fas fa-music mr-2 text-green-400"></i>Worship Songs
                    </a>
                    <a href="{{ route('media.youtube.search', ['q' => 'sermons']) }}" class="block px-4 py-2 bg-white/5 rounded-lg text-white hover:bg-white/10 transition-colors">
                        <i class="fas fa-bible mr-2 text-blue-400"></i>Sermons
                    </a>
                    <a href="{{ route('media.youtube.search', ['q' => 'gospel']) }}" class="block px-4 py-2 bg-white/5 rounded-lg text-white hover:bg-white/10 transition-colors">
                        <i class="fas fa-dove mr-2 text-purple-400"></i>Gospel Music
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function shareVideo(videoId, title) {
    const url = `https://www.youtube.com/watch?v=${videoId}`;
    
    if (navigator.share) {
        navigator.share({
            title: title,
            text: 'Check out this video!',
            url: url
        }).then(() => {
            alert('✅ Video shared successfully!');
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
        prompt('Copy this link:', text);
    });
}
</script>
@endpush

@php
function formatDuration($duration) {
    preg_match_all('/(\d+)([HMS])/', $duration, $matches);
    $hours = 0;
    $minutes = 0;
    $seconds = 0;
    
    foreach ($matches[1] as $key => $value) {
        if ($matches[2][$key] == 'H') $hours = $value;
        if ($matches[2][$key] == 'M') $minutes = $value;
        if ($matches[2][$key] == 'S') $seconds = $value;
    }
    
    if ($hours > 0) {
        return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
    } else {
        return sprintf('%d:%02d', $minutes, $seconds);
    }
}
@endphp
@endsection
