@extends('layouts.media')
@section('title', 'Event Media Scheduling')
@section('content')
<div class="space-y-6">
<!-- Header with AI Reminder -->
<div class="flex justify-between items-center">
<div>
<h1 class="text-4xl font-black text-white">
<i class="fas fa-calendar-alt text-green-400 mr-3"></i>Event Media Scheduling</h1>
<p class="text-gray-400 mt-2">Assign media coverage to events and track completion</p></div>
<button onclick="openQuickAssign()" class="px-6 py-3 gradient-green rounded-xl text-white font-semibold hover:shadow-lg">
<i class="fas fa-user-plus mr-2"></i>Quick Assign</button></div>

<!-- AI Reminder Alert -->
@php
$pendingCount = $myAssignments->where('status', 'pending')->count();
@endphp
@if($pendingCount > 0)
<div class="stat-card bg-gradient-to-r from-orange-500/20 to-red-500/20 border-2 border-orange-500/30">
<div class="flex items-start space-x-4">
<div class="flex-shrink-0">
<div class="w-12 h-12 rounded-full bg-orange-500/30 flex items-center justify-center">
<i class="fas fa-robot text-orange-400 text-xl"></i></div></div>
<div class="flex-1">
<h3 class="text-lg font-bold text-white mb-1">
<i class="fas fa-bell mr-2"></i>AI Reminder</h3>
<p class="text-orange-200 font-semibold">
You have {{ $pendingCount }} unsent media package{{ $pendingCount > 1 ? 's' : '' }} waiting for upload.</p>
<div class="mt-3 space-y-1">
@foreach($myAssignments->where('status', 'pending')->take(3) as $assignment)
<p class="text-sm text-orange-300">
<i class="fas fa-circle text-xs mr-2"></i>{{ $assignment->event->title }} - {{ $assignment->role }}</p>
@endforeach
</div>
<button onclick="scrollToMyAssignments()" class="mt-4 px-5 py-2 bg-orange-500 hover:bg-orange-600 rounded-lg text-white font-semibold">
<i class="fas fa-upload mr-2"></i>Upload Media Now</button></div></div></div>
@endif

<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4">
<div class="stat-card">
<div class="flex items-center justify-between mb-2">
<span class="text-gray-400 text-sm">Upcoming Events</span>
<i class="fas fa-calendar text-blue-400"></i></div>
<p class="text-3xl font-bold text-white">{{ $events->count() }}</p>
<p class="text-xs text-gray-500 mt-1">Next 30 days</p></div>
<div class="stat-card">
<div class="flex items-center justify-between mb-2">
<span class="text-gray-400 text-sm">My Assignments</span>
<i class="fas fa-tasks text-purple-400"></i></div>
<p class="text-3xl font-bold text-white">{{ $myAssignments->count() }}</p>
<p class="text-xs text-gray-500 mt-1">Active tasks</p></div>
<div class="stat-card">
<div class="flex items-center justify-between mb-2">
<span class="text-gray-400 text-sm">Pending Upload</span>
<i class="fas fa-upload text-orange-400"></i></div>
<p class="text-3xl font-bold text-white">{{ $myAssignments->where('status', 'pending')->count() }}</p>
<p class="text-xs text-gray-500 mt-1">Awaiting media</p></div>
<div class="stat-card">
<div class="flex items-center justify-between mb-2">
<span class="text-gray-400 text-sm">Completed</span>
<i class="fas fa-check-circle text-green-400"></i></div>
<p class="text-3xl font-bold text-white">{{ $myAssignments->where('status', 'completed')->count() }}</p>
<p class="text-xs text-gray-500 mt-1">This month</p></div></div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
<!-- Upcoming Events -->
<div class="lg:col-span-2 space-y-4">
<div class="stat-card">
<div class="flex justify-between items-center mb-4">
<h2 class="text-xl font-bold text-white">
<i class="fas fa-calendar-week text-blue-400 mr-2"></i>Upcoming Events</h2>
<select onchange="filterEvents(this.value)" class="px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white text-sm">
<option value="all">All Events</option>
<option value="assigned">Assigned</option>
<option value="unassigned">Unassigned</option>
<option value="urgent">Urgent (< 3 days)</option></select></div>

