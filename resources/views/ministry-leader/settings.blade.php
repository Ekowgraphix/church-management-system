@extends('layouts.ministry-leader')

@section('title', 'Settings')
@section('header', 'Ministry Leader Settings')

@section('content')
<div class="max-w-4xl mx-auto">
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

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-6">
            <i class="fas fa-user-circle text-purple-600 mr-2"></i>
            Profile Information
        </h3>
        
        <form action="{{ route('ministry-leader.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Profile Photo Section -->
            <div class="flex items-center space-x-6 pb-6 border-b border-gray-200">
                <div class="relative">
                    <img id="photoPreview" 
                         src="{{ $user->profile_photo ? asset('uploads/profiles/' . $user->profile_photo) : asset('images/default-avatar.svg') }}" 
                         alt="Profile Photo" 
                         class="w-24 h-24 rounded-full object-cover border-4 border-purple-200">
                    <label for="profile_photo" class="absolute bottom-0 right-0 bg-purple-600 text-white rounded-full p-2 cursor-pointer hover:bg-purple-700 transition">
                        <i class="fas fa-camera"></i>
                        <input type="file" 
                               id="profile_photo" 
                               name="profile_photo" 
                               accept="image/*" 
                               class="hidden" 
                               onchange="previewPhoto(event)">
                    </label>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800">Profile Photo</h4>
                    <p class="text-sm text-gray-500">Click the camera icon to upload a new photo</p>
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG or GIF (Max 2MB)</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Phone</label>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Ministry Role</label>
                    <input type="text" value="Ministry Leader" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly>
                </div>
            </div>
            
            <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                <i class="fas fa-save mr-2"></i>Update Profile
            </button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-6">
            <i class="fas fa-lock text-blue-600 mr-2"></i>
            Change Password
        </h3>
        
        <form action="{{ route('ministry-leader.settings.password') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Current Password</label>
                <input type="password" name="current_password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                <input type="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm New Password</label>
                <input type="password" name="password_confirmation" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-key mr-2"></i>Change Password
            </button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-6">
            <i class="fas fa-bell text-green-600 mr-2"></i>
            Notification Preferences
        </h3>
        
        <div class="space-y-4">
            <label class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                <span class="text-gray-800">Email notifications for new prayer requests</span>
                <input type="checkbox" checked class="w-5 h-5 text-green-600">
            </label>
            <label class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                <span class="text-gray-800">SMS reminders for events</span>
                <input type="checkbox" checked class="w-5 h-5 text-green-600">
            </label>
            <label class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                <span class="text-gray-800">Weekly ministry reports</span>
                <input type="checkbox" class="w-5 h-5 text-green-600">
            </label>
        </div>
    </div>
</div>

<script>
function previewPhoto(event) {
    const file = event.target.files[0];
    if (file) {
        // Check file size (2MB max)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            event.target.value = '';
            return;
        }
        
        // Check file type
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file');
            event.target.value = '';
            return;
        }
        
        // Preview image
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('photoPreview').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
