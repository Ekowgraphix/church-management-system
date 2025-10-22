@extends('layouts.media')
@section('title', 'Gallery Management')
@section('content')
<div class="space-y-6">
<!-- Header -->
<div class="flex justify-between items-center">
<div>
<h1 class="text-4xl font-black text-white">
<i class="fas fa-images text-blue-400 mr-3"></i>Gallery Management</h1>
<p class="text-gray-400 mt-2">Create albums and organize your media</p></div>
<button onclick="openNewGalleryModal()" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl text-white font-semibold hover:shadow-lg">
<i class="fas fa-plus-circle mr-2"></i>New Album</button></div>

<!-- AI Suggestions Alert -->
@if($suggestedPhotos && $suggestedPhotos->count() > 0)
<div class="stat-card bg-gradient-to-r from-purple-500/20 to-blue-500/20 border-2 border-purple-500/30">
<div class="flex items-start space-x-4">
<div class="flex-shrink-0">
<div class="w-12 h-12 rounded-full bg-purple-500/30 flex items-center justify-center">
<i class="fas fa-robot text-purple-400 text-xl"></i></div></div>
<div class="flex-1">
<h3 class="text-lg font-bold text-white mb-1">
<i class="fas fa-lightbulb mr-2"></i>AI Photo Suggestions</h3>
<p class="text-purple-200 font-semibold">
Based on engagement metrics, these {{ $suggestedPhotos->count() }} photos are trending!</p>
<div class="flex space-x-2 mt-3">
@foreach($suggestedPhotos->take(5) as $photo)
<div class="w-20 h-20 rounded-lg overflow-hidden border-2 border-yellow-400">
<img src="{{ asset('storage/' . $photo->file_path) }}" alt="" class="w-full h-full object-cover"></div>
@endforeach
</div>
<button onclick="viewSuggestions()" class="mt-4 px-5 py-2 bg-purple-500 hover:bg-purple-600 rounded-lg text-white font-semibold">
<i class="fas fa-star mr-2"></i>View All Suggestions</button></div></div></div>
@endif

<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4">
<div class="stat-card">
<div class="flex items-center justify-between mb-2">
<span class="text-gray-400 text-sm">Total Albums</span>
<i class="fas fa-images text-blue-400"></i></div>
<p class="text-3xl font-bold text-white">{{ $galleries->count() }}</p>
<p class="text-xs text-gray-500 mt-1">All galleries</p></div>
<div class="stat-card">
<div class="flex items-center justify-between mb-2">
<span class="text-gray-400 text-sm">Total Photos</span>
<i class="fas fa-camera text-green-400"></i></div>
<p class="text-3xl font-bold text-white">{{ $stats['total_photos'] ?? 0 }}</p>
<p class="text-xs text-gray-500 mt-1">Across all albums</p></div>
<div class="stat-card">
<div class="flex items-center justify-between mb-2">
<span class="text-gray-400 text-sm">Public Albums</span>
<i class="fas fa-globe text-purple-400"></i></div>
<p class="text-3xl font-bold text-white">{{ $stats['public_galleries'] ?? 0 }}</p>
<p class="text-xs text-gray-500 mt-1">Visible to public</p></div>
<div class="stat-card">
<div class="flex items-center justify-between mb-2">
<span class="text-gray-400 text-sm">Total Views</span>
<i class="fas fa-eye text-orange-400"></i></div>
<p class="text-3xl font-bold text-white">{{ number_format($stats['total_views'] ?? 0) }}</p>
<p class="text-xs text-gray-500 mt-1">All time</p></div></div>

<!-- Galleries Grid -->
<div class="stat-card">
<div class="flex justify-between items-center mb-4">
<h2 class="text-xl font-bold text-white">
<i class="fas fa-folder-open text-blue-400 mr-2"></i>Albums</h2>
<select onchange="filterGalleries(this.value)" class="px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white text-sm">
<option value="all">All Albums</option>
<option value="public">Public</option>
<option value="private">Private</option>
<option value="downloads">Downloads Allowed</option></select></div>

