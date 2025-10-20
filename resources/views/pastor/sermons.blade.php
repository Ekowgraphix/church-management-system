@extends('layouts.pastor')

@section('content')
<div>
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">📖 Sermons & Messages</h1>
                <p class="text-gray-600">Upload and manage your sermon content</p>
            </div>
            <button id="uploadSermonBtn" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                <i class="fas fa-plus mr-2"></i>Upload New Sermon
            </button>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
            <p class="font-semibold">{{ session('success') }}</p>
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- Sermons List -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-800">All Sermons</h3>
                <input type="text" id="searchInput" placeholder="Search sermons..." class="border border-gray-300 rounded-lg px-4 py-2 w-64" oninput="searchSermons()">
            </div>
        </div>

        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Theme</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bible Reference</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($sermons as $sermon)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-800">{{ $sermon->title }}</p>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $sermon->sermon_date ? $sermon->sermon_date->format('M d, Y') : 'N/A' }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $sermon->theme }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $sermon->scripture_reference }}</td>
                        <td class="px-6 py-4">
                            <button onclick="editSermon({{ $sermon->id }}, '{{ $sermon->title }}', '{{ $sermon->theme }}', '{{ $sermon->scripture_reference }}')" class="text-blue-600 hover:text-blue-800 mr-3" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="shareSermon('{{ $sermon->title }}')" class="text-green-600 hover:text-green-800 mr-3" title="Share">
                                <i class="fas fa-share-alt"></i>
                            </button>
                            <form action="{{ route('pastor.sermons.delete', $sermon->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this sermon?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-book-open text-4xl mb-4 text-gray-300"></i>
                            <p>No sermons uploaded yet</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($sermons->hasPages())
            <div class="p-6 border-t">
                {{ $sermons->links() }}
            </div>
        @endif
    </div>
</div>

<script>
console.log('Sermons page JavaScript loaded');

// Upload New Sermon Modal
function openUploadModal() {
    console.log('openUploadModal called');
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-upload text-blue-600 mr-2"></i>Upload New Sermon
                </h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <form id="uploadSermonForm" action="{{ route('pastor.sermons.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Sermon Title *</label>
                    <input type="text" name="title" id="sermonTitle" required class="w-full border border-gray-300 rounded-lg px-4 py-3" placeholder="Enter sermon title">
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Date *</label>
                        <input type="date" name="sermon_date" id="sermonDate" required class="w-full border border-gray-300 rounded-lg px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Theme</label>
                        <input type="text" name="theme" id="sermonTheme" class="w-full border border-gray-300 rounded-lg px-4 py-3" placeholder="e.g., Faith, Hope, Love">
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Bible Reference *</label>
                    <input type="text" name="scripture_reference" id="sermonScripture" required class="w-full border border-gray-300 rounded-lg px-4 py-3" placeholder="e.g., John 3:16, Romans 8:28">
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Sermon Notes/Outline</label>
                    <textarea name="notes" id="sermonNotes" rows="6" class="w-full border border-gray-300 rounded-lg px-4 py-3" placeholder="Enter sermon outline or key points..."></textarea>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Upload Audio/Video (Optional)</label>
                    <input type="file" name="audio_file" id="sermonFile" accept="audio/*,video/*" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                    <p class="text-xs text-gray-500 mt-1">Supported: MP3, MP4, WAV, M4A (Max 50MB)</p>
                </div>
                
                <div class="flex space-x-3">
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700">
                        <i class="fas fa-upload mr-2"></i>Upload Sermon
                    </button>
                    <button type="button" onclick="closeModal()" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    `;
    document.body.appendChild(modal);
    
    // Set today's date as default
    document.getElementById('sermonDate').valueAsDate = new Date();
}

// Edit Sermon
function editSermon(id, title, theme, scripture) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-2xl w-full mx-4">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-edit text-blue-600 mr-2"></i>Edit Sermon
                </h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <form id="editSermonForm" action="{{ url('/pastor/sermons') }}/${id}/update" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Sermon Title</label>
                    <input type="text" name="title" id="editTitle" value="${title}" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Theme</label>
                    <input type="text" name="theme" id="editTheme" value="${theme}" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                </div>
                
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Bible Reference</label>
                    <input type="text" name="scripture_reference" id="editScripture" value="${scripture}" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                </div>
                
                <div class="flex space-x-3">
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700">
                        <i class="fas fa-save mr-2"></i>Save Changes
                    </button>
                    <button type="button" onclick="closeModal()" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    `;
    document.body.appendChild(modal);
    
}

