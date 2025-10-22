@extends('layouts.media')
@section('title', 'Announcements & Graphics')
@section('content')
<div class="space-y-6">
<div class="flex justify-between items-center">
<div><h1 class="text-4xl font-black text-white"><i class="fas fa-bullhorn text-green-400 mr-3"></i>Announcements & Graphics</h1>
<p class="text-gray-400 mt-2">Design, schedule & publish church announcements</p></div>
<button onclick="openCreateModal()" class="px-6 py-3 gradient-green rounded-xl text-white font-semibold hover:shadow-lg">
<i class="fas fa-plus mr-2"></i>Create Announcement</button></div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4">
<div class="stat-card"><div class="flex items-center justify-between mb-2">
<span class="text-gray-400 text-sm">Total Posts</span><i class="fas fa-bullhorn text-green-400"></i></div>
<p class="text-3xl font-bold text-white">{{ $announcements->count() }}</p></div>
<div class="stat-card"><div class="flex items-center justify-between mb-2">
<span class="text-gray-400 text-sm">Scheduled</span><i class="fas fa-clock text-blue-400"></i></div>
<p class="text-3xl font-bold text-white">{{ $announcements->where('status', 'scheduled')->count() }}</p></div>
<div class="stat-card"><div class="flex items-center justify-between mb-2">
<span class="text-gray-400 text-sm">Published</span><i class="fas fa-check-circle text-purple-400"></i></div>
<p class="text-3xl font-bold text-white">{{ $announcements->where('status', 'published')->count() }}</p></div>
<div class="stat-card"><div class="flex items-center justify-between mb-2">
<span class="text-gray-400 text-sm">Total Reach</span><i class="fas fa-users text-orange-400"></i></div>
<p class="text-3xl font-bold text-white">0</p></div></div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
<div class="lg:col-span-2 space-y-4">
<div class="stat-card"><div class="flex justify-between items-center mb-4">
<h2 class="text-xl font-bold text-white">Your Announcements</h2>
<div class="flex space-x-2"><select class="px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white text-sm" onchange="filterStatus(this.value)">
<option value="all">All Status</option><option value="draft">Draft</option><option value="scheduled">Scheduled</option>
<option value="published">Published</option></select></div></div>
@if($announcements->count() > 0)
<div class="space-y-3" id="announcementsList">
@foreach($announcements as $announcement)
<div class="p-4 bg-white/5 rounded-xl hover:bg-white/10 transition-all announcement-item" data-status="{{ $announcement->status }}">
<div class="flex justify-between items-start">
<div class="flex-1"><h3 class="text-white font-bold text-lg mb-2">{{ $announcement->title }}</h3>
<p class="text-gray-400 text-sm mb-3 line-clamp-2">{{ $announcement->content }}</p>
<div class="flex flex-wrap gap-2 mb-3">
@if($announcement->platforms)
@foreach(json_decode($announcement->platforms) as $platform)
<span class="px-2 py-1 rounded text-xs font-semibold
@if($platform == 'facebook') bg-blue-500/20 text-blue-400
@elseif($platform == 'instagram') bg-pink-500/20 text-pink-400
@elseif($platform == 'whatsapp') bg-green-500/20 text-green-400
@elseif($platform == 'website') bg-purple-500/20 text-purple-400
@endif"><i class="fab fa-{{ $platform }} mr-1"></i>{{ ucfirst($platform) }}</span>
@endforeach
@endif
</div>
<div class="flex items-center space-x-4 text-sm text-gray-500">
<span><i class="fas fa-eye mr-1"></i>0 views</span>
<span><i class="fas fa-mouse-pointer mr-1"></i>0 clicks</span>
<span><i class="fas fa-share mr-1"></i>0 shares</span>
<span class="text-gray-400">{{ $announcement->created_at->diffForHumans() }}</span></div></div>
<div class="flex flex-col items-end space-y-2">
<span class="px-3 py-1 rounded-lg text-xs font-semibold
@if($announcement->status == 'published') bg-green-500/20 text-green-400
@elseif($announcement->status == 'scheduled') bg-blue-500/20 text-blue-400
@else bg-gray-500/20 text-gray-400
@endif"><i class="fas fa-circle text-xs mr-1"></i>{{ ucfirst($announcement->status) }}</span>
<div class="flex space-x-2">
<button onclick="editAnnouncement({{ $announcement->id }})" class="px-3 py-2 bg-blue-500/20 rounded-lg text-blue-400 hover:bg-blue-500/30">
<i class="fas fa-edit"></i></button>
<button onclick="viewEngagement({{ $announcement->id }})" class="px-3 py-2 bg-purple-500/20 rounded-lg text-purple-400 hover:bg-purple-500/30">
<i class="fas fa-chart-line"></i></button></div></div></div></div>
@endforeach
</div>
{{ $announcements->links() }}
@else
<div class="text-center py-20"><i class="fas fa-bullhorn text-6xl text-gray-500 mb-4"></i>
<h3 class="text-xl font-bold text-white mb-2">No Announcements Yet</h3>
<p class="text-gray-400 mb-4">Create your first announcement to get started</p>
<button onclick="openCreateModal()" class="px-6 py-3 gradient-green rounded-xl text-white font-semibold">
<i class="fas fa-plus mr-2"></i>Create Now</button></div>
@endif
</div></div>

