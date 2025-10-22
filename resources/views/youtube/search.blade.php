@extends('layouts.media')
@section('title', 'YouTube Search')
@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-black text-white">
                <i class="fab fa-youtube text-red-500 mr-3"></i>YouTube Search
            </h1>
            <p class="text-gray-400 mt-2">Search and discover worship videos</p>
        </div>
        <a href="{{ route('media.youtube.channel') }}" class="px-6 py-3 bg-red-600 hover:bg-red-700 rounded-xl text-white font-semibold">
            <i class="fas fa-tv mr-2"></i>Our Channel
        </a>
    </div>

    <!-- Search Box -->
    <div class="stat-card">
        <form method="GET" action="{{ route('media.youtube.search') }}" class="flex space-x-3">
            <input type="text" name="q" value="{{ $query }}" 
                   placeholder="Search YouTube... (e.g., worship songs, sermons)"
                   class="flex-1 px-6 py-4 bg-white/10 border border-white/20 rounded-xl text-white text-lg placeholder-gray-400 focus:outline-none focus:border-red-500">
            <button type="submit" class="px-8 py-4 bg-gradient-to-r from-red-600 to-red-700 rounded-xl text-white font-semibold hover:shadow-lg transition-all">
                <i class="fas fa-search mr-2"></i>Search
            </button>
        </form>
        
        @if(isset($error))
        <div class="mt-4 p-4 bg-red-500/20 border border-red-500/30 rounded-xl">
            <p class="text-red-400"><i class="fas fa-exclamation-triangle mr-2"></i>{{ $error }}</p>
        </div>
        @endif
    </div>

    <!-- Results Count -->
    @if(count($videos) > 0)
    <div class="flex justify-between items-center">
        <p class="text-gray-400">
            <i class="fas fa-video mr-2"></i>Found <span class="text-white font-bold">{{ count($videos) }}</span> videos for "<span class="text-red-400">{{ $query }}</span>"
        </p>
        <div class="flex space-x-2">
            <span class="px-3 py-1 bg-white/5 rounded-lg text-gray-400 text-sm">
                <i class="fas fa-sort-amount-down mr-1"></i>Sorted by relevance
            </span>
        </div>
    </div>
    @endif

    <!-- Video Grid -->
    @if(count($videos) > 0)
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
                
                <!-- Duration Badge (if available) -->
                <div class="absolute bottom-2 right-2 px-2 py-1 bg-black/80 rounded text-white text-xs font-semibold">
                    <i class="far fa-clock mr-1"></i>Video
                </div>
            </div>

            <!-- Video Info -->
            <h3 class="text-white font-bold text-sm mb-2 line-clamp-2 group-hover:text-red-400 transition-colors">
                {{ $video['snippet']['title'] }}
            </h3>
            
            <p class="text-gray-400 text-xs mb-2">
                <i class="fas fa-user mr-1"></i>{{ $video['snippet']['channelTitle'] }}
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
    @else
    <div class="stat-card text-center py-20">
        <i class="fab fa-youtube text-8xl text-gray-600 mb-4"></i>
        <h3 class="text-2xl font-bold text-white mb-2">No videos found</h3>
        <p class="text-gray-400 mb-6">Try a different search term</p>
        <div class="flex flex-wrap justify-center gap-2">
            <a href="{{ route('media.youtube.search', ['q' => 'worship songs']) }}" class="px-4 py-2 bg-white/10 rounded-lg text-white hover:bg-white/20">
                Worship Songs
            </a>
            <a href="{{ route('media.youtube.search', ['q' => 'gospel music']) }}" class="px-4 py-2 bg-white/10 rounded-lg text-white hover:bg-white/20">
                Gospel Music
            </a>
            <a href="{{ route('media.youtube.search', ['q' => 'christian sermons']) }}" class="px-4 py-2 bg-white/10 rounded-lg text-white hover:bg-white/20">
                Sermons
            </a>
            <a href="{{ route('media.youtube.search', ['q' => 'praise and worship']) }}" class="px-4 py-2 bg-white/10 rounded-lg text-white hover:bg-white/20">
                Praise & Worship
            </a>
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
            text: 'Check out this video!',
            url: url
        }).then(() => {
            console.log('✅ Shared successfully');
        }).catch((error) => {
            console.log('Error sharing:', error);
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
</script>
@endpush
@endsection
