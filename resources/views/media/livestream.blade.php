@extends('layouts.media')
@section('title', 'Livestream Control')
@section('content')
<div class="space-y-6">
<!-- Header -->
<div class="flex justify-between items-center">
<div>
<h1 class="text-4xl font-black text-white">
<i class="fas fa-broadcast-tower text-red-500 mr-3"></i>Livestream Control</h1>
<p class="text-gray-400 mt-2">Manage livestreams for all services and events</p></div>
<button onclick="openNewStreamModal()" class="px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 rounded-xl text-white font-semibold hover:shadow-lg">
<i class="fas fa-plus-circle mr-2"></i>New Stream</button></div>

<!-- Active Stream Alert -->
@if($activeStream)
<div class="stat-card bg-gradient-to-r from-red-500/20 to-orange-500/20 border-2 border-red-500/50 animate-pulse">
<div class="flex items-center justify-between">
<div class="flex items-center space-x-4">
<div class="w-4 h-4 bg-red-500 rounded-full animate-pulse"></div>
<div>
<h3 class="text-xl font-bold text-white">🔴 LIVE NOW</h3>
<p class="text-red-200">{{ $activeStream->title }}</p>
<p class="text-red-300 text-sm">{{ $activeStream->platform }} • {{ $activeStream->started_at->diffForHumans() }}</p></div></div>
<div class="text-right">
<p class="text-3xl font-bold text-white">{{ $activeStream->peak_viewers ?? 0 }}</p>
<p class="text-gray-400 text-sm">Peak Viewers</p></div>
<div class="flex space-x-2">
<button onclick="viewLivestream({{ $activeStream->id }})" class="px-4 py-2 bg-blue-500 rounded-lg text-white font-semibold">
<i class="fas fa-eye mr-1"></i>View</button>
<button onclick="stopStream({{ $activeStream->id }})" class="px-4 py-2 bg-red-600 rounded-lg text-white font-semibold">
<i class="fas fa-stop mr-1"></i>Stop</button></div></div></div>
@endif

<!-- Stats Dashboard -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4">
<div class="stat-card">
<div class="flex items-center justify-between mb-2">
<span class="text-gray-400 text-sm">Active Streams</span>
<i class="fas fa-broadcast-tower text-red-500"></i></div>
<p class="text-3xl font-bold text-white">{{ $stats['active'] ?? 0 }}</p>
<p class="text-xs text-gray-500 mt-1">Currently live</p></div>
<div class="stat-card">
<div class="flex items-center justify-between mb-2">
<span class="text-gray-400 text-sm">Total Views Today</span>
<i class="fas fa-eye text-blue-400"></i></div>
<p class="text-3xl font-bold text-white">{{ number_format($stats['views_today'] ?? 0) }}</p>
<p class="text-xs text-gray-500 mt-1">Live + Replay</p></div>
<div class="stat-card">
<div class="flex items-center justify-between mb-2">
<span class="text-gray-400 text-sm">Peak Viewers</span>
<i class="fas fa-users text-green-400"></i></div>
<p class="text-3xl font-bold text-white">{{ $stats['peak_viewers'] ?? 0 }}</p>
<p class="text-xs text-gray-500 mt-1">This month</p></div>
<div class="stat-card">
<div class="flex items-center justify-between mb-2">
<span class="text-gray-400 text-sm">Archived</span>
<i class="fas fa-archive text-purple-400"></i></div>
<p class="text-3xl font-bold text-white">{{ $stats['archived'] ?? 0 }}</p>
<p class="text-xs text-gray-500 mt-1">Past streams</p></div></div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
<!-- Main Control Panel -->
<div class="lg:col-span-2 space-y-4">
<!-- Scheduled Streams -->
<div class="stat-card">
<div class="flex justify-between items-center mb-4">
<h2 class="text-xl font-bold text-white">
<i class="fas fa-calendar-check text-blue-400 mr-2"></i>Scheduled Streams</h2>
<select onchange="filterStreams(this.value)" class="px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white text-sm">
<option value="scheduled">Scheduled</option>
<option value="live">Live Now</option>
<option value="ended">Recently Ended</option>
<option value="all">All Streams</option></select></div>

