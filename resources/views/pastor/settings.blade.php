@extends('layouts.pastor')

@section('content')
<div>
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">⚙️ Settings</h1>
                <p class="text-gray-600">Manage your profile and preferences</p>
            </div>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Section -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Profile Information -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Profile Information</h3>
                
                <!-- Photo Upload Form (separate) -->
                <div class="flex items-center space-x-6 mb-6">
                    <div class="relative">
                        @if($pastor->profile_photo)
                            <img src="{{ asset('uploads/profiles/' . $pastor->profile_photo) }}" class="w-24 h-24 rounded-full object-cover border-4 border-blue-500" id="profilePhoto">
                        @else
                            <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-3xl border-4 border-blue-500" id="profilePhoto">
                                {{ strtoupper(substr($pastor->name, 0, 1)) }}
                            </div>
                        @endif
                        <img id="photoPreview" class="w-24 h-24 rounded-full object-cover border-4 border-blue-500 hidden" style="position: absolute; top: 0; left: 0;">
                    </div>
                    <div>
                        <button type="button" onclick="document.getElementById('photoInput').click()" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 mb-2">
                            <i class="fas fa-camera mr-2"></i>Upload Photo
                        </button>
                        <p class="text-sm text-gray-600">JPG, PNG or GIF (max 2MB)</p>
                    </div>
                </div>

                <!-- Profile Update Form -->
                <form action="{{ route('pastor.settings.profile') }}" method="POST" id="profileForm">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Full Name</label>
                            <input type="text" name="name" value="{{ $pastor->name }}" required class="w-full border border-gray-300 rounded-lg px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Email</label>
                            <input type="email" name="email" value="{{ $pastor->email }}" required class="w-full border border-gray-300 rounded-lg px-4 py-3">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Phone</label>
                            <input type="tel" name="phone" value="{{ $pastor->phone }}" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Ministry</label>
                            <input type="text" placeholder="Senior Pastor" class="w-full border border-gray-300 rounded-lg px-4 py-3" disabled>
                        </div>
                    </div>

                    <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700">
                        Save Changes
                    </button>
                </form>
            </div>

            <!-- Change Password -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Change Password</h3>
                <form action="{{ route('pastor.settings.password') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Current Password</label>
                        <input type="password" name="current_password" required class="w-full border border-gray-300 rounded-lg px-4 py-3">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">New Password</label>
                        <input type="password" name="new_password" required minlength="8" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" required minlength="8" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                    </div>
                    <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700">
                        Update Password
                    </button>
                </form>
            </div>
        </div>

        <!-- Sidebar Settings -->
        <div class="space-y-6">
            <!-- Notification Preferences -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Notifications</h3>
                <form id="notificationForm">
                    <div class="space-y-4">
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-gray-700">Email Notifications</span>
                            <input type="checkbox" class="toggle" id="emailNotif" checked onchange="saveNotificationPreference('email', this.checked)">
                        </label>
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-gray-700">SMS Notifications</span>
                            <input type="checkbox" class="toggle" id="smsNotif" checked onchange="saveNotificationPreference('sms', this.checked)">
                        </label>
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-gray-700">Prayer Request Alerts</span>
                            <input type="checkbox" class="toggle" id="prayerNotif" checked onchange="saveNotificationPreference('prayer', this.checked)">
                        </label>
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-gray-700">Event Reminders</span>
                            <input type="checkbox" class="toggle" id="eventNotif" checked onchange="saveNotificationPreference('event', this.checked)">
                        </label>
                    </div>
                </form>
            </div>

            <!-- Theme Settings -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Theme</h3>
                <div class="space-y-3">
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="theme" value="light" class="mr-3" onchange="changeTheme('light')">
                        <span class="text-gray-700">💡 Light Mode</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="theme" value="dark" class="mr-3" checked onchange="changeTheme('dark')">
                        <span class="text-gray-700">🌙 Dark Mode</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="theme" value="auto" class="mr-3" onchange="changeTheme('auto')">
                        <span class="text-gray-700">🔄 Auto</span>
                    </label>
                </div>
            </div>

            <script>
            // Load saved theme on page load
            document.addEventListener('DOMContentLoaded', function() {
                const savedTheme = localStorage.getItem('theme') || 'dark';
                const themeRadio = document.querySelector(`input[name="theme"][value="${savedTheme}"]`);
                if (themeRadio) {
                    themeRadio.checked = true;
                }
                applyTheme(savedTheme);
                
                // Load notification preferences
                loadNotificationPreferences();
            });

            function changeTheme(theme) {
                // Save to localStorage
                localStorage.setItem('theme', theme);
                
                // Apply theme immediately
                applyTheme(theme);
                
                // Show success notification
                showNotification('✓ Theme changed to ' + theme + ' mode!', 'success');
                
                // Trigger storage event for other tabs/pages
                window.dispatchEvent(new StorageEvent('storage', {
                    key: 'theme',
                    newValue: theme
                }));
            }

            function applyTheme(theme) {
                const body = document.body;
                
                // Remove all theme classes
                body.classList.remove('theme-light', 'theme-dark');
                
                // Determine which theme to apply
                let appliedTheme = theme;
                if (theme === 'auto') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    appliedTheme = prefersDark ? 'dark' : 'light';
                }
                
                // Add the appropriate theme class
                if (appliedTheme === 'light') {
                    body.classList.add('theme-light');
                } else {
                    body.classList.add('theme-dark');
                }
            }

            function saveNotificationPreference(type, enabled) {
                const prefs = JSON.parse(localStorage.getItem('notificationPreferences') || '{}');
                prefs[type] = enabled;
                localStorage.setItem('notificationPreferences', JSON.stringify(prefs));
                showNotification(`${type.charAt(0).toUpperCase() + type.slice(1)} notifications ${enabled ? 'enabled' : 'disabled'}!`, 'success');
            }

            function loadNotificationPreferences() {
                const prefs = JSON.parse(localStorage.getItem('notificationPreferences') || '{}');
                if (prefs.email !== undefined) document.getElementById('emailNotif').checked = prefs.email;
                if (prefs.sms !== undefined) document.getElementById('smsNotif').checked = prefs.sms;
                if (prefs.prayer !== undefined) document.getElementById('prayerNotif').checked = prefs.prayer;
                if (prefs.event !== undefined) document.getElementById('eventNotif').checked = prefs.event;
            }

            function showNotification(message, type) {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${type === 'success' ? 'bg-green-500' : 'bg-blue-500'} text-white`;
                notification.textContent = message;
                document.body.appendChild(notification);
                setTimeout(() => notification.remove(), 3000);
            }
            </script>

            <!-- Account Info -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white">
                <h3 class="text-xl font-bold mb-4">Account Status</h3>
                <div class="space-y-2 text-sm">
                    <p><strong>Role:</strong> Pastor</p>
                    <p><strong>Member Since:</strong> {{ $pastor->created_at->format('M Y') }}</p>
                    <p><strong>Last Login:</strong> {{ $pastor->last_login_at ? $pastor->last_login_at->diffForHumans() : 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Separate Photo Upload Form (outside main form) -->
    <form id="photoForm" action="{{ route('pastor.settings.photo') }}" method="POST" enctype="multipart/form-data" style="display:none;">
        @csrf
        <input type="file" id="photoInput" name="photo" accept="image/*" onchange="previewAndUploadPhoto(event)">
    </form>
</div>

<script>
// Photo preview and upload
function previewAndUploadPhoto(event) {
    const file = event.target.files[0];
    if (file) {
        // Validate file type
        if (!file.type.match('image.*')) {
            alert('Please select an image file (JPG, PNG, or GIF)');
            return;
        }
        
        // Validate file size (2MB = 2097152 bytes)
        if (file.size > 2097152) {
            alert('Image size must be less than 2MB');
            return;
        }
        
        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('photoPreview');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
        
        // Submit form
        document.getElementById('photoForm').submit();
    }
}
</script>

<style>
/* Theme styles */
body.theme-light {
    background: #f3f4f6 !important;
}

body.theme-light .bg-white {
    background: white !important;
    color: #1f2937 !important;
}

body.theme-dark {
    /* Already styled by default */
}

/* Toggle switch styling */
.toggle {
    appearance: none;
    width: 48px;
    height: 24px;
    background: #cbd5e0;
    border-radius: 12px;
    position: relative;
    cursor: pointer;
    transition: background 0.3s;
}

.toggle:checked {
    background: #3b82f6;
}

.toggle:before {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: white;
    top: 2px;
    left: 2px;
    transition: transform 0.3s;
}

.toggle:checked:before {
    transform: translateX(24px);
}
</style>
@endsection