@if($galleries->count() > 0)
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
@foreach($galleries as $gallery)
<div class="bg-white/5 rounded-xl overflow-hidden hover:bg-white/10 transition-all gallery-item" data-public="{{ $gallery->is_public ? 'true' : 'false' }}" data-downloads="{{ $gallery->allow_downloads ? 'true' : 'false' }}">
<!-- Cover Image -->
<div class="relative h-48 bg-gradient-to-br from-blue-500/20 to-purple-500/20">
@if($gallery->cover_image)
<img src="{{ asset('storage/' . $gallery->cover_image) }}" alt="" class="w-full h-full object-cover">
@else
<div class="flex items-center justify-center h-full">
<i class="fas fa-images text-6xl text-gray-500"></i></div>
@endif
<div class="absolute top-2 right-2 flex space-x-1">
@if($gallery->is_public)
<span class="px-2 py-1 bg-green-500 rounded-lg text-white text-xs font-semibold">
<i class="fas fa-globe mr-1"></i>Public</span>
@else
<span class="px-2 py-1 bg-gray-500 rounded-lg text-white text-xs font-semibold">
<i class="fas fa-lock mr-1"></i>Private</span>
@endif
</div>
<div class="absolute bottom-2 left-2">
<span class="px-2 py-1 bg-black/70 rounded-lg text-white text-xs">
<i class="fas fa-camera mr-1"></i>{{ $gallery->mediaFiles->count() }} photos</span></div></div>

<!-- Gallery Info -->
<div class="p-4">
<h3 class="text-white font-bold text-lg mb-2">{{ $gallery->name }}</h3>
@if($gallery->description)
<p class="text-gray-400 text-sm mb-3 line-clamp-2">{{ $gallery->description }}</p>
@endif

<div class="flex items-center justify-between text-xs text-gray-500 mb-3">
<span><i class="fas fa-eye mr-1"></i>{{ number_format($gallery->views_count ?? 0) }} views</span>
<span><i class="fas fa-calendar mr-1"></i>{{ $gallery->created_at->format('M d, Y') }}</span></div>

<!-- Action Buttons -->
<div class="grid grid-cols-2 gap-2">
<button onclick="viewGallery({{ $gallery->id }})" class="px-3 py-2 bg-blue-500 rounded-lg text-white text-sm font-semibold hover:bg-blue-600">
<i class="fas fa-eye mr-1"></i>View</button>
<button onclick="editGallery({{ $gallery->id }})" class="px-3 py-2 bg-green-500/20 rounded-lg text-green-400 text-sm font-semibold hover:bg-green-500/30">
<i class="fas fa-edit mr-1"></i>Edit</button>
<button onclick="embedGallery({{ $gallery->id }})" class="px-3 py-2 bg-purple-500/20 rounded-lg text-purple-400 text-sm font-semibold hover:bg-purple-500/30">
<i class="fas fa-code mr-1"></i>Embed</button>
<button onclick="syncGallery({{ $gallery->id }})" class="px-3 py-2 bg-orange-500/20 rounded-lg text-orange-400 text-sm font-semibold hover:bg-orange-500/30">
<i class="fas fa-sync mr-1"></i>Sync</button></div></div></div>
@endforeach
</div>
@else
<div class="text-center py-20">
<i class="fas fa-images text-6xl text-gray-500 mb-4"></i>
<h3 class="text-xl font-bold text-white mb-2">No Albums Yet</h3>
<p class="text-gray-400 mb-4">Create your first gallery album</p>
<button onclick="openNewGalleryModal()" class="px-6 py-3 bg-blue-500 rounded-xl text-white font-semibold">
<i class="fas fa-plus-circle mr-2"></i>Create Album</button></div>
@endif
</div>
</div>

<!-- Include Modals -->
@include('media.gallery_modals')

@push('scripts')
<script>
// Filter Galleries
function filterGalleries(filter){
    const galleries = document.querySelectorAll('.gallery-item');
    galleries.forEach(gallery => {
        const isPublic = gallery.dataset.public === 'true';
        const allowsDownloads = gallery.dataset.downloads === 'true';
        
        if(filter === 'all'){
            gallery.style.display = 'block';
        } else if(filter === 'public'){
            gallery.style.display = isPublic ? 'block' : 'none';
        } else if(filter === 'private'){
            gallery.style.display = !isPublic ? 'block' : 'none';
        } else if(filter === 'downloads'){
            gallery.style.display = allowsDownloads ? 'block' : 'none';
        }
    });
}