@if($events->count() > 0)
<div class="space-y-3" id="eventsList">
@foreach($events as $event)
@php
$assignmentsCount = $event->mediaAssignments->count();
$daysUntil = $event->start_date->diffInDays(now());
$isUrgent = $daysUntil < 3 && !$event->start_date->isPast();
$isPast = $event->start_date->isPast();
@endphp
<div class="p-4 bg-white/5 rounded-xl hover:bg-white/10 transition-all event-item" data-status="{{ $assignmentsCount > 0 ? 'assigned' : 'unassigned' }}" data-urgent="{{ $isUrgent ? 'true' : 'false' }}" data-past="{{ $isPast ? 'true' : 'false' }}">
<div class="flex items-start justify-between">
<div class="flex-1">
<div class="flex items-center space-x-2 mb-2">
<h3 class="text-white font-bold text-lg">{{ $event->title }}</h3>
@if($isUrgent)
<span class="px-2 py-1 bg-red-500/20 rounded-lg text-red-400 text-xs font-semibold animate-pulse">
<i class="fas fa-exclamation-circle mr-1"></i>URGENT</span>
@endif
@if($isPast)
<span class="px-2 py-1 bg-orange-500/20 rounded-lg text-orange-400 text-xs font-semibold">
<i class="fas fa-clock mr-1"></i>PAST</span>
@endif
</div>
<div class="flex items-center space-x-4 text-sm text-gray-400 mb-3">
<span><i class="fas fa-calendar mr-1"></i>{{ $event->start_date->format('M d, Y') }}</span>
<span><i class="fas fa-clock mr-1"></i>{{ $event->start_date->format('h:i A') }}</span>
@if($event->location)
<span><i class="fas fa-map-marker-alt mr-1"></i>{{ $event->location }}</span>
@endif
</div>

@if($event->mediaAssignments->count() > 0)
<div class="flex flex-wrap gap-2 mb-3">
@foreach($event->mediaAssignments as $assignment)
<div class="px-3 py-1 bg-blue-500/20 rounded-lg text-blue-300 text-xs">
<i class="fas fa-user mr-1"></i>{{ $assignment->assignedUser->name ?? 'Unknown' }}
<span class="text-blue-400">• {{ $assignment->role }}</span>
@if($assignment->status == 'completed')
<i class="fas fa-check-circle text-green-400 ml-1"></i>
@endif
</div>
@endforeach
</div>
@else
<p class="text-gray-500 text-sm italic mb-3">
<i class="fas fa-exclamation-triangle mr-1"></i>No team assigned yet</p>
@endif
</div>

<div class="flex flex-col space-y-2">
<button onclick="assignTeam({{ $event->id }}, '{{ addslashes($event->title) }}')" class="px-4 py-2 bg-green-500/20 rounded-lg text-green-400 hover:bg-green-500/30 text-sm font-semibold whitespace-nowrap">
<i class="fas fa-users mr-1"></i>Assign</button>
@if($isPast)
<button onclick="uploadMedia({{ $event->id }}, '{{ addslashes($event->title) }}')" class="px-4 py-2 bg-purple-500/20 rounded-lg text-purple-400 hover:bg-purple-500/30 text-sm font-semibold whitespace-nowrap">
<i class="fas fa-upload mr-1"></i>Upload</button>
@endif
<button onclick="notifyTeam({{ $event->id }})" class="px-4 py-2 bg-blue-500/20 rounded-lg text-blue-400 hover:bg-blue-500/30 text-sm font-semibold whitespace-nowrap">
<i class="fas fa-bell mr-1"></i>Notify</button>
</div>
</div>
</div>
@endforeach
</div>
@else
<div class="text-center py-20">
<i class="fas fa-calendar-times text-6xl text-gray-500 mb-4"></i>
<h3 class="text-xl font-bold text-white mb-2">No Upcoming Events</h3>
<p class="text-gray-400">Check back later for new events</p>
</div>
@endif
</div>
</div>

