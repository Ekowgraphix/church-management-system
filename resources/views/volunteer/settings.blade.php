@extends('layouts.volunteer')

@section('content')
<div>
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <h1 class="text-3xl font-bold text-gray-800">⚙️ Settings</h1>
        <p class="text-gray-600">Manage your volunteer profile and preferences</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Settings -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Profile Information -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Profile Information</h3>
                <form id="profileForm" onsubmit="saveProfile(event)">
                    <div class="flex items-center space-x-6 mb-6">
                        <div class="w-24 h-24 bg-gradient-to-br from-orange-500 to-orange-600 rounded-full flex items-center justify-center text-white font-bold text-3xl">
                            {{ strtoupper(substr($volunteer->name ?? 'V', 0, 1)) }}
                        </div>
                        <div>
                            <button type="button" onclick="uploadProfilePhoto()" class="bg-orange-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-orange-700 mb-2 transition-all">
                                Upload Photo
                            </button>
                            <p class="text-sm text-gray-600">JPG, PNG or GIF (max 2MB)</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Full Name</label>
                            <input type="text" id="fullName" name="name" value="{{ $volunteer->name ?? '' }}" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-orange-500 focus:ring-2 focus:ring-orange-200">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Email</label>
                            <input type="email" id="email" name="email" value="{{ $volunteer->email ?? '' }}" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-orange-500 focus:ring-2 focus:ring-orange-200">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Phone</label>
                            <input type="tel" id="phone" name="phone" value="{{ $volunteer->phone ?? '' }}" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-orange-500 focus:ring-2 focus:ring-orange-200">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Ministry</label>
                            <select id="ministry" name="ministry" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-orange-500 focus:ring-2 focus:ring-orange-200">
                                <option>Ushering Team</option>
                                <option>Media Team</option>
                                <option>Outreach Team</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="bg-orange-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-orange-700 transition-all">
                        Save Changes
                    </button>
                </form>
            </div>

            <!-- Available Days -->
            <div id="availabilitySection" class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Available Days for Serving</h3>
                <div id="daysGrid" class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <label class="flex items-center p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                        <input type="checkbox" class="mr-3" checked>
                        <span class="text-gray-700">Monday</span>
                    </label>
                    <label class="flex items-center p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                        <input type="checkbox" class="mr-3">
                        <span class="text-gray-700">Tuesday</span>
                    </label>
                    <label class="flex items-center p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                        <input type="checkbox" class="mr-3">
                        <span class="text-gray-700">Wednesday</span>
                    </label>
                    <label class="flex items-center p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                        <input type="checkbox" class="mr-3">
                        <span class="text-gray-700">Thursday</span>
                    </label>
                    <label class="flex items-center p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                        <input type="checkbox" class="mr-3">
                        <span class="text-gray-700">Friday</span>
                    </label>
                    <label class="flex items-center p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                        <input type="checkbox" class="mr-3" checked>
                        <span class="text-gray-700">Saturday</span>
                    </label>
                    <label class="flex items-center p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                        <input type="checkbox" class="mr-3" checked>
                        <span class="text-gray-700">Sunday</span>
                    </label>
                </div>
                <button onclick="updateAvailability()" type="button" class="mt-4 bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition-all">
                    Update Availability
                </button>
            </div>

            <!-- Change Password -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Change Password</h3>
                <form id="passwordForm" onsubmit="changePassword(event)">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Current Password</label>
                        <input type="password" id="currentPassword" name="current_password" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">New Password</label>
                        <input type="password" id="newPassword" name="new_password" required minlength="8" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                        <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">Confirm New Password</label>
                        <input type="password" id="confirmPassword" name="confirm_password" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-all">
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
                <div class="space-y-4">
                    <label class="flex items-center justify-between cursor-pointer">
                        <span class="text-gray-700">Email Notifications</span>
                        <input type="checkbox" class="toggle" checked onchange="toggleNotification(this, 'email')">
                    </label>
                    <label class="flex items-center justify-between cursor-pointer">
                        <span class="text-gray-700">SMS Reminders</span>
                        <input type="checkbox" class="toggle" checked onchange="toggleNotification(this, 'sms')">
                    </label>
                    <label class="flex items-center justify-between cursor-pointer">
                        <span class="text-gray-700">Task Alerts</span>
                        <input type="checkbox" class="toggle" checked onchange="toggleNotification(this, 'task')">
                    </label>
                    <label class="flex items-center justify-between cursor-pointer">
                        <span class="text-gray-700">Event Reminders</span>
                        <input type="checkbox" class="toggle" checked onchange="toggleNotification(this, 'event')">
                    </label>
                </div>
            </div>

            <!-- Volunteer Status -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Volunteer Status</h3>
                <div class="mb-4">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" class="mr-3" onchange="pauseVolunteering(this)">
                        <span class="text-gray-700">Temporarily pause volunteer duties</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-2 ml-6">You won't receive new assignments while paused</p>
                </div>
            </div>

            <!-- Volunteering History -->
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl shadow-lg p-6 text-white">
                <h3 class="text-xl font-bold mb-4">Volunteering Stats</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span>Total Hours:</span>
                        <span class="font-bold">45 hrs</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Events Served:</span>
                        <span class="font-bold">12</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Member Since:</span>
                        <span class="font-bold">{{ $volunteer->created_at ? $volunteer->created_at->format('M Y') : 'Jan 2025' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Badges Earned:</span>
                        <span class="font-bold">3</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
console.log('✅ Settings page JavaScript loaded successfully!');

// Upload Profile Photo
function uploadProfilePhoto() {
    try {
        console.log('📸 Upload photo button clicked!');
        
        // Create file input
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/jpeg,image/png,image/gif,image/webp';
        input.style.display = 'none';
        
        console.log('📎 File input created');
        
        // Handle file selection
        input.onchange = function(e) {
            console.log('📂 File selected');
            const file = e.target.files[0];
            
            if (!file) {
                console.log('⚠️ No file selected');
                return;
            }
            
            console.log('📄 File details:', {
                name: file.name,
                size: file.size,
                type: file.type
            });
            
            // Check if it's an image
            if (!file.type.startsWith('image/')) {
                alert('⚠️ Please select an image file (JPG, PNG, GIF, WebP)');
                return;
            }
            
            // Check file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert(`⚠️ File too large!\n\nFile size: ${(file.size / 1024 / 1024).toFixed(2)} MB\nMaximum allowed: 2 MB\n\nPlease choose a smaller image.`);
                return;
            }
            
            // Show preview and confirmation
            const reader = new FileReader();
            
            reader.onerror = function() {
                console.error('❌ Error reading file');
                alert('⚠️ Error reading file. Please try again.');
            };
            
            reader.onload = function(e) {
                console.log('✅ File loaded successfully');
                
                // Show confirmation with file details
                const sizeKB = (file.size / 1024).toFixed(2);
                const message = `📸 Upload this photo?\n\n` +
                               `Filename: ${file.name}\n` +
                               `Size: ${sizeKB} KB\n` +
                               `Type: ${file.type}\n\n` +
                               `Click OK to upload.`;
                
                if (confirm(message)) {
                    console.log('✅ User confirmed upload');
                    alert('✅ Photo uploaded successfully!\n\nYour profile picture has been updated.');
                    
                    // TODO: Upload to server
                    // const formData = new FormData();
                    // formData.append('photo', file);
                    // fetch('/volunteer/settings/upload-photo', {
                    //     method: 'POST',
                    //     headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    //     body: formData
                    // })
                    // .then(response => response.json())
                    // .then(data => {
                    //     if (data.success) {
                    //         location.reload();
                    //     }
                    // });
                } else {
                    console.log('❌ User cancelled upload');
                }
            };
            
            // Read the file
            reader.readAsDataURL(file);
        };
        
        // Add to DOM and trigger click
        document.body.appendChild(input);
        console.log('🖱️ Triggering file picker...');
        input.click();
        
        // Remove from DOM after a delay
        setTimeout(() => {
            document.body.removeChild(input);
            console.log('🧹 Cleaned up file input');
        }, 1000);
        
    } catch (error) {
        console.error('❌ Error in uploadProfilePhoto:', error);
        alert('⚠️ An error occurred:\n\n' + error.message + '\n\nPlease try again or contact support.');
    }
}

// Save Profile
function saveProfile(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    const name = formData.get('name');
    const email = formData.get('email');
    const phone = formData.get('phone');
    const ministry = formData.get('ministry');
    
    // Validate
    if (!name || !email) {
        alert('⚠️ Please fill in all required fields.');
        return;
    }
    
    // Show loading
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
    submitBtn.disabled = true;
    
    // Simulate save
    setTimeout(() => {
        alert('✅ Profile Updated!\n\nYour profile information has been saved successfully.');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        
        // TODO: Send to backend
        // fetch('/volunteer/settings/profile', {
        //     method: 'POST',
        //     headers: {
        //         'Content-Type': 'application/json',
        //         'X-CSRF-TOKEN': '{{ csrf_token() }}'
        //     },
        //     body: JSON.stringify({
        //         name, email, phone, ministry
        //     })
        // });
    }, 1000);
}

// Update Availability
function updateAvailability() {
    // Only select checkboxes from the days grid
    const daysGrid = document.getElementById('daysGrid');
    if (!daysGrid) {
        alert('⚠️ Could not find days grid!');
        return;
    }
    
    const checkboxes = daysGrid.querySelectorAll('input[type="checkbox"]');
    const selectedDays = [];
    
    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            const label = checkbox.closest('label');
            const day = label.querySelector('span').textContent.trim();
            selectedDays.push(day);
        }
    });
    
    if (selectedDays.length === 0) {
        alert('⚠️ Please select at least one day!');
        return;
    }
    
    if (confirm(`📅 Update Availability?\n\nYou selected:\n${selectedDays.join(', ')}\n\nConfirm?`)) {
        alert('✅ Availability Updated!\n\nYour available days have been saved.');
        
        // TODO: Send to backend
        // fetch('/volunteer/settings/availability', {
        //     method: 'POST',
        //     headers: {
        //         'Content-Type': 'application/json',
        //         'X-CSRF-TOKEN': '{{ csrf_token() }}'
        //     },
        //     body: JSON.stringify({ days: selectedDays })
        // });
    }
}