@if($streams->count() > 0)
<div class="space-y-3">
@foreach($streams as $stream)
<div class="p-4 bg-white/5 rounded-xl hover:bg-white/10 transition-all stream-item" data-status="{{ $stream->status }}">
<div class="flex items-start justify-between">
<div class="flex-1">
<div class="flex items-center space-x-2 mb-2">
<h3 class="text-white font-bold text-lg">{{ $stream->title }}</h3>
@if($stream->status == 'live')
<span class="px-2 py-1 bg-red-500 rounded-lg text-white text-xs font-semibold animate-pulse">
<i class="fas fa-circle mr-1"></i>LIVE</span>
@elseif($stream->status == 'scheduled')
<span class="px-2 py-1 bg-blue-500/20 rounded-lg text-blue-400 text-xs font-semibold">
<i class="fas fa-clock mr-1"></i>SCHEDULED</span>
@elseif($stream->status == 'ended')
<span class="px-2 py-1 bg-gray-500/20 rounded-lg text-gray-400 text-xs font-semibold">
<i class="fas fa-check-circle mr-1"></i>ENDED</span>
@endif
</div>

<div class="flex items-center space-x-4 text-sm text-gray-400 mb-2">
<span><i class="fas fa-calendar mr-1"></i>{{ $stream->scheduled_at ? $stream->scheduled_at->format('M d, h:i A') : 'Not scheduled' }}</span>
@if($stream->platform)
<span><i class="fab fa-{{ strtolower($stream->platform) == 'youtube' ? 'youtube' : (strtolower($stream->platform) == 'facebook' ? 'facebook' : 'server') }} mr-1"></i>{{ $stream->platform }}</span>
@endif
</div>

@if($stream->description)
<p class="text-gray-400 text-sm mb-2">{{ $stream->description }}</p>
@endif

<div class="flex items-center space-x-3 text-sm">
@if($stream->status == 'live' || $stream->status == 'ended')
<span class="text-green-400"><i class="fas fa-eye mr-1"></i>{{ number_format($stream->total_views ?? 0) }} views</span>
<span class="text-blue-400"><i class="fas fa-users mr-1"></i>{{ $stream->peak_viewers ?? 0 }} peak</span>
@endif
@if($stream->status == 'live')
<span class="text-orange-400"><i class="fas fa-clock mr-1"></i>{{ $stream->started_at->diffForHumans() }}</span>
@endif
</div>
</div>

<!-- Action Buttons -->
<div class="flex flex-col space-y-2 ml-4">
@if($stream->status == 'scheduled')
<button onclick="startStream({{ $stream->id }})" class="px-4 py-2 bg-green-500 rounded-lg text-white font-semibold whitespace-nowrap hover:bg-green-600">
<i class="fas fa-play mr-1"></i>Start</button>
<button onclick="editStream({{ $stream->id }})" class="px-4 py-2 bg-blue-500/20 rounded-lg text-blue-400 hover:bg-blue-500/30 text-sm font-semibold whitespace-nowrap">
<i class="fas fa-edit mr-1"></i>Edit</button>
@elseif($stream->status == 'live')
<button onclick="viewLivestream({{ $stream->id }})" class="px-4 py-2 bg-blue-500 rounded-lg text-white font-semibold whitespace-nowrap">
<i class="fas fa-eye mr-1"></i>View</button>
<button onclick="moderateChat({{ $stream->id }})" class="px-4 py-2 bg-purple-500/20 rounded-lg text-purple-400 hover:bg-purple-500/30 text-sm font-semibold whitespace-nowrap">
<i class="fas fa-comments mr-1"></i>Chat</button>
<button onclick="stopStream({{ $stream->id }})" class="px-4 py-2 bg-red-600 rounded-lg text-white font-semibold whitespace-nowrap">
<i class="fas fa-stop mr-1"></i>Stop</button>
@else
<button onclick="viewArchive({{ $stream->id }})" class="px-4 py-2 bg-purple-500/20 rounded-lg text-purple-400 hover:bg-purple-500/30 text-sm font-semibold whitespace-nowrap">
<i class="fas fa-archive mr-1"></i>Archive</button>
<button onclick="uploadToLibrary({{ $stream->id }})" class="px-4 py-2 bg-green-500/20 rounded-lg text-green-400 hover:bg-green-500/30 text-sm font-semibold whitespace-nowrap">
<i class="fas fa-upload mr-1"></i>Upload</button>
@endif
</div>
</div>
</div>
@endforeach
</div>
@else
<div class="text-center py-20">
<i class="fas fa-broadcast-tower text-6xl text-gray-500 mb-4"></i>
<h3 class="text-xl font-bold text-white mb-2">No Streams Yet</h3>
<p class="text-gray-400 mb-4">Create your first livestream</p>
<button onclick="openNewStreamModal()" class="px-6 py-3 bg-red-500 rounded-xl text-white font-semibold">
<i class="fas fa-plus-circle mr-2"></i>New Stream</button>
</div>
@endif
</div>
</div>