<!-- My Assignments Sidebar -->
<div class="space-y-4" id="myAssignmentsSidebar">
<div class="stat-card">
<h3 class="text-lg font-bold text-white mb-4">
<i class="fas fa-tasks text-purple-400 mr-2"></i>My Assignments</h3>
@if($myAssignments->count() > 0)
<div class="space-y-3">
@foreach($myAssignments->take(5) as $assignment)
<div class="p-3 bg-white/5 rounded-lg">
<div class="flex items-start justify-between mb-2">
<div class="flex-1">
<p class="text-white font-semibold text-sm">{{ $assignment->event->title }}</p>
<p class="text-gray-400 text-xs">
<i class="fas fa-briefcase mr-1"></i>{{ $assignment->role }}</p>
<p class="text-gray-500 text-xs">
<i class="fas fa-calendar mr-1"></i>{{ $assignment->event->start_date->format('M d, h:i A') }}</p>
</div>
<span class="px-2 py-1 rounded-lg text-xs font-semibold {{ $assignment->status == 'completed' ? 'bg-green-500/20 text-green-400' : ($assignment->status == 'in_progress' ? 'bg-blue-500/20 text-blue-400' : 'bg-orange-500/20 text-orange-400') }}">
{{ ucfirst($assignment->status) }}
</span>
</div>
@if($assignment->status != 'completed')
<button onclick="uploadMedia({{ $assignment->event_id }}, '{{ addslashes($assignment->event->title) }}')" class="w-full px-3 py-2 bg-purple-500/20 rounded-lg text-purple-400 hover:bg-purple-500/30 text-xs font-semibold">
<i class="fas fa-upload mr-1"></i>Upload Media</button>
@endif
</div>
@endforeach
</div>
@else
<div class="text-center py-10">
<i class="fas fa-clipboard-list text-4xl text-gray-500 mb-3"></i>
<p class="text-gray-400 text-sm">No assignments yet</p>
</div>
@endif
</div>

<div class="stat-card">
<h3 class="text-lg font-bold text-white mb-4">
<i class="fas fa-chart-line text-green-400 mr-2"></i>Quick Stats</h3>
<div class="space-y-3">
<div class="flex justify-between items-center p-2 bg-white/5 rounded-lg">
<span class="text-gray-400 text-sm">Events This Week</span>
<span class="text-white font-bold">{{ $events->whereBetween('start_date', [now(), now()->addWeek()])->count() }}</span>
</div>
<div class="flex justify-between items-center p-2 bg-white/5 rounded-lg">
<span class="text-gray-400 text-sm">Needs Assignment</span>
<span class="text-orange-400 font-bold">{{ $events->filter(function($e){ return $e->mediaAssignments->count() == 0; })->count() }}</span>
</div>
<div class="flex justify-between items-center p-2 bg-white/5 rounded-lg">
<span class="text-gray-400 text-sm">Fully Staffed</span>
<span class="text-green-400 font-bold">{{ $events->filter(function($e){ return $e->mediaAssignments->count() >= 3; })->count() }}</span>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

<!-- Assign Team Modal -->
<div id="assignTeamModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
<div class="stat-card max-w-2xl w-full max-h-[90vh] overflow-y-auto">
<div class="flex justify-between items-center mb-6">
<h2 class="text-2xl font-bold text-white">
<i class="fas fa-users text-green-400 mr-2"></i>Assign Team to Event</h2>
<button onclick="closeAssignModal()" class="text-gray-400 hover:text-white">
<i class="fas fa-times text-xl"></i></button></div>
<p class="text-gray-400 mb-4">Event: <span id="assign_event_name" class="text-white font-semibold"></span></p>
<form id="assignTeamForm" class="space-y-4">
@csrf
<input type="hidden" id="assign_event_id" name="event_id">
<div class="grid grid-cols-2 gap-4">
<div>
<label class="block text-white font-semibold mb-2">Photographer</label>
<select name="photographer" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white">
<option value="">Not assigned</option>
@foreach(\App\Models\User::role('Media Team')->get() as $user)
@if($user->hasRole('Photographer'))
<option value="{{ $user->id }}">{{ $user->name }}</option>
@endif
@endforeach
</select></div>
<div>
<label class="block text-white font-semibold mb-2">Videographer</label>
<select name="videographer" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white">
<option value="">Not assigned</option>
@foreach(\App\Models\User::role('Media Team')->get() as $user)
@if($user->hasRole('Videographer'))
<option value="{{ $user->id }}">{{ $user->name }}</option>
@endif
@endforeach
</select></div>
<div>
<label class="block text-white font-semibold mb-2">Designer</label>
<select name="designer" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white">
<option value="">Not assigned</option>
@foreach(\App\Models\User::role('Media Team')->get() as $user)
@if($user->hasRole('Designer'))
<option value="{{ $user->id }}">{{ $user->name }}</option>
@endif
@endforeach
</select></div>
<div>
<label class="block text-white font-semibold mb-2">Editor</label>
<select name="editor" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white">
<option value="">Not assigned</option>
@foreach(\App\Models\User::role('Media Team')->get() as $user)
@if($user->hasRole('Editor'))
<option value="{{ $user->id }}">{{ $user->name }}</option>
@endif
@endforeach
</select></div></div>
<div>
<label class="block text-white font-semibold mb-2">Livestream Operator</label>
<select name="livestream" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white">
<option value="">Not assigned</option>
@foreach(\App\Models\User::role('Media Team')->get() as $user)
@if($user->hasRole('Livestream Operator'))
<option value="{{ $user->id }}">{{ $user->name }}</option>
@endif
@endforeach
</select></div>
<div>
<label class="block text-white font-semibold mb-2">Special Instructions</label>
<textarea name="notes" rows="3" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white" placeholder="Any special requirements..."></textarea></div>
<div class="p-3 bg-blue-500/10 border border-blue-500/30 rounded-lg">
<label class="flex items-center space-x-2 cursor-pointer">
<input type="checkbox" name="send_notification" checked class="w-4 h-4 rounded">
<span class="text-blue-300 text-sm">
<i class="fas fa-bell mr-1"></i>Send notifications to assigned team members</span></label></div>
<div class="flex space-x-3">
<button type="submit" class="flex-1 px-6 py-3 gradient-green rounded-xl text-white font-semibold">
<i class="fas fa-check mr-2"></i>Assign Team</button>
<button type="button" onclick="closeAssignModal()" class="px-6 py-3 bg-white/10 rounded-xl text-white">Cancel</button></div>
</form></div></div>