<div class="space-y-4">
<div class="stat-card"><h3 class="text-lg font-bold text-white mb-4"><i class="fas fa-paint-brush text-pink-400 mr-2"></i>Quick Templates</h3>
<div class="space-y-2"><button onclick="useTemplate('sunday-service')" class="w-full p-3 bg-gradient-to-r from-blue-500/20 to-purple-500/20 rounded-lg text-left hover:scale-105 transition-all">
<p class="text-white font-semibold text-sm">📅 Sunday Service</p><p class="text-gray-400 text-xs">Weekly service announcement</p></button>
<button onclick="useTemplate('event')" class="w-full p-3 bg-gradient-to-r from-green-500/20 to-blue-500/20 rounded-lg text-left hover:scale-105 transition-all">
<p class="text-white font-semibold text-sm">🎉 Special Event</p><p class="text-gray-400 text-xs">Event invitation</p></button>
<button onclick="useTemplate('bible-verse')" class="w-full p-3 bg-gradient-to-r from-purple-500/20 to-pink-500/20 rounded-lg text-left hover:scale-105 transition-all">
<p class="text-white font-semibold text-sm">📖 Daily Verse</p><p class="text-gray-400 text-xs">Inspirational quote</p></button>
<button onclick="useTemplate('prayer')" class="w-full p-3 bg-gradient-to-r from-orange-500/20 to-red-500/20 rounded-lg text-left hover:scale-105 transition-all">
<p class="text-white font-semibold text-sm">🙏 Prayer Request</p><p class="text-gray-400 text-xs">Community prayer</p></button></div></div>

<div class="stat-card"><h3 class="text-lg font-bold text-white mb-4"><i class="fas fa-robot text-purple-400 mr-2"></i>AI Copywriter</h3>
<p class="text-gray-400 text-sm mb-3">Generate engaging content</p>
<button onclick="openAICopywriter()" class="w-full px-4 py-2 bg-purple-500/20 rounded-lg text-purple-400 hover:bg-purple-500/30 font-semibold">
<i class="fas fa-magic mr-2"></i>Generate Caption</button></div>

<div class="stat-card"><h3 class="text-lg font-bold text-white mb-4"><i class="fas fa-chart-bar text-orange-400 mr-2"></i>Top Performing</h3>
<div class="space-y-2"><div class="p-2 text-sm"><p class="text-white">Youth Rally Promo</p>
<p class="text-gray-500 text-xs">1,234 impressions</p></div>
<div class="p-2 text-sm"><p class="text-white">Sunday Sermon</p>
<p class="text-gray-500 text-xs">987 impressions</p></div></div></div>
</div></div>
</div>

<div id="createModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
<div class="stat-card max-w-4xl w-full max-h-[90vh] overflow-y-auto">
<div class="flex justify-between items-center mb-6">
<h2 class="text-2xl font-bold text-white"><i class="fas fa-plus text-green-400 mr-2"></i>Create Announcement</h2>
<button onclick="closeCreateModal()" class="text-gray-400 hover:text-white"><i class="fas fa-times text-xl"></i></button></div>
<form id="createForm" class="space-y-4">
@csrf
<div><label class="block text-white font-semibold mb-2">Title *</label>
<input type="text" name="title" required class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white" placeholder="e.g., Youth Rally This Saturday!"></div>
<div><label class="block text-white font-semibold mb-2">Content *</label>
<textarea name="content" rows="4" required class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white" placeholder="Write your announcement message..."></textarea>
<button type="button" onclick="generateAIContent()" class="mt-2 text-purple-400 hover:text-purple-300 text-sm">
<i class="fas fa-magic mr-1"></i>Generate with AI</button></div>
<div><label class="block text-white font-semibold mb-2">Select Platforms *</label>
<div class="grid grid-cols-2 gap-3">
<label class="flex items-center p-3 bg-white/5 rounded-lg hover:bg-white/10 cursor-pointer">
<input type="checkbox" name="platforms[]" value="facebook" class="mr-3"><i class="fab fa-facebook text-2xl text-blue-500 mr-2"></i>
<span class="text-white">Facebook</span></label>
<label class="flex items-center p-3 bg-white/5 rounded-lg hover:bg-white/10 cursor-pointer">
<input type="checkbox" name="platforms[]" value="instagram" class="mr-3"><i class="fab fa-instagram text-2xl text-pink-500 mr-2"></i>
<span class="text-white">Instagram</span></label>
<label class="flex items-center p-3 bg-white/5 rounded-lg hover:bg-white/10 cursor-pointer">
<input type="checkbox" name="platforms[]" value="whatsapp" class="mr-3"><i class="fab fa-whatsapp text-2xl text-green-500 mr-2"></i>
<span class="text-white">WhatsApp</span></label>
<label class="flex items-center p-3 bg-white/5 rounded-lg hover:bg-white/10 cursor-pointer">
<input type="checkbox" name="platforms[]" value="website" class="mr-3"><i class="fas fa-globe text-2xl text-purple-500 mr-2"></i>
<span class="text-white">Website</span></label></div></div>
<div class="grid grid-cols-2 gap-4">
<div><label class="block text-white font-semibold mb-2">Schedule Date & Time</label>
<input type="datetime-local" name="scheduled_at" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white"></div>
<div><label class="block text-white font-semibold mb-2">Status</label>
<select name="status" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white">
<option value="draft">Save as Draft</option><option value="scheduled">Schedule</option><option value="published">Publish Now</option></select></div></div>
<div class="flex space-x-3">
<button type="submit" class="flex-1 px-6 py-3 gradient-green rounded-xl text-white font-semibold">
<i class="fas fa-paper-plane mr-2"></i>Create Announcement</button>
<button type="button" onclick="closeCreateModal()" class="px-6 py-3 bg-white/10 rounded-xl text-white">Cancel</button></div>
</form></div></div>

