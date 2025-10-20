@extends('layouts.app')

@section('content')
<div>
    <!-- Header -->
    <div class="glass-card p-8 rounded-2xl mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-6">
                <!-- Profile Photo -->
                <div class="relative">
                    @if($member->profile_photo)
                        <img src="{{ asset('storage/' . $member->profile_photo) }}" alt="{{ $member->full_name }}" class="w-24 h-24 rounded-full object-cover border-4 border-neon-green">
                    @else
                        <div class="w-24 h-24 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center border-4 border-neon-green">
                            <span class="text-white text-3xl font-bold">{{ strtoupper(substr($member->first_name, 0, 1)) }}{{ strtoupper(substr($member->last_name, 0, 1)) }}</span>
                        </div>
                    @endif
                    <!-- Upload Photo Button -->
                    <button onclick="document.getElementById('profile-photo-input').click()" class="absolute bottom-0 right-0 bg-neon-green text-dark p-2 rounded-full hover:bg-green-400 transition">
                        <i class="fas fa-camera"></i>
                    </button>
                    <form id="profile-photo-form" action="{{ route('members.update', $member) }}" method="POST" enctype="multipart/form-data" class="hidden">
                        @csrf
                        @method('PUT')
                        <input type="file" id="profile-photo-input" name="profile_photo" accept="image/*" onchange="document.getElementById('profile-photo-form').submit()">
                    </form>
                </div>
                
                <div>
                    <h1 class="text-4xl font-black text-white">{{ $member->full_name }}</h1>
                    <p class="text-gray-300 text-lg">Member ID: {{ $member->member_id ?? 'N/A' }}</p>
                    <div class="flex items-center space-x-3 mt-2">
                        <span class="px-3 py-1 rounded-full text-sm
                            {{ $member->membership_status === 'active' ? 'bg-green-500/20 text-neon-green' : '' }}
                            {{ $member->membership_status === 'inactive' ? 'bg-gray-500/20 text-gray-400' : '' }}">
                            {{ ucfirst($member->membership_status ?? 'Active') }}
                        </span>
                        @if($member->age)
                        <span class="text-gray-400">{{ $member->age }} years old</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('members.edit', $member) }}" class="btn btn-primary">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
                <a href="{{ route('members.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </a>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="glass-card p-2 rounded-2xl mb-6">
        <div class="flex space-x-2">
            <button onclick="showTab('personal')" id="tab-personal" class="tab-btn active px-6 py-3 rounded-xl font-semibold transition">
                <i class="fas fa-user mr-2"></i> Personal Info
            </button>
            <button onclick="showTab('contacts')" id="tab-contacts" class="tab-btn px-6 py-3 rounded-xl font-semibold transition">
                <i class="fas fa-phone mr-2"></i> Contact Details
            </button>
            <button onclick="showTab('emergency')" id="tab-emergency" class="tab-btn px-6 py-3 rounded-xl font-semibold transition">
                <i class="fas fa-exclamation-triangle mr-2"></i> Emergency Contacts
            </button>
            <button onclick="showTab('documents')" id="tab-documents" class="tab-btn px-6 py-3 rounded-xl font-semibold transition">
                <i class="fas fa-file-alt mr-2"></i> Documents
            </button>
            <button onclick="showTab('activity')" id="tab-activity" class="tab-btn px-6 py-3 rounded-xl font-semibold transition">
                <i class="fas fa-chart-line mr-2"></i> Activity
            </button>
        </div>
    </div>

    <!-- Personal Info Tab -->
    <div id="content-personal" class="tab-content">
        <div class="glass-card p-6 rounded-2xl mb-6">
            <h2 class="text-2xl font-bold text-white mb-6">Personal Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="text-sm font-semibold text-gray-400 mb-2 block">First Name</label>
                    <p class="text-white text-lg">{{ $member->first_name }}</p>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-400 mb-2 block">Middle Name</label>
                    <p class="text-white text-lg">{{ $member->middle_name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-400 mb-2 block">Last Name</label>
                    <p class="text-white text-lg">{{ $member->last_name }}</p>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-400 mb-2 block">Date of Birth</label>
                    <p class="text-white text-lg">{{ $member->date_of_birth ? $member->date_of_birth->format('M d, Y') : 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-400 mb-2 block">Gender</label>
                    <p class="text-white text-lg">{{ ucfirst($member->gender ?? 'N/A') }}</p>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-400 mb-2 block">Marital Status</label>
                    <p class="text-white text-lg">{{ ucfirst($member->marital_status ?? 'N/A') }}</p>
                </div>
                @if($member->wedding_anniversary)
                <div>
                    <label class="text-sm font-semibold text-gray-400 mb-2 block">Wedding Anniversary</label>
                    <p class="text-white text-lg">{{ $member->wedding_anniversary->format('M d, Y') }}</p>
                </div>
                @endif
                <div>
                    <label class="text-sm font-semibold text-gray-400 mb-2 block">Occupation</label>
                    <p class="text-white text-lg">{{ $member->occupation ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-400 mb-2 block">Employer</label>
                    <p class="text-white text-lg">{{ $member->employer ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Membership Info -->
        <div class="glass-card p-6 rounded-2xl">
            <h2 class="text-2xl font-bold text-white mb-6">Membership Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="text-sm font-semibold text-gray-400 mb-2 block">Membership Date</label>
                    <p class="text-white text-lg">{{ $member->membership_date ? $member->membership_date->format('M d, Y') : 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-400 mb-2 block">Baptism Date</label>
                    <p class="text-white text-lg">{{ $member->baptism_date ? $member->baptism_date->format('M d, Y') : 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-400 mb-2 block">Status</label>
                    <p class="text-white text-lg">{{ ucfirst($member->membership_status ?? 'Active') }}</p>
                </div>
            </div>
            @if($member->notes)
            <div class="mt-6">
                <label class="text-sm font-semibold text-gray-400 mb-2 block">Notes</label>
                <p class="text-white">{{ $member->notes }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Contact Details Tab -->
    <div id="content-contacts" class="tab-content hidden">
        <div class="glass-card p-6 rounded-2xl">
            <h2 class="text-2xl font-bold text-white mb-6">Contact Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-semibold text-gray-400 mb-2 block">Phone</label>
                    <p class="text-white text-lg flex items-center">
                        <i class="fas fa-phone mr-2 text-neon-green"></i>
                        {{ $member->phone ?? 'N/A' }}
                    </p>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-400 mb-2 block">Alternate Phone</label>
                    <p class="text-white text-lg flex items-center">
                        <i class="fas fa-phone mr-2 text-blue-400"></i>
                        {{ $member->alternate_phone ?? 'N/A' }}
                    </p>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-400 mb-2 block">Email</label>
                    <p class="text-white text-lg flex items-center">
                        <i class="fas fa-envelope mr-2 text-purple-400"></i>
                        {{ $member->email ?? 'N/A' }}
                    </p>
                </div>
            </div>
            <div class="mt-6">
                <label class="text-sm font-semibold text-gray-400 mb-2 block">Address</label>
                <div class="bg-white/5 p-4 rounded-xl">
                    <p class="text-white">
                        {{ $member->address ?? 'N/A' }}<br>
                        {{ $member->city }}{{ $member->state ? ', ' . $member->state : '' }} {{ $member->postal_code }}<br>
                        {{ $member->country }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Emergency Contacts Tab -->
    <div id="content-emergency" class="tab-content hidden">
        <div class="glass-card p-6 rounded-2xl">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-white">Emergency Contacts</h2>
                <button onclick="showAddEmergencyContactModal()" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i> Add Contact
                </button>
            </div>

            @if($member->emergencyContacts->count() > 0)
                <div class="space-y-4">
                    @foreach($member->emergencyContacts as $contact)
                    <div class="bg-white/5 p-6 rounded-xl hover:bg-white/10 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="text-sm text-gray-400">Name</label>
                                    <p class="text-white font-semibold">{{ $contact->name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-400">Relationship</label>
                                    <p class="text-white">{{ $contact->relationship }}</p>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-400">Phone</label>
                                    <p class="text-white">{{ $contact->phone }}</p>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-400">Alternate Phone</label>
                                    <p class="text-white">{{ $contact->alternate_phone ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="flex space-x-2 ml-4">
                                <button onclick="editEmergencyContact({{ $contact->id }}, '{{ $contact->name }}', '{{ $contact->relationship }}', '{{ $contact->phone }}', '{{ $contact->alternate_phone }}', '{{ $contact->address }}')" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form method="POST" action="{{ route('members.emergency-contacts.delete', [$member, $contact->id]) }}" class="inline" onsubmit="return confirm('Delete this emergency contact?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm bg-red-500/20 text-red-400">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @if($contact->address)
                        <div class="mt-4">
                            <label class="text-sm text-gray-400">Address</label>
                            <p class="text-white text-sm">{{ $contact->address }}</p>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-address-book text-6xl text-gray-600 mb-4"></i>
                    <p class="text-gray-400 text-lg">No emergency contacts added yet</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Documents Tab -->
    <div id="content-documents" class="tab-content hidden">
        <div class="glass-card p-6 rounded-2xl">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-white">Documents & Certificates</h2>
                <button onclick="showUploadDocumentModal()" class="btn btn-primary">
                    <i class="fas fa-upload mr-2"></i> Upload Document
                </button>
            </div>

            @if($member->documents->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($member->documents as $document)
                    <div class="bg-gradient-to-br from-white/10 to-white/5 p-6 rounded-2xl hover:from-white/15 hover:to-white/10 transition-all duration-300 border border-white/10 hover:border-green-500/50 shadow-lg hover:shadow-green-500/20">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-4 flex-1">
                                <div class="w-14 h-14 bg-gradient-to-br from-blue-500/30 to-blue-600/30 rounded-xl flex items-center justify-center shadow-lg">
                                    @php
                                        $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
                                        $iconClass = 'fa-file-alt';
                                        $iconColor = 'text-blue-400';
                                        if($extension == 'pdf') { $iconClass = 'fa-file-pdf'; $iconColor = 'text-red-400'; }
                                        elseif(in_array($extension, ['jpg', 'jpeg', 'png'])) { $iconClass = 'fa-file-image'; $iconColor = 'text-green-400'; }
                                        elseif(in_array($extension, ['doc', 'docx'])) { $iconClass = 'fa-file-word'; $iconColor = 'text-blue-500'; }
                                    @endphp
                                    <i class="fas {{ $iconClass }} {{ $iconColor }} text-2xl"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-white font-bold text-sm mb-1 truncate">{{ $document->document_name }}</p>
                                    <span class="inline-block px-2 py-1 bg-green-500/20 text-green-400 text-xs rounded-md font-medium">
                                        {{ $document->document_type }}
                                    </span>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('members.documents.delete', [$member, $document->id]) }}" class="inline ml-2" onsubmit="return confirm('Delete this document?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300 hover:bg-red-500/10 p-2 rounded-lg transition-all">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                        <div class="bg-black/20 rounded-lg p-3 mb-4">
                            <div class="grid grid-cols-2 gap-3 text-xs">
                                <div>
                                    <p class="text-gray-500 mb-1">File Size</p>
                                    <p class="text-white font-semibold">{{ number_format($document->file_size / 1024, 2) }} KB</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 mb-1">Uploaded</p>
                                    <p class="text-white font-semibold">{{ $document->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('members.documents.download', [$member, $document->id]) }}" class="block w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white text-center px-4 py-3 rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-green-500/50">
                            <i class="fas fa-download mr-2"></i> Download
                        </a>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-folder-open text-6xl text-gray-600 mb-4"></i>
                    <p class="text-gray-400 text-lg">No documents uploaded yet</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Activity Tab -->
    <div id="content-activity" class="tab-content hidden">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="glass-card p-6 rounded-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Total Attendance</p>
                        <p class="text-3xl font-bold text-white">{{ $member->attendanceRecords->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-500/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-check text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="glass-card p-6 rounded-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Total Donations</p>
                        <p class="text-3xl font-bold text-white">${{ number_format($member->donations->sum('amount'), 2) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-500/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-hand-holding-usd text-blue-400 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="glass-card p-6 rounded-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm mb-1">Total Pledges</p>
                        <p class="text-3xl font-bold text-white">${{ number_format($member->pledges->sum('amount'), 2) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-500/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-hands-helping text-purple-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="glass-card p-6 rounded-2xl">
            <h2 class="text-2xl font-bold text-white mb-6">Recent Activity</h2>
            @if($member->attendanceRecords->take(10)->count() > 0)
                <div class="space-y-3">
                    @foreach($member->attendanceRecords->take(10) as $attendance)
                    <div class="flex items-center justify-between p-4 bg-white/5 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-green-500/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-green-400"></i>
                            </div>
                            <div>
                                <p class="text-white font-semibold">{{ $attendance->service_type }}</p>
                                <p class="text-gray-400 text-sm">Attendance</p>
                            </div>
                        </div>
                        <p class="text-gray-400">{{ $attendance->created_at->format('M d, Y') }}</p>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400 text-center py-8">No recent activity</p>
            @endif
        </div>
    </div>
</div>

<!-- Add Emergency Contact Modal -->
<div id="addEmergencyContactModal" class="modal hidden">
    <div class="modal-overlay" onclick="closeModal('addEmergencyContactModal')"></div>
    <div class="modal-content">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-white">Add Emergency Contact</h2>
            <button onclick="closeModal('addEmergencyContactModal')" class="text-gray-400 hover:text-white">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('members.emergency-contacts.add', $member) }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Name *</label>
                    <input type="text" name="name" required class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Relationship *</label>
                    <input type="text" name="relationship" required class="input-field" placeholder="e.g., Spouse, Parent, Sibling">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Phone *</label>
                        <input type="text" name="phone" required class="input-field">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Alternate Phone</label>
                        <input type="text" name="alternate_phone" class="input-field">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Address</label>
                    <textarea name="address" rows="2" class="input-field"></textarea>
                </div>
                <div class="flex space-x-3">
                    <button type="submit" class="btn btn-primary flex-1">
                        <i class="fas fa-save mr-2"></i> Save Contact
                    </button>
                    <button type="button" onclick="closeModal('addEmergencyContactModal')" class="btn btn-secondary">
                        Cancel
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Upload Document Modal -->
<div id="uploadDocumentModal" class="modal hidden">
    <div class="modal-overlay" onclick="closeModal('uploadDocumentModal')"></div>
    <div class="modal-content">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-white">Upload Document</h2>
            <button onclick="closeModal('uploadDocumentModal')" class="text-gray-400 hover:text-white">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('members.documents.upload', $member) }}" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Document Type *</label>
                    <select name="document_type" required class="input-field">
                        <option value="">Select type...</option>
                        <option value="Baptism Certificate">Baptism Certificate</option>
                        <option value="ID Document">ID Document</option>
                        <option value="Membership Form">Membership Form</option>
                        <option value="Letter of Transfer">Letter of Transfer</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Document Name *</label>
                    <input type="text" name="document_name" required class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">
                        <i class="fas fa-file-upload mr-2"></i>Choose File * (Max 10MB)
                    </label>
                    <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-600 border-dashed rounded-xl hover:border-green-500 transition-colors">
                        <div class="space-y-2 text-center">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                            <div class="flex text-sm text-gray-400">
                                <label for="document-upload" class="relative cursor-pointer bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg transition-all">
                                    <span>Browse files</span>
                                    <input id="document-upload" name="document" type="file" class="sr-only" required accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" onchange="showFileName(this)">
                                </label>
                                <p class="pl-3 self-center">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">
                                PDF, JPG, PNG, DOC, DOCX up to 10MB
                            </p>
                            <p id="file-name" class="text-sm text-green-400 mt-2 hidden"></p>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <button type="submit" class="btn btn-primary flex-1">
                        <i class="fas fa-upload mr-2"></i> Upload Document
                    </button>
                    <button type="button" onclick="closeModal('uploadDocumentModal')" class="btn btn-secondary">
                        Cancel
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.tab-btn {
    background: transparent;
    color: #9CA3AF;
}
.tab-btn.active {
    background: rgba(16, 185, 129, 0.2);
    color: #10B981;
}
.tab-btn:hover {
    background: rgba(255, 255, 255, 0.05);
}
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-center;
    z-index: 1000;
}
.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
}
.modal-content {
    position: relative;
    background: linear-gradient(135deg, rgba(30, 30, 40, 0.95), rgba(20, 20, 30, 0.95));
    backdrop-filter: blur(10px);
    border-radius: 1.5rem;
    padding: 2rem;
    max-width: 600px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    border: 1px rgba(255, 255, 255, 0.1) solid;
}
</style>

<script>
function showTab(tabName) {
    // Hide all content
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected content
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Add active class to selected tab
    document.getElementById('tab-' + tabName).classList.add('active');
}

function showAddEmergencyContactModal() {
    document.getElementById('addEmergencyContactModal').classList.remove('hidden');
}

function showUploadDocumentModal() {
    document.getElementById('uploadDocumentModal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function editEmergencyContact(id, name, relationship, phone, alternatePhone, address) {
    // Implement edit functionality
    alert('Edit functionality to be implemented');
}

function showFileName(input) {
    const fileName = input.files[0]?.name;
    const fileNameDisplay = document.getElementById('file-name');
    if (fileName) {
        fileNameDisplay.textContent = `Selected: ${fileName}`;
        fileNameDisplay.classList.remove('hidden');
    }
}
</script>

@endsection