<!-- Sidebar -->
<div class="space-y-4">
<!-- Platform Connections -->
<div class="stat-card">
<h3 class="text-lg font-bold text-white mb-4">
<i class="fas fa-plug text-green-400 mr-2"></i>Platform Connections</h3>
<div class="space-y-3">
<div class="p-3 bg-white/5 rounded-lg">
<div class="flex items-center justify-between mb-2">
<div class="flex items-center space-x-2">
<i class="fab fa-youtube text-red-500 text-xl"></i>
<span class="text-white font-semibold">YouTube Live</span>
</div>
<span class="px-2 py-1 bg-green-500/20 rounded text-green-400 text-xs font-semibold">Connected</span>
</div>
<button onclick="manageConnection('youtube')" class="w-full px-3 py-2 bg-blue-500/20 rounded-lg text-blue-400 hover:bg-blue-500/30 text-sm">
<i class="fas fa-cog mr-1"></i>Manage</button>
</div>

<div class="p-3 bg-white/5 rounded-lg">
<div class="flex items-center justify-between mb-2">
<div class="flex items-center space-x-2">
<i class="fab fa-facebook text-blue-500 text-xl"></i>
<span class="text-white font-semibold">Facebook Live</span>
</div>
<span class="px-2 py-1 bg-green-500/20 rounded text-green-400 text-xs font-semibold">Connected</span>
</div>
<button onclick="manageConnection('facebook')" class="w-full px-3 py-2 bg-blue-500/20 rounded-lg text-blue-400 hover:bg-blue-500/30 text-sm">
<i class="fas fa-cog mr-1"></i>Manage</button>
</div>

<div class="p-3 bg-white/5 rounded-lg">
<div class="flex items-center justify-between mb-2">
<div class="flex items-center space-x-2">
<i class="fas fa-server text-purple-500 text-xl"></i>
<span class="text-white font-semibold">Custom RTMP</span>
</div>
<span class="px-2 py-1 bg-gray-500/20 rounded text-gray-400 text-xs font-semibold">Available</span>
</div>
<button onclick="setupRTMP()" class="w-full px-3 py-2 bg-purple-500/20 rounded-lg text-purple-400 hover:bg-purple-500/30 text-sm">
<i class="fas fa-plus mr-1"></i>Setup</button>
</div>
</div>
</div>

<!-- Quick Actions -->
<div class="stat-card">
<h3 class="text-lg font-bold text-white mb-4">
<i class="fas fa-bolt text-yellow-400 mr-2"></i>Quick Actions</h3>
<div class="space-y-2">
<button onclick="viewStreamKeys()" class="w-full px-4 py-3 bg-white/5 rounded-lg text-white hover:bg-white/10 text-left">
<i class="fas fa-key text-yellow-400 mr-2"></i>View Stream Keys</button>
<button onclick="viewerStats()" class="w-full px-4 py-3 bg-white/5 rounded-lg text-white hover:bg-white/10 text-left">
<i class="fas fa-chart-line text-green-400 mr-2"></i>Viewer Statistics</button>
<button onclick="captionSettings()" class="w-full px-4 py-3 bg-white/5 rounded-lg text-white hover:bg-white/10 text-left">
<i class="fas fa-closed-captioning text-blue-400 mr-2"></i>AI Captions</button>
<button onclick="archiveManager()" class="w-full px-4 py-3 bg-white/5 rounded-lg text-white hover:bg-white/10 text-left">
<i class="fas fa-archive text-purple-400 mr-2"></i>Archive Manager</button>
</div>
</div>

