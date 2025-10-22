<!-- New Gallery Modal -->
<div id="newGalleryModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
<div class="stat-card max-w-2xl w-full max-h-[90vh] overflow-y-auto">
<div class="flex justify-between items-center mb-6">
<h2 class="text-2xl font-bold text-white">
<i class="fas fa-plus-circle text-blue-400 mr-2"></i>Create New Album</h2>
<button onclick="closeNewGalleryModal()" class="text-gray-400 hover:text-white">
<i class="fas fa-times text-xl"></i></button></div>

<form id="newGalleryForm" class="space-y-4">
@csrf
<div>
<label class="block text-white font-semibold mb-2">Album Name *</label>
<input type="text" name="name" required class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white" placeholder="Easter 2025, Youth Camp, Convention Highlights"></div>

<div>
<label class="block text-white font-semibold mb-2">Description</label>
<textarea name="description" rows="3" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white" placeholder="Describe this photo album..."></textarea></div>

<div class="grid grid-cols-2 gap-4">
<div>
<label class="block text-white font-semibold mb-2">Cover Image</label>
<input type="file" name="cover_image" accept="image/*" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white"></div>

<div>
<label class="block text-white font-semibold mb-2">Event (Optional)</label>
<select name="event_id" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white">
<option value="">Not linked to event</option>
@foreach(\App\Models\Event::latest()->take(20)->get() as $event)
<option value="{{ $event->id }}">{{ $event->title }}</option>
@endforeach
</select></div></div>

<div class="p-3 bg-blue-500/10 border border-blue-500/30 rounded-lg">
<h4 class="text-white font-semibold mb-3">Permissions & Visibility</h4>
<label class="flex items-center space-x-2 cursor-pointer mb-2">
<input type="checkbox" name="is_public" value="1" checked class="w-4 h-4 rounded">
<span class="text-blue-300 text-sm">
<i class="fas fa-globe mr-1"></i>Make album public (visible to website visitors)</span></label>
<label class="flex items-center space-x-2 cursor-pointer">
<input type="checkbox" name="allow_downloads" value="1" class="w-4 h-4 rounded">
<span class="text-blue-300 text-sm">
<i class="fas fa-download mr-1"></i>Allow downloads (Admin/Leader only)</span></label></div>

<div class="flex space-x-3">
<button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl text-white font-semibold">
<i class="fas fa-check mr-2"></i>Create Album</button>
<button type="button" onclick="closeNewGalleryModal()" class="px-6 py-3 bg-white/10 rounded-xl text-white">Cancel</button></div>
</form></div></div>

<!-- Add Photos to Gallery Modal -->
<div id="addPhotosModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
<div class="stat-card max-w-4xl w-full max-h-[90vh] overflow-y-auto">
<div class="flex justify-between items-center mb-6">
<h2 class="text-2xl font-bold text-white">
<i class="fas fa-images text-green-400 mr-2"></i>Add Photos to Album</h2>
<button onclick="closeAddPhotosModal()" class="text-gray-400 hover:text-white">
<i class="fas fa-times text-xl"></i></button></div>
<p class="text-gray-400 mb-4">Album: <span id="add_photos_gallery_name" class="text-white font-semibold"></span></p>

<form id="addPhotosForm" class="space-y-4">
@csrf
<input type="hidden" id="add_photos_gallery_id" name="gallery_id">

<div>
<label class="block text-white font-semibold mb-2">Upload Photos *</label>
<div class="border-2 border-dashed border-white/20 rounded-xl p-6 text-center hover:border-white/40 transition-colors cursor-pointer" onclick="document.getElementById('photo_files').click()">
<i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
<p class="text-white font-semibold">Click to upload photos</p>
<p class="text-gray-400 text-sm">JPG, PNG (Multiple files supported)</p>
<input type="file" id="photo_files" name="photos[]" multiple accept="image/*" class="hidden"></div>
<div id="photos_preview" class="mt-3 grid grid-cols-4 gap-2"></div></div>

<div>
<label class="block text-white font-semibold mb-2">Default Photographer Credit</label>
<input type="text" name="photographer" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white" placeholder="John Doe Photography"></div>

<div class="flex space-x-3">
<button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 rounded-xl text-white font-semibold">
<i class="fas fa-upload mr-2"></i>Upload Photos</button>
<button type="button" onclick="closeAddPhotosModal()" class="px-6 py-3 bg-white/10 rounded-xl text-white">Cancel</button></div>
</form></div></div>

<!-- Embed Gallery Modal -->
<div id="embedModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
<div class="stat-card max-w-3xl w-full">
<div class="flex justify-between items-center mb-6">
<h2 class="text-2xl font-bold text-white">
<i class="fas fa-code text-purple-400 mr-2"></i>Embed Gallery Code</h2>
<button onclick="closeEmbedModal()" class="text-gray-400 hover:text-white">
<i class="fas fa-times text-xl"></i></button></div>