<!-- Upload Media Modal -->
<div id="uploadMediaModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
<div class="stat-card max-w-2xl w-full max-h-[90vh] overflow-y-auto">
<div class="flex justify-between items-center mb-6">
<h2 class="text-2xl font-bold text-white">
<i class="fas fa-upload text-purple-400 mr-2"></i>Upload Event Media</h2>
<button onclick="closeUploadModal()" class="text-gray-400 hover:text-white">
<i class="fas fa-times text-xl"></i></button></div>
<p class="text-gray-400 mb-4">Event: <span id="upload_event_name" class="text-white font-semibold"></span></p>
<form id="uploadMediaForm" class="space-y-4">
@csrf
<input type="hidden" id="upload_event_id" name="linked_event_id">
<div>
<label class="block text-white font-semibold mb-2">Media Type *</label>
<select name="media_type" required class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white">
<option value="">Select type...</option>
<option value="photo">📷 Photos</option>
<option value="video">🎥 Videos</option>
<option value="livestream">📡 Livestream Recording</option>
<option value="graphic">🎨 Graphics/Designs</option>
<option value="mixed">📦 Mixed Media Package</option></select></div>
<div>
<label class="block text-white font-semibold mb-2">Upload Files *</label>
<div class="border-2 border-dashed border-white/20 rounded-xl p-6 text-center hover:border-white/40 transition-colors cursor-pointer" onclick="document.getElementById('media_files').click()">
<i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
<p class="text-white font-semibold">Click to upload files</p>
<p class="text-gray-400 text-sm">Supports: JPG, PNG, MP4, MOV (Max: 500MB)</p>
<input type="file" id="media_files" name="files[]" multiple accept="image/*,video/*" class="hidden"></div>
<div id="file_preview" class="mt-3 space-y-2"></div></div>
<div>
<label class="block text-white font-semibold mb-2">Description</label>
<textarea name="description" rows="3" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white" placeholder="Describe the media..."></textarea></div>
<div class="p-3 bg-purple-500/10 border border-purple-500/30 rounded-lg">
<p class="text-purple-300 text-sm">
<i class="fas fa-tag mr-2"></i>Media will be tagged with event ID: <span class="font-mono text-white" id="event_tag_id"></span></p></div>
<div class="flex space-x-3">
<button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl text-white font-semibold">
<i class="fas fa-upload mr-2"></i>Upload Media</button>
<button type="button" onclick="closeUploadModal()" class="px-6 py-3 bg-white/10 rounded-xl text-white">Cancel</button></div>
</form></div></div>

@push('scripts')
<script>
// Filter Events
function filterEvents(filter){
    const events = document.querySelectorAll('.event-item');
    events.forEach(event => {
        const status = event.dataset.status;
        const isUrgent = event.dataset.urgent === 'true';
        const isPast = event.dataset.past === 'true';
        
        if(filter === 'all'){
            event.style.display = 'block';
        } else if(filter === 'assigned'){
            event.style.display = status === 'assigned' ? 'block' : 'none';
        } else if(filter === 'unassigned'){
            event.style.display = status === 'unassigned' && !isPast ? 'block' : 'none';
        } else if(filter === 'urgent'){
            event.style.display = isUrgent ? 'block' : 'none';
        }
    });
}