<!-- Recent Activity -->
<div class="stat-card">
<h3 class="text-lg font-bold text-white mb-4">
<i class="fas fa-clock text-orange-400 mr-2"></i>Recent Activity</h3>
<div class="space-y-2 text-sm">
<div class="p-2">
<p class="text-white">Stream started</p>
<p class="text-gray-500 text-xs">Sunday Service - 2 hours ago</p>
</div>
<div class="p-2">
<p class="text-white">Recording uploaded</p>
<p class="text-gray-500 text-xs">Youth Rally - 1 day ago</p>
</div>
<div class="p-2">
<p class="text-white">Chat moderated</p>
<p class="text-gray-500 text-xs">12 messages removed - 3 days ago</p>
</div>
</div>
</div>
</div>
</div>
</div>

<!-- Include Modals -->
@include('media.livestream_modals')

@push('scripts')
<script>
// Filter Streams
function filterStreams(status){
    const streams = document.querySelectorAll('.stream-item');
    streams.forEach(stream => {
        const streamStatus = stream.dataset.status;
        if(status === 'all' || streamStatus === status){
            stream.style.display = 'block';
        } else {
            stream.style.display = 'none';
        }
    });
}

// New Stream Modal
function openNewStreamModal(){
    console.log('Opening new stream modal...');
    const modal = document.getElementById('newStreamModal');
    if(modal){
        modal.classList.remove('hidden');
        console.log('Modal opened successfully');
    } else {
        console.error('Modal element not found!');
        alert('Error: Modal not found. Please refresh the page.');
    }
}

function closeNewStreamModal(){
    const modal = document.getElementById('newStreamModal');
    if(modal){
        modal.classList.add('hidden');
    }
}

function updatePlatformFields(){
    const platform = document.getElementById('platform_select').value;
    const fieldsContainer = document.getElementById('platform_fields');
    
    if(platform === 'youtube'){
        fieldsContainer.innerHTML = `
            <div class="p-3 bg-red-500/10 border border-red-500/30 rounded-lg">
                <p class="text-white font-semibold mb-2"><i class="fab fa-youtube mr-2"></i>YouTube Settings</p>
                <p class="text-gray-400 text-sm">Stream will be created in your connected YouTube account</p>
            </div>`;
    } else if(platform === 'facebook'){
        fieldsContainer.innerHTML = `
            <div class="p-3 bg-blue-500/10 border border-blue-500/30 rounded-lg">
                <p class="text-white font-semibold mb-2"><i class="fab fa-facebook mr-2"></i>Facebook Settings</p>
                <p class="text-gray-400 text-sm">Stream will be created on your Facebook Page</p>
            </div>`;
    } else if(platform === 'custom_rtmp'){
        fieldsContainer.innerHTML = `
            <div class="p-3 bg-purple-500/10 border border-purple-500/30 rounded-lg">
                <p class="text-white font-semibold mb-2"><i class="fas fa-server mr-2"></i>RTMP Settings</p>
                <input type="text" name="rtmp_url" placeholder="RTMP Server URL" class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white mb-2">
                <input type="text" name="rtmp_key" placeholder="Stream Key" class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white">
            </div>`;
    } else {
        fieldsContainer.innerHTML = '';
    }
}

