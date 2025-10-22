@extends('layouts.organization')

@section('content')
<div>
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">⚙️ Settings</h1>
                <p class="text-gray-600">Manage organization profile and preferences</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Settings -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Organization Profile -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Organization Profile</h3>
                <form>
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">Organization Logo</label>
                        <div class="flex items-center space-x-6">
                            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-network-wired text-green-600 text-3xl"></i>
                            </div>
                            <button type="button" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
                                Upload Logo
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Organization Name</label>
                            <input type="text" value="Light Global Ministries" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Contact Email</label>
                            <input type="email" value="contact@lightglobal.org" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Phone</label>
                            <input type="tel" value="+233 24 123 4567" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Website</label>
                            <input type="url" value="https://lightglobal.org" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">Headquarters Address</label>
                        <textarea rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-3">123 Main Street, Accra, Ghana</textarea>
                    </div>

                    <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700">
                        Save Changes
                    </button>
                </form>
            </div>

            <!-- API Integrations -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">API Integrations</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-semibold text-gray-800">SMS Gateway</p>
                            <p class="text-sm text-gray-600">Twilio / Africa's Talking</p>
                        </div>
                        <button class="text-blue-600 hover:text-blue-800 font-semibold">Configure</button>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-semibold text-gray-800">AI Service</p>
                            <p class="text-sm text-gray-600">OpenAI / Gemini API</p>
                        </div>
                        <button class="text-blue-600 hover:text-blue-800 font-semibold">Configure</button>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-semibold text-gray-800">Payment Gateway</p>
                            <p class="text-sm text-gray-600">Paystack / Flutterwave</p>
                        </div>
                        <button class="text-blue-600 hover:text-blue-800 font-semibold">Configure</button>
                    </div>
                </div>
            </div>

            <!-- Access Control -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Access Control</h3>
                <p class="text-gray-600 mb-4">Create and manage custom roles across the organization</p>
                <button class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>Create Custom Role
                </button>
            </div>
        </div>

        <!-- Sidebar Settings -->
        <div class="space-y-6">
            <!-- Theme Settings -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Theme</h3>
                <div class="space-y-3">
                    <label class="flex items-center">
                        <input type="radio" name="theme" class="mr-3" checked>
                        <span class="text-gray-700">Light Mode</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="theme" class="mr-3">
                        <span class="text-gray-700">Dark Mode</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="theme" class="mr-3">
                        <span class="text-gray-700">Auto</span>
                    </label>
                </div>
            </div>

            <!-- Data Backup -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Data Management</h3>
                <button class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 mb-3">
                    <i class="fas fa-download mr-2"></i>Backup Data
                </button>
                <button class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700">
                    <i class="fas fa-upload mr-2"></i>Restore Data
                </button>
            </div>

            <!-- Audit Log -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Audit Log</h3>
                <p class="text-sm text-gray-600 mb-4">View all system activity and changes</p>
                <button class="w-full bg-gray-600 text-white py-3 rounded-lg font-semibold hover:bg-gray-700">
                    <i class="fas fa-history mr-2"></i>View Audit Log
                </button>
            </div>

            <!-- Organization Stats -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-6 text-white">
                <h3 class="text-xl font-bold mb-4">Organization Status</h3>
                <div class="space-y-2 text-sm">
                    <p><strong>Total Branches:</strong> 12</p>
                    <p><strong>Total Members:</strong> 5,400</p>
                    <p><strong>Established:</strong> April 2023</p>
                    <p><strong>Health Index:</strong> 92%</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Upload Logo
document.querySelectorAll('button').forEach(btn => {
    if(btn.textContent.includes('Upload Logo')) {
        btn.addEventListener('click', function() {
            alert('📸 Upload Organization Logo\n\nOpening file picker...');
            // TODO: Open file upload dialog
        });
    }
});

// Save Changes Form
document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();
    if(confirm('💾 Save organization settings?')) {
        alert('✅ Settings saved successfully!');
        // TODO: Submit form data to backend
    }
});

// Configure Integrations
document.querySelectorAll('button').forEach(btn => {
    if(btn.textContent.includes('Configure')) {
        btn.addEventListener('click', function() {
            const service = this.closest('.bg-gray-50').querySelector('.font-semibold').textContent;
            alert(`⚙️ Configure ${service}\n\nOpening configuration panel...`);
            // TODO: Open integration settings
        });
    }
});

// Create Custom Role
document.querySelectorAll('button').forEach(btn => {
    if(btn.textContent.includes('Create Custom Role')) {
        btn.addEventListener('click', function() {
            alert('👥 Create Custom Role\n\nDefine permissions for a new role...');
            // TODO: Open role creation form
        });
    }
});

// Backup Data
document.querySelectorAll('button').forEach(btn => {
    if(btn.textContent.includes('Backup Data')) {
        btn.addEventListener('click', function() {
            if(confirm('💾 Create full backup of all organization data?')) {
                alert('⏳ Creating backup...\n\nThis may take a few minutes.');
                // TODO: Trigger backup process
            }
        });
    }
});

// Restore Data
document.querySelectorAll('button').forEach(btn => {
    if(btn.textContent.includes('Restore Data')) {
        btn.addEventListener('click', function() {
            if(confirm('⚠️ WARNING: Restore data from backup?\n\nThis will overwrite current data!')) {
                alert('⏳ Restoring data...\n\nPlease wait.');
                // TODO: Trigger restore process
            }
        });
    }
});

// View Audit Log
document.querySelectorAll('button').forEach(btn => {
    if(btn.textContent.includes('View Audit Log')) {
        btn.addEventListener('click', function() {
            alert('📋 Audit Log\n\nShowing system activity history...');
            // TODO: Open audit log viewer
        });
    }
});
</script>
@endsection