// Assign Team Modal
function assignTeam(eventId, eventName){
    document.getElementById('assign_event_id').value = eventId;
    document.getElementById('assign_event_name').textContent = eventName;
    document.getElementById('assignTeamModal').classList.remove('hidden');
}

function openQuickAssign(){
    alert('⚡ Quick Assign\n\nPlease select an event from the list, then click the "Assign" button.');
}

function closeAssignModal(){
    document.getElementById('assignTeamModal').classList.add('hidden');
}

// Upload Media Modal
function uploadMedia(eventId, eventName){
    document.getElementById('upload_event_id').value = eventId;
    document.getElementById('upload_event_name').textContent = eventName;
    document.getElementById('event_tag_id').textContent = eventId;
    document.getElementById('uploadMediaModal').classList.remove('hidden');
}

function closeUploadModal(){
    document.getElementById('uploadMediaModal').classList.add('hidden');
}

// Scroll to My Assignments
function scrollToMyAssignments(){
    document.getElementById('myAssignmentsSidebar').scrollIntoView({ behavior: 'smooth' });
}

// Notify Team
function notifyTeam(eventId){
    if(confirm('Send notification to all assigned team members for this event?')){
        fetch(`{{ url('media/schedule/notify') }}/${eventId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => response.json())
        .then(data => {
            if(data.success){
                alert('✅ Notifications sent successfully!\n\n' + data.message);
            } else {
                alert('❌ Failed:\n\n' + data.message);
            }
        }).catch(error => {
            console.error('Error:', error);
            alert('❌ Error sending notifications.');
        });
    }
}

// File Preview
document.addEventListener('DOMContentLoaded', function(){
    const fileInput = document.getElementById('media_files');
    if(fileInput){
        fileInput.addEventListener('change', function(){
            const preview = document.getElementById('file_preview');
            preview.innerHTML = '';
            
            Array.from(this.files).forEach(file => {
                const div = document.createElement('div');
                div.className = 'flex items-center justify-between p-2 bg-white/5 rounded-lg';
                div.innerHTML = `
                    <div class="flex items-center space-x-2">
                        <i class="fas ${file.type.startsWith('image/') ? 'fa-image' : 'fa-video'} text-purple-400"></i>
                        <span class="text-white text-sm">${file.name}</span>
                        <span class="text-gray-400 text-xs">(${(file.size / 1024 / 1024).toFixed(2)} MB)</span>
                    </div>
                    <i class="fas fa-check-circle text-green-400"></i>
                `;
                preview.appendChild(div);
            });
        });
    }
    
    // Assign Team Form
    const assignForm = document.getElementById('assignTeamForm');
    if(assignForm){
        assignForm.addEventListener('submit', async function(e){
            e.preventDefault();
            const formData = new FormData(this);
            
            try {
                const response = await fetch('{{ route("media.schedule.assign") }}', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                
                const data = await response.json();
                
                if(response.ok && data.success){
                    alert('✅ Team assigned successfully!\n\n' + data.message + (data.notifications_sent ? '\n\n📧 Notifications sent.' : ''));
                    window.location.reload();
                } else {
                    alert('❌ Failed:\n\n' + (data.message || 'Unknown error'));
                }
            } catch(error){
                console.error('Error:', error);
                alert('❌ Error assigning team.');
            }
        });
    }
    
    // Upload Media Form
    const uploadForm = document.getElementById('uploadMediaForm');
    if(uploadForm){
        uploadForm.addEventListener('submit', async function(e){
            e.preventDefault();
            const formData = new FormData(this);
            
            if(!document.getElementById('media_files').files.length){
                alert('⚠️ Please select files to upload');
                return;
            }
            
            try {
                const response = await fetch('{{ route("media.schedule.upload") }}', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                
                const data = await response.json();
                
                if(response.ok && data.success){
                    alert('✅ Media uploaded successfully!\n\nFiles: ' + data.files_count + '\nEvent ID: ' + data.event_id);
                    window.location.reload();
                } else {
                    alert('❌ Upload failed:\n\n' + (data.message || 'Unknown error'));
                }
            } catch(error){
                console.error('Error:', error);
                alert('❌ Error uploading media.');
            }
        });
    }
});
</script>
@endpush
@endsection
