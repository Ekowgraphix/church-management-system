@extends('layouts.media')

@section('title', 'Media Library')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-black text-white mb-2">
                <i class="fas fa-photo-video text-green-400 mr-3"></i>
                Media Library
            </h1>
            <p class="text-gray-400">Central storage for all church digital assets</p>
        </div>
        <button onclick="openUploadModal()" class="px-6 py-3 gradient-green rounded-xl text-white font-semibold hover:shadow-lg transition-all">
            <i class="fas fa-cloud-upload-alt mr-2"></i>
            Upload Media
        </button>
    </div>
    
    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="stat-card">
            <div class="flex items-center justify-between mb-2">
                <span class="text-gray-400 text-sm">Total Files</span>
                <i class="fas fa-file text-green-400"></i>
            </div>
            <p class="text-3xl font-bold text-white">{{ $stats['total_files'] }}</p>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between mb-2">
                <span class="text-gray-400 text-sm">My Uploads</span>
                <i class="fas fa-upload text-blue-400"></i>
            </div>
            <p class="text-3xl font-bold text-white">{{ $stats['my_uploads'] }}</p>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between mb-2">
                <span class="text-gray-400 text-sm">Total Views</span>
                <i class="fas fa-eye text-purple-400"></i>
            </div>
            <p class="text-3xl font-bold text-white">{{ number_format($stats['total_views']) }}</p>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between mb-2">
                <span class="text-gray-400 text-sm">Storage</span>
                <i class="fas fa-hdd text-orange-400"></i>
            </div>
            <p class="text-3xl font-bold text-white">{{ number_format($stats['storage_used'] / 1048576, 1) }} MB</p>
        </div>
    </div>
    
    <!-- Media Grid -->
    @if($mediaFiles->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
            @foreach($mediaFiles as $file)
                <div class="stat-card p-0 overflow-hidden hover:scale-105 transition-transform cursor-pointer" onclick="viewFile({{ $file->id }})">
                    <div class="aspect-square bg-gradient-to-br from-gray-700 to-gray-800 flex items-center justify-center relative">
                        @if($file->type === 'image')
                            <img src="{{ $file->file_url }}" alt="{{ $file->title }}" class="w-full h-full object-cover">
                        @elseif($file->type === 'video')
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-red-900/30 to-purple-900/30">
                                <i class="fas fa-play-circle text-6xl text-white/70"></i>
                            </div>
                        @elseif($file->type === 'audio')
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-900/30 to-green-900/30">
                                <i class="fas fa-music text-5xl text-white/70"></i>
                            </div>
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-800 to-gray-900">
                                <i class="fas fa-file-alt text-5xl text-white/70"></i>
                            </div>
                        @endif
                        <div class="absolute top-2 right-2">
                            <span class="px-2 py-1 text-xs rounded bg-black/70 text-white font-semibold">
                                {{ strtoupper($file->type) }}
                            </span>
                        </div>
                        @if($file->type === 'video')
                            <div class="absolute bottom-2 right-2">
                                <span class="px-2 py-1 text-xs rounded bg-black/70 text-white">
                                    <i class="fas fa-video mr-1"></i>
                                    {{ gmdate('i:s', $file->file_size / 100000) }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="p-3">
                        <h4 class="text-white font-semibold text-sm truncate">{{ $file->title }}</h4>
                        <p class="text-gray-400 text-xs">{{ $file->created_at->format('M d, Y') }}</p>
                        <div class="flex items-center justify-between mt-2 text-xs text-gray-500">
                            <span><i class="fas fa-eye mr-1"></i>{{ $file->views_count }}</span>
                            <span>{{ number_format($file->file_size / 1024, 0) }} KB</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $mediaFiles->links() }}
        </div>
    @else
        <div class="stat-card text-center py-20">
            <i class="fas fa-cloud-upload-alt text-6xl text-gray-500 mb-4"></i>
            <h2 class="text-2xl font-bold text-white mb-2">No Media Yet</h2>
            <p class="text-gray-400 mb-6">Upload your first file to get started</p>
            <button onclick="openUploadModal()" class="px-6 py-3 gradient-green rounded-xl text-white font-semibold">
                <i class="fas fa-upload mr-2"></i>
                Upload Now
            </button>
        </div>
    @endif
</div>