// Share Sermon
function shareSermon(title) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-share-alt text-green-600 mr-2"></i>Share Sermon
                </h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <p class="text-gray-600 mb-6"><strong>${title}</strong></p>
            
            <div class="space-y-3">
                <button onclick="shareTo('facebook')" class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 flex items-center justify-center">
                    <i class="fab fa-facebook mr-2"></i>Share on Facebook
                </button>
                <button onclick="shareTo('twitter')" class="w-full bg-sky-500 text-white py-3 rounded-lg font-semibold hover:bg-sky-600 flex items-center justify-center">
                    <i class="fab fa-twitter mr-2"></i>Share on Twitter
                </button>
                <button onclick="shareTo('whatsapp')" class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 flex items-center justify-center">
                    <i class="fab fa-whatsapp mr-2"></i>Share on WhatsApp
                </button>
                <button onclick="copyLink('${title}')" class="w-full bg-gray-600 text-white py-3 rounded-lg font-semibold hover:bg-gray-700 flex items-center justify-center">
                    <i class="fas fa-link mr-2"></i>Copy Link
                </button>
                <button onclick="emailSermon('${title}')" class="w-full bg-purple-600 text-white py-3 rounded-lg font-semibold hover:bg-purple-700 flex items-center justify-center">
                    <i class="fas fa-envelope mr-2"></i>Email to Members
                </button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
}

// Share to platform
function shareTo(platform) {
    closeModal();
    const capitalizedPlatform = platform.charAt(0).toUpperCase() + platform.slice(1);
    showNotification('✓ Opening ' + capitalizedPlatform + '...', 'success');
}

// Copy link
function copyLink(title) {
    const link = window.location.origin + '/sermons/' + title.toLowerCase().replace(/ /g, '-');
    navigator.clipboard.writeText(link).then(() => {
        closeModal();
        showNotification('✓ Link copied to clipboard!', 'success');
    });
}

// Email sermon
function emailSermon(title) {
    closeModal();
    showNotification('✓ Opening email composer...', 'info');
}


// Search Sermons
function searchSermons() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    let visibleCount = 0;
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    if (searchTerm && visibleCount === 0) {
        showNotification('No sermons found matching "' + searchTerm + '"', 'info');
    }
}

// Close modal
function closeModal() {
    const modal = document.querySelector('.fixed.inset-0');
    if (modal) modal.remove();
}

// Show notification
function showNotification(message, type) {
    const colors = {
        success: 'bg-green-500',
        info: 'bg-blue-500',
        warning: 'bg-yellow-500',
        error: 'bg-red-500'
    };
    
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 ' + colors[type] + ' text-white px-6 py-4 rounded-lg shadow-lg z-50 animate-slide-in';
    notification.textContent = message;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});

// Add animation
const style = document.createElement('style');
style.textContent = '@keyframes slide-in { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } } .animate-slide-in { animation: slide-in 0.3s ease-out; }';
document.head.appendChild(style);

// Initialize Upload Button
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, attaching upload button listener');
    const uploadBtn = document.getElementById('uploadSermonBtn');
    if (uploadBtn) {
        uploadBtn.addEventListener('click', function() {
            console.log('Upload button clicked');
            openUploadModal();
        });
        console.log('Upload button listener attached');
    } else {
        console.error('Upload button not found!');
    }
});
</script>
@endsection