<div class="space-y-4">
<div>
<h3 class="text-white font-semibold mb-2">iFrame Embed Code</h3>
<div class="relative">
<textarea id="embed_iframe" readonly rows="4" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white font-mono text-sm"></textarea>
<button onclick="copyEmbedCode('iframe')" class="absolute top-2 right-2 px-3 py-1 bg-blue-500 rounded-lg text-white text-sm">
<i class="fas fa-copy mr-1"></i>Copy</button></div></div>

<div>
<h3 class="text-white font-semibold mb-2">Direct Link</h3>
<div class="relative">
<input type="text" id="embed_link" readonly class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white font-mono text-sm">
<button onclick="copyEmbedCode('link')" class="absolute top-2 right-2 px-3 py-1 bg-blue-500 rounded-lg text-white text-sm">
<i class="fas fa-copy mr-1"></i>Copy</button></div></div>

<div class="p-4 bg-purple-500/10 border border-purple-500/30 rounded-lg">
<h4 class="text-white font-semibold mb-2">
<i class="fas fa-info-circle mr-2"></i>How to Use</h4>
<ul class="text-purple-200 text-sm space-y-1">
<li>• Paste the iframe code into your website HTML</li>
<li>• Or share the direct link on social media</li>
<li>• Gallery must be "Public" to be viewable</li>
<li>• Customize iframe width/height as needed</li></ul></div>
</div>

<button onclick="closeEmbedModal()" class="w-full mt-4 px-6 py-3 bg-white/10 rounded-xl text-white font-semibold">Close</button>
</div></div>

<!-- AI Suggestions Modal -->
<div id="suggestionsModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
<div class="stat-card max-w-5xl w-full max-h-[90vh] overflow-y-auto">
<div class="flex justify-between items-center mb-6">
<h2 class="text-2xl font-bold text-white">
<i class="fas fa-robot text-purple-400 mr-2"></i>AI Photo Suggestions</h2>
<button onclick="closeSuggestionsModal()" class="text-gray-400 hover:text-white">
<i class="fas fa-times text-xl"></i></button></div>

<p class="text-gray-400 mb-4">Based on engagement metrics (views, likes, shares), these photos are performing best:</p>

<div class="grid grid-cols-3 gap-4">
@if(isset($suggestedPhotos))
@foreach($suggestedPhotos as $photo)
<div class="bg-white/5 rounded-lg overflow-hidden">
<div class="relative h-48">
<img src="{{ asset('storage/' . $photo->file_path) }}" alt="" class="w-full h-full object-cover">
<div class="absolute top-2 right-2">
<span class="px-2 py-1 bg-yellow-500 rounded-lg text-white text-xs font-semibold">
<i class="fas fa-star mr-1"></i>Trending</span></div></div>
<div class="p-3">
<p class="text-white text-sm mb-2">{{ $photo->title ?? 'Untitled' }}</p>
<div class="flex items-center justify-between text-xs text-gray-400">
<span><i class="fas fa-eye mr-1"></i>{{ $photo->views_count ?? 0 }}</span>
<span><i class="fas fa-heart mr-1"></i>{{ $photo->likes_count ?? 0 }}</span></div>
<button onclick="usePhoto({{ $photo->id }})" class="w-full mt-2 px-3 py-2 bg-purple-500 rounded-lg text-white text-xs font-semibold">
<i class="fas fa-plus mr-1"></i>Add to Album</button></div></div>
@endforeach
@endif
</div>
</div></div>

<!-- Photo Edit Modal (Caption & Photographer) -->
<div id="editPhotoModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
<div class="stat-card max-w-2xl w-full">
<div class="flex justify-between items-center mb-6">
<h2 class="text-2xl font-bold text-white">
<i class="fas fa-edit text-green-400 mr-2"></i>Edit Photo Details</h2>
<button onclick="closeEditPhotoModal()" class="text-gray-400 hover:text-white">
<i class="fas fa-times text-xl"></i></button></div>

<form id="editPhotoForm" class="space-y-4">
@csrf
<input type="hidden" id="edit_photo_id" name="photo_id">
<input type="hidden" id="edit_gallery_id" name="gallery_id">

<div>
<label class="block text-white font-semibold mb-2">Photo Preview</label>
<div class="w-full h-64 bg-white/5 rounded-lg overflow-hidden">
<img id="edit_photo_preview" src="" alt="" class="w-full h-full object-contain"></div></div>

<div>
<label class="block text-white font-semibold mb-2">Caption</label>
<textarea name="caption" id="edit_photo_caption" rows="3" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white" placeholder="Add a caption for this photo..."></textarea></div>

<div>
<label class="block text-white font-semibold mb-2">Photographer Credit</label>
<input type="text" name="photographer" id="edit_photo_photographer" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white" placeholder="John Doe Photography"></div>