<!-- Upload Modal -->
<div id="uploadModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
    <div class="stat-card max-w-2xl w-full">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-white">
                <i class="fas fa-cloud-upload-alt text-green-400 mr-2"></i>
                Upload Media
            </h2>
            <button onclick="closeUploadModal()" class="text-gray-400 hover:text-white">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="uploadForm" class="space-y-4">
            @csrf
            <div id="dropZone" class="border-2 border-dashed border-white/20 rounded-xl p-12 text-center hover:border-green-400 transition-all cursor-pointer">
                <input type="file" id="fileInput" name="file" class="hidden" accept="image/*,video/*,audio/*,application/pdf">
                <div id="dropZoneContent">
                    <i class="fas fa-cloud-upload-alt text-6xl text-gray-400 mb-4"></i>
                    <p class="text-white font-semibold mb-2">Drop files here or click to browse</p>
                    <p class="text-gray-400 text-sm">Maximum file size: 100MB</p>
                </div>
            </div>
            
            <div>
                <label class="block text-white font-semibold mb-2">Title *</label>
                <input type="text" name="title" id="fileTitle" required class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-400">
            </div>
            
            <div>
                <label class="block text-white font-semibold mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-400"></textarea>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-white font-semibold mb-2">Type *</label>
                    <select name="type" id="fileType" required class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-400">
                        <option value="image">Image</option>
                        <option value="video">Video</option>
                        <option value="audio">Audio</option>
                        <option value="document">Document</option>
                    </select>
                </div>
                <div>
                    <label class="block text-white font-semibold mb-2">Category</label>
                    <input type="text" name="category" placeholder="sermon, worship, youth..." class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-400">
                </div>
            </div>
            
            <div class="flex items-center">
                <input type="checkbox" name="is_public" id="isPublic" checked class="mr-2">
                <label for="isPublic" class="text-white">Make this file public</label>
            </div>
            
            <div class="flex space-x-3">
                <button type="submit" class="flex-1 px-6 py-3 gradient-green rounded-xl text-white font-semibold hover:shadow-lg transition-all">
                    <i class="fas fa-upload mr-2"></i>
                    Upload
                </button>
                <button type="button" onclick="closeUploadModal()" class="px-6 py-3 bg-white/10 rounded-xl text-white font-semibold hover:bg-white/20 transition-all">
                    Cancel
                </button>
            </div>
        </form>
        
        <div id="uploadProgress" class="hidden mt-4">
            <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden">
                <div id="progressBar" class="h-full gradient-green transition-all duration-300" style="width: 0%"></div>
            </div>
            <p class="text-center text-gray-400 text-sm mt-2" id="progressText">Uploading...</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openUploadModal() {
        document.getElementById('uploadModal').classList.remove('hidden');
    }
    
    function closeUploadModal() {
        document.getElementById('uploadModal').classList.add('hidden');
        document.getElementById('uploadForm').reset();
    }
    
    // Drag & Drop
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    
    dropZone.addEventListener('click', () => fileInput.click());
    
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-green-400');
    });
    
    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-green-400');
    });
    
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-green-400');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            updateFileName();
        }
    });
    
    fileInput.addEventListener('change', updateFileName);
    
    function updateFileName() {
        const fileName = fileInput.files[0]?.name;
        if (fileName) {
            document.getElementById('fileTitle').value = fileName.split('.')[0];
            document.getElementById('dropZoneContent').innerHTML = `
                <i class="fas fa-file text-4xl text-green-400 mb-4"></i>
                <p class="text-white font-semibold">${fileName}</p>
                <p class="text-gray-400 text-sm mt-2">Click to change file</p>
            `;
        }
    }
    
    // Upload Form
    document.getElementById('uploadForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // Check if file is selected
        if (!fileInput.files || fileInput.files.length === 0) {
            alert('Please select a file to upload');
            return;
        }
        
        const formData = new FormData(e.target);
        
        // Make sure is_public checkbox value is included
        const isPublicCheckbox = document.getElementById('isPublic');
        if (isPublicCheckbox.checked) {
            formData.set('is_public', '1');
        } else {
            formData.set('is_public', '0');
        }
        
        const progressDiv = document.getElementById('uploadProgress');
        const progressBar = document.getElementById('progressBar');
        const progressText = document.getElementById('progressText');
        
        progressDiv.classList.remove('hidden');
        progressBar.style.width = '0%';
        progressText.textContent = 'Uploading...';
        
        try {
            const response = await fetch('{{ route("media.library.upload") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            const data = await response.json();
            
            if (response.ok && data.success) {
                progressText.textContent = 'Upload complete!';
                progressBar.style.width = '100%';
                
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                // Show detailed error message
                let errorMsg = 'Upload failed: ' + (data.message || 'Unknown error');
                if (data.errors) {
                    errorMsg += '\n\nDetails:\n';
                    for (let field in data.errors) {
                        errorMsg += data.errors[field].join(', ') + '\n';
                    }
                }
                alert(errorMsg);
                console.error('Upload error:', data);
                progressDiv.classList.add('hidden');
            }
        } catch (error) {
            console.error('Upload error:', error);
            alert('Upload failed. Please check the console for details.');
            progressDiv.classList.add('hidden');
        }
    });
    
    function viewFile(id) {
        // Implement file preview modal
        console.log('View file:', id);
    }
</script>
@endpush
@endsection