// Change Password
function changePassword(event) {
    event.preventDefault();
    
    const form = event.target;
    const currentPassword = document.getElementById('currentPassword').value;
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    
    // Validate
    if (!currentPassword || !newPassword || !confirmPassword) {
        alert('⚠️ Please fill in all password fields.');
        return;
    }
    
    if (newPassword.length < 8) {
        alert('⚠️ New password must be at least 8 characters long.');
        return;
    }
    
    if (newPassword !== confirmPassword) {
        alert('⚠️ New passwords do not match!');
        return;
    }
    
    // Show loading
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating...';
    submitBtn.disabled = true;
    
    // Simulate password change
    setTimeout(() => {
        alert('✅ Password Updated!\n\nYour password has been changed successfully.');
        form.reset();
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        
        // TODO: Send to backend
        // fetch('/volunteer/settings/password', {
        //     method: 'POST',
        //     headers: {
        //         'Content-Type': 'application/json',
        //         'X-CSRF-TOKEN': '{{ csrf_token() }}'
        //     },
        //     body: JSON.stringify({
        //         current_password: currentPassword,
        //         new_password: newPassword
        //     })
        // });
    }, 1000);
}

// Toggle Notifications
function toggleNotification(checkbox, type) {
    const status = checkbox.checked ? 'enabled' : 'disabled';
    const labels = {
        'email': 'Email Notifications',
        'sms': 'SMS Reminders',
        'task': 'Task Alerts',
        'event': 'Event Reminders'
    };
    
    alert(`✅ ${labels[type]} ${status}!`);
    
    // TODO: Send to backend
    // fetch('/volunteer/settings/notifications', {
    //     method: 'POST',
    //     headers: {
    //         'Content-Type': 'application/json',
    //         'X-CSRF-TOKEN': '{{ csrf_token() }}'
    //     },
    //     body: JSON.stringify({
    //         type: type,
    //         enabled: checkbox.checked
    //     })
    // });
}

// Pause Volunteering
function pauseVolunteering(checkbox) {
    if (checkbox.checked) {
        if (confirm('⚠️ Pause Volunteer Duties?\n\nYou won\'t receive new assignments while paused.\n\nAre you sure?')) {
            alert('📴 Volunteer duties paused.\n\nYou can resume anytime from this page.');
            
            // TODO: Send to backend
            // fetch('/volunteer/settings/pause', {
            //     method: 'POST',
            //     headers: {
            //         'Content-Type': 'application/json',
            //         'X-CSRF-TOKEN': '{{ csrf_token() }}'
            //     },
            //     body: JSON.stringify({ paused: true })
            // });
        } else {
            checkbox.checked = false;
        }
    } else {
        alert('✅ Volunteer duties resumed!\n\nYou will now receive new assignments.');
        
        // TODO: Send to backend
    }
}
</script>
@endsection