@push('scripts')
<script>
function openCreateModal(){document.getElementById('createModal').classList.remove('hidden');}
function closeCreateModal(){document.getElementById('createModal').classList.add('hidden');document.getElementById('createForm').reset();}
function filterStatus(status){
const items = document.querySelectorAll('.announcement-item');
items.forEach(item => {
if(status === 'all' || item.dataset.status === status) item.style.display = 'block';
else item.style.display = 'none';
});
}
function useTemplate(type){
openCreateModal();
const templates = {
'sunday-service': {title: 'Join Us This Sunday!', content: '📅 Sunday Service\n⏰ 9:00 AM\n📍 Main Sanctuary\n\nCome worship with us this weekend!'},
'event': {title: 'Special Event Coming Up!', content: '🎉 You\'re Invited!\n📅 Date: TBA\n⏰ Time: TBA\n📍 Location: TBA\n\nDon\'t miss out!'},
'bible-verse': {title: 'Verse of the Day', content: '"For God so loved the world..." - John 3:16\n\n🙏 Share this with someone today!'},
'prayer': {title: 'Prayer Request', content: '🙏 We\'re praying for our community.\n\nShare your prayer requests in the comments.'}
};
if(templates[type]){
document.querySelector('[name="title"]').value = templates[type].title;
document.querySelector('[name="content"]').value = templates[type].content;
}
}
function generateAIContent(){
const title = document.querySelector('[name="title"]').value;
if(!title){alert('Please enter a title first'); return;}
document.querySelector('[name="content"]').value = `✨ AI Generated Content for "${title}"\n\nJoin us for this amazing event! This is an exciting opportunity to come together as a community.\n\n📅 Don't miss out!\n💬 Share with friends\n🙏 Bring your family\n\n#ChurchCommunity #FaithAndFellowship`;
}
function openAICopywriter(){
alert('AI Copywriter\n\nThis feature uses OpenAI to generate:\n• Engaging captions\n• Event descriptions\n• Social media posts\n• Flyer text\n\nComing soon with full API integration!');
}
function editAnnouncement(id){alert('Edit announcement ID: ' + id + '\n\nThis will open an edit form.');}
function viewEngagement(id){alert('Engagement Stats for Announcement #' + id + '\n\n📊 Impressions: 1,234\n🖱️ Clicks: 56 (4.5%)\n📤 Shares: 23\n👥 Reach: 1,100\n💬 Comments: 12\n❤️ Likes: 89\n📈 Engagement Rate: 7.8%');}

// Form submission
document.getElementById('createForm').addEventListener('submit', async function(e){
    e.preventDefault();
    const formData = new FormData(this);
    
    // Get selected platforms
    const platforms = [];
    document.querySelectorAll('[name="platforms[]"]:checked').forEach(cb => platforms.push(cb.value));
    formData.delete('platforms[]');
    formData.append('platforms', JSON.stringify(platforms));
    
    try {
        const response = await fetch('{{ route("media.announcements.create") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        const data = await response.json();
        if(data.success){
            alert('✅ Announcement created successfully!');
            window.location.reload();
        } else {
            alert('❌ Failed to create announcement: ' + (data.message || 'Unknown error'));
        }
    } catch(error){
        console.error('Error:', error);
        alert('❌ Error creating announcement. Please try again.');
    }
});
</script>
@endpush
@endsection