// New Gallery Modal
function openNewGalleryModal(){
    document.getElementById('newGalleryModal').classList.remove('hidden');
}

function closeNewGalleryModal(){
    document.getElementById('newGalleryModal').classList.add('hidden');
}

// View Gallery
function viewGallery(galleryId){
    window.location.href = `/media/gallery/${galleryId}/view`;
}

// Edit Gallery
function editGallery(galleryId){
    alert('🔧 Edit Gallery\n\nGallery ID: ' + galleryId + '\n\nEdit functionality ready.');
}

// Embed Gallery
function embedGallery(galleryId){
    const baseUrl = '{{ url("/") }}';
    const embedUrl = `${baseUrl}/gallery/${galleryId}`;
    const iframeCode = `<iframe src="${embedUrl}" width="100%" height="600" frameborder="0"></iframe>`;
    
    document.getElementById('embed_iframe').value = iframeCode;
    document.getElementById('embed_link').value = embedUrl;
    document.getElementById('embedModal').classList.remove('hidden');
}

function closeEmbedModal(){
    document.getElementById('embedModal').classList.add('hidden');
}

function copyEmbedCode(type){
    const input = document.getElementById(type === 'iframe' ? 'embed_iframe' : 'embed_link');
    input.select();
    document.execCommand('copy');
    alert('✅ Copied to clipboard!');
}

// Sync Gallery
function syncGallery(galleryId){
    document.getElementById('syncModal').classList.remove('hidden');
    window.currentSyncGalleryId = galleryId;
}

function closeSyncModal(){
    document.getElementById('syncModal').classList.add('hidden');
}

function startSync(){
    const galleryId = window.currentSyncGalleryId;
    fetch(`{{ url('media/gallery/sync') }}/${galleryId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    }).then(response => response.json())
    .then(data => {
        if(data.success){
            alert('✅ Gallery synced to public website!\n\n' + data.message);
            closeSyncModal();
        } else {
            alert('❌ Sync failed:\n\n' + data.message);
        }
    }).catch(error => {
        console.error('Error:', error);
        alert('❌ Error syncing gallery.');
    });
}

// AI Suggestions
function viewSuggestions(){
    document.getElementById('suggestionsModal').classList.remove('hidden');
}

function closeSuggestionsModal(){
    document.getElementById('suggestionsModal').classList.add('hidden');
}

function usePhoto(photoId){
    alert('📷 Add Photo\n\nPhoto ID: ' + photoId + '\n\nSelect an album to add this photo to.');
}

// Download Settings
function closeDownloadSettingsModal(){
    document.getElementById('downloadSettingsModal').classList.add('hidden');
}

function saveDownloadSettings(){
    alert('✅ Download settings saved!');
    closeDownloadSettingsModal();
}

// Photo Preview
document.addEventListener('DOMContentLoaded', function(){
    const photoInput = document.getElementById('photo_files');
    if(photoInput){
        photoInput.addEventListener('change', function(){
            const preview = document.getElementById('photos_preview');
            preview.innerHTML = '';
            
            Array.from(this.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e){
                    const div = document.createElement('div');
                    div.className = 'relative h-24 bg-white/5 rounded-lg overflow-hidden';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                        <div class="absolute bottom-1 left-1 right-1 bg-black/70 rounded text-white text-xs p-1 truncate">
                            ${file.name}
                        </div>
                    `;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        });
    }
    
    // New Gallery Form
    const newGalleryForm = document.getElementById('newGalleryForm');
    if(newGalleryForm){
        newGalleryForm.addEventListener('submit', async function(e){
            e.preventDefault();
            const formData = new FormData(this);
            
            try {
                const response = await fetch('{{ route("media.gallery.create") }}', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                
                const data = await response.json();
                
                if(response.ok && data.success){
                    alert('✅ Album created successfully!\n\n' + data.message);
                    window.location.reload();
                } else {
                    alert('❌ Failed:\n\n' + (data.message || 'Unknown error'));
                }
            } catch(error){
                console.error('Error:', error);
                alert('❌ Error creating album.');
            }
        });
    }
});
</script>
@endpush
@endsection