<div class="flex space-x-3">
<button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 rounded-xl text-white font-semibold">
<i class="fas fa-save mr-2"></i>Save Changes</button>
<button type="button" onclick="closeEditPhotoModal()" class="px-6 py-3 bg-white/10 rounded-xl text-white">Cancel</button></div>
</form></div></div>

<!-- Download Settings Modal -->
<div id="downloadSettingsModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
<div class="stat-card max-w-2xl w-full">
<div class="flex justify-between items-center mb-6">
<h2 class="text-2xl font-bold text-white">
<i class="fas fa-download text-orange-400 mr-2"></i>Download Permission Settings</h2>
<button onclick="closeDownloadSettingsModal()" class="text-gray-400 hover:text-white">
<i class="fas fa-times text-xl"></i></button></div>

<div class="space-y-4">
<div class="p-4 bg-orange-500/10 border border-orange-500/30 rounded-lg">
<h3 class="text-white font-semibold mb-3">Current Settings</h3>
<p class="text-gray-300 text-sm mb-3">Control who can download photos from this album</p>

<div class="space-y-2">
<label class="flex items-center space-x-3 p-3 bg-white/5 rounded-lg cursor-pointer">
<input type="radio" name="download_permission" value="admin" checked class="w-4 h-4">
<div>
<p class="text-white font-semibold">Admin Only</p>
<p class="text-gray-400 text-xs">Only system administrators can download</p></div></label>

<label class="flex items-center space-x-3 p-3 bg-white/5 rounded-lg cursor-pointer">
<input type="radio" name="download_permission" value="leader" class="w-4 h-4">
<div>
<p class="text-white font-semibold">Admin & Ministry Leaders</p>
<p class="text-gray-400 text-xs">Admins and ministry leaders can download</p></div></label>

<label class="flex items-center space-x-3 p-3 bg-white/5 rounded-lg cursor-pointer">
<input type="radio" name="download_permission" value="member" class="w-4 h-4">
<div>
<p class="text-white font-semibold">All Members</p>
<p class="text-gray-400 text-xs">Any logged-in member can download</p></div></label>

<label class="flex items-center space-x-3 p-3 bg-white/5 rounded-lg cursor-pointer">
<input type="radio" name="download_permission" value="public" class="w-4 h-4">
<div>
<p class="text-white font-semibold">Public</p>
<p class="text-gray-400 text-xs">Anyone can download (not recommended)</p></div></label></div></div>

<button onclick="saveDownloadSettings()" class="w-full px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl text-white font-semibold">
<i class="fas fa-save mr-2"></i>Save Settings</button>
</div>
</div></div>

<!-- Sync to Public Site Modal -->
<div id="syncModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
<div class="stat-card max-w-2xl w-full">
<div class="flex justify-between items-center mb-6">
<h2 class="text-2xl font-bold text-white">
<i class="fas fa-sync text-green-400 mr-2"></i>Sync to Public Website</h2>
<button onclick="closeSyncModal()" class="text-gray-400 hover:text-white">
<i class="fas fa-times text-xl"></i></button></div>

<div class="space-y-4">
<div class="p-4 bg-green-500/10 border border-green-500/30 rounded-lg">
<h3 class="text-white font-semibold mb-2">
<i class="fas fa-info-circle mr-2"></i>Sync Information</h3>
<p class="text-green-200 text-sm mb-3">This will publish the album to your public-facing website</p>

<div class="space-y-2 text-sm text-green-300">
<p>✓ Album will be visible on your website gallery page</p>
<p>✓ Photos will be optimized for web viewing</p>
<p>✓ Captions and photographer credits will be included</p>
<p>✓ Download permissions will be respected</p></div></div>

<div class="p-4 bg-blue-500/10 border border-blue-500/30 rounded-lg">
<h3 class="text-white font-semibold mb-2">Sync Options</h3>
<label class="flex items-center space-x-2 cursor-pointer mb-2">
<input type="checkbox" checked class="w-4 h-4 rounded">
<span class="text-blue-300 text-sm">Optimize images for web</span></label>
<label class="flex items-center space-x-2 cursor-pointer mb-2">
<input type="checkbox" checked class="w-4 h-4 rounded">
<span class="text-blue-300 text-sm">Generate thumbnails</span></label>
<label class="flex items-center space-x-2 cursor-pointer">
<input type="checkbox" class="w-4 h-4 rounded">
<span class="text-blue-300 text-sm">Enable lightbox viewer</span></label></div>

<div class="flex space-x-3">
<button onclick="startSync()" class="flex-1 px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 rounded-xl text-white font-semibold">
<i class="fas fa-sync mr-2"></i>Start Sync</button>
<button onclick="closeSyncModal()" class="px-6 py-3 bg-white/10 rounded-xl text-white">Cancel</button></div>
</div>
</div></div>