// Start Stream
function startStream(streamId){
    if(confirm('Start livestream now?')){
        fetch(`{{ url('media/livestream/start') }}/${streamId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => response.json())
        .then(data => {
            if(data.success){
                alert('✅ Stream started!\n\n' + data.message);
                window.location.reload();
            } else {
                alert('❌ Failed:\n\n' + data.message);
            }
        }).catch(error => {
            console.error('Error:', error);
            alert('❌ Error starting stream.');
        });
    }
}

// Stop Stream
function stopStream(streamId){
    if(confirm('Stop livestream? This will end the broadcast and save the recording.')){
        fetch(`{{ url('media/livestream/stop') }}/${streamId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => response.json())
        .then(data => {
            if(data.success){
                alert('✅ Stream stopped!\n\nRecording saved.\nDuration: ' + data.duration);
                window.location.reload();
            } else {
                alert('❌ Failed:\n\n' + data.message);
            }
        }).catch(error => {
            console.error('Error:', error);
            alert('❌ Error stopping stream.');
        });
    }
}

// View Livestream
function viewLivestream(streamId){
    window.open(`{{ url('media/livestream/view') }}/${streamId}`, '_blank');
}

// Edit Stream
function editStream(streamId){
    alert('🔧 Edit Stream\n\nStream ID: ' + streamId + '\n\nEdit functionality ready for integration.');
}

// Chat Moderation
function moderateChat(streamId){
    document.getElementById('chatModerationModal').classList.remove('hidden');
}

function closeChatModal(){
    document.getElementById('chatModerationModal').classList.add('hidden');
}

function enableSlowMode(){
    alert('⏱️ Slow Mode Enabled\n\nUsers can send 1 message per 10 seconds.');
}

function clearChat(){
    if(confirm('Clear all chat messages?')){
        alert('✅ Chat cleared!');
    }
}

// View Archive
function viewArchive(streamId){
    document.getElementById('archiveModal').classList.remove('hidden');
}

function closeArchiveModal(){
    document.getElementById('archiveModal').classList.add('hidden');
}

// Upload to Library
function uploadToLibrary(streamId){
    if(confirm('Upload this stream recording to Media Library?')){
        fetch(`{{ url('media/livestream/upload-to-library') }}/${streamId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => response.json())
        .then(data => {
            if(data.success){
                alert('✅ Recording uploaded to Media Library!\n\nFile: ' + data.filename);
            } else {
                alert('❌ Upload failed:\n\n' + data.message);
            }
        }).catch(error => {
            console.error('Error:', error);
            alert('❌ Error uploading.');
        });
    }
}

// Stream Keys
function viewStreamKeys(){
    document.getElementById('streamKeysModal').classList.remove('hidden');
}

function closeStreamKeysModal(){
    document.getElementById('streamKeysModal').classList.add('hidden');
}

function toggleVisibility(inputId){
    const input = document.getElementById(inputId);
    input.type = input.type === 'password' ? 'text' : 'password';
}

function copyToClipboard(inputId){
    const input = document.getElementById(inputId);
    input.select();
    document.execCommand('copy');
    alert('✅ Copied to clipboard!');
}

// Viewer Stats
function viewerStats(){
    document.getElementById('viewerStatsModal').classList.remove('hidden');
}

function closeViewerStatsModal(){
    document.getElementById('viewerStatsModal').classList.add('hidden');
}

// Captions
function captionSettings(){
    document.getElementById('captionsModal').classList.remove('hidden');
}

function closeCaptionsModal(){
    document.getElementById('captionsModal').classList.add('hidden');
}

// Archive Manager
function archiveManager(){
    document.getElementById('archiveModal').classList.remove('hidden');
}

// Platform Connections
function manageConnection(platform){
    alert('🔌 Manage ' + platform.toUpperCase() + ' Connection\n\nSettings and OAuth integration ready.');
}

function setupRTMP(){
    alert('🖥️ Custom RTMP Setup\n\nConfigure your custom RTMP server settings.');
}

// New Stream Form Submission
document.addEventListener('DOMContentLoaded', function(){
    console.log('DOM Content Loaded - Setting up form listener');
    const newStreamForm = document.getElementById('newStreamForm');
    if(newStreamForm){
        console.log('New stream form found!');
        newStreamForm.addEventListener('submit', async function(e){
            e.preventDefault();
            console.log('Form submitted!');
            const formData = new FormData(this);
            
            // Log form data for debugging
            console.log('Form data:');
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
            
            try {
                console.log('Sending request to:', '{{ route("media.livestream.create") }}');
                const response = await fetch('{{ route("media.livestream.create") }}', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                
                console.log('Response status:', response.status);
                const responseText = await response.text();
                console.log('Response text:', responseText);
                
                let data;
                try {
                    data = JSON.parse(responseText);
                } catch(parseError) {
                    console.error('Failed to parse JSON:', parseError);
                    alert('❌ Server error. Please check console for details.\n\nResponse: ' + responseText.substring(0, 200));
                    return;
                }
                
                if(response.ok && data.success){
                    alert('✅ Stream created!\n\n' + data.message + '\n\nStream ID: ' + data.stream_id);
                    window.location.reload();
                } else {
                    alert('❌ Failed:\n\n' + (data.message || 'Unknown error'));
                }
            } catch(error){
                console.error('Error:', error);
                alert('❌ Error creating stream. Check console for details.\n\nError: ' + error.message);
            }
        });
    } else {
        console.error('New stream form NOT found!');
    }
});
</script>
@endpush
@endsection
