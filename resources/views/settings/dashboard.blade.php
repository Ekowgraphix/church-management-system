@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-indigo-900 p-6">
    <div class="max-w-7xl mx-auto">
        
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-white mb-2">⚙️ System Settings</h1>
                <p class="text-white/80">Intelligent Control Center for Your Church</p>
            </div>
            <button onclick="saveAllSettings()" class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-8 py-3 rounded-xl font-semibold hover:opacity-90 shadow-lg">
                💾 Save All Changes
            </button>
        </div>

        <!-- Main Layout: Sidebar + Content -->
        <div class="grid grid-cols-4 gap-6">
            
            <!-- Sidebar Navigation -->
            <div class="col-span-1 space-y-3">
                <button onclick="showSection('general')" id="tab-general" class="w-full text-left px-6 py-4 rounded-xl bg-white/20 backdrop-blur text-white font-semibold hover:bg-white/30 transition border-l-4 border-blue-500">
                    🏛️ General
                </button>
                <button onclick="showSection('users')" id="tab-users" class="w-full text-left px-6 py-4 rounded-xl bg-white/10 backdrop-blur text-white/80 hover:bg-white/20 transition border-l-4 border-transparent">
                    👥 Users & Roles
                </button>
                <button onclick="showSection('communication')" id="tab-communication" class="w-full text-left px-6 py-4 rounded-xl bg-white/10 backdrop-blur text-white/80 hover:bg-white/20 transition border-l-4 border-transparent">
                    💬 Communication
                </button>
                <button onclick="showSection('finance')" id="tab-finance" class="w-full text-left px-6 py-4 rounded-xl bg-white/10 backdrop-blur text-white/80 hover:bg-white/20 transition border-l-4 border-transparent">
                    💳 Finance
                </button>
                <button onclick="showSection('ai')" id="tab-ai" class="w-full text-left px-6 py-4 rounded-xl bg-white/10 backdrop-blur text-white/80 hover:bg-white/20 transition border-l-4 border-transparent">
                    🧠 AI & Automation
                </button>
                <button onclick="showSection('appearance')" id="tab-appearance" class="w-full text-left px-6 py-4 rounded-xl bg-white/10 backdrop-blur text-white/80 hover:bg-white/20 transition border-l-4 border-transparent">
                    🎨 Appearance
                </button>
                <button onclick="showSection('notifications')" id="tab-notifications" class="w-full text-left px-6 py-4 rounded-xl bg-white/10 backdrop-blur text-white/80 hover:bg-white/20 transition border-l-4 border-transparent">
                    🔔 Notifications
                </button>
                <button onclick="showSection('security')" id="tab-security" class="w-full text-left px-6 py-4 rounded-xl bg-white/10 backdrop-blur text-white/80 hover:bg-white/20 transition border-l-4 border-transparent">
                    🔐 Security
                </button>
                <button onclick="showSection('integrations')" id="tab-integrations" class="w-full text-left px-6 py-4 rounded-xl bg-white/10 backdrop-blur text-white/80 hover:bg-white/20 transition border-l-4 border-transparent">
                    🔄 Integrations
                </button>
                <button onclick="showSection('logs')" id="tab-logs" class="w-full text-left px-6 py-4 rounded-xl bg-white/10 backdrop-blur text-white/80 hover:bg-white/20 transition border-l-4 border-transparent">
                    📊 Audit Logs
                </button>

                <!-- AI Assistant Widget -->
                <div class="mt-6 bg-gradient-to-br from-pink-500 to-purple-600 rounded-2xl p-6 text-white">
                    <h3 class="font-bold mb-2">🤖 Settings Assistant</h3>
                    <p class="text-sm mb-3 opacity-90">Ask me about any setting!</p>
                    <input type="text" id="aiSettingsQuery" placeholder="e.g., How to enable 2FA?" class="w-full bg-white/20 rounded-xl px-4 py-2 text-sm mb-2 text-white placeholder-white/60">
                    <button onclick="askSettingsAI()" class="w-full bg-white text-purple-600 px-4 py-2 rounded-xl text-sm font-semibold">
                        Ask AI
                    </button>
                </div>
            </div>

            <!-- Content Area -->
            <div class="col-span-3">
                
                <!-- SECTION 1: GENERAL (CHURCH PROFILE) -->
                <div id="section-general" class="settings-section">
                    <div class="bg-white/10 backdrop-blur rounded-2xl p-8">
                        <h2 class="text-3xl font-bold text-white mb-6">🏛️ General - Church Profile</h2>
                        
                        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Church Logo -->
                            <div class="mb-6 bg-white/5 rounded-xl p-6">
                                <label class="block text-white font-semibold mb-3">Church Logo</label>
                                <div class="flex items-start space-x-6">
                                    <div class="w-32 h-32 bg-white/10 rounded-xl flex items-center justify-center overflow-hidden">
                                        <img id="logoPreview" src="https://via.placeholder.com/150" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" name="church_logo" accept="image/*" onchange="previewImage(event, 'logoPreview')" class="text-white mb-2">
                                        <p class="text-white/60 text-sm">Recommended: 500x500px, PNG or JPG</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Church Basic Info -->
                            <div class="grid grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="block text-white font-semibold mb-2">Church Name</label>
                                    <input type="text" name="church_name" value="" placeholder="Enter church name" class="w-full px-4 py-3 rounded-xl bg-white/10 text-white border border-white/20 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-white font-semibold mb-2">Motto / Slogan</label>
                                    <input type="text" name="church_motto" value="" placeholder="Enter church motto" class="w-full px-4 py-3 rounded-xl bg-white/10 text-white border border-white/20 focus:border-blue-500">
                                </div>
                            </div>

                            <!-- Contact Info -->
                            <div class="grid grid-cols-3 gap-6 mb-6">
                                <div>
                                    <label class="block text-white font-semibold mb-2">Email</label>
                                    <input type="email" name="church_email" value="" placeholder="church@example.com" class="w-full px-4 py-3 rounded-xl bg-white/10 text-white border border-white/20">
                                </div>
                                <div>
                                    <label class="block text-white font-semibold mb-2">Phone</label>
                                    <input type="tel" name="church_phone" value="" placeholder="+233 123 456 789" class="w-full px-4 py-3 rounded-xl bg-white/10 text-white border border-white/20">
                                </div>
                                <div>
                                    <label class="block text-white font-semibold mb-2">WhatsApp</label>
                                    <input type="tel" name="church_whatsapp" value="" placeholder="+233 123 456 789" class="w-full px-4 py-3 rounded-xl bg-white/10 text-white border border-white/20">
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="mb-6">
                                <label class="block text-white font-semibold mb-2">Address</label>
                                <textarea name="church_address" rows="2" placeholder="Enter church address" class="w-full px-4 py-3 rounded-xl bg-white/10 text-white border border-white/20"></textarea>
                            </div>

                            <!-- Social Links -->
                            <div class="grid grid-cols-3 gap-6 mb-6">
                                <div>
                                    <label class="block text-white font-semibold mb-2">Facebook</label>
                                    <input type="url" name="social_facebook" value="" placeholder="https://facebook.com/yourchurch" class="w-full px-4 py-3 rounded-xl bg-white/10 text-white border border-white/20">
                                </div>
                                <div>
                                    <label class="block text-white font-semibold mb-2">YouTube</label>
                                    <input type="url" name="social_youtube" value="" placeholder="https://youtube.com/@yourchurch" class="w-full px-4 py-3 rounded-xl bg-white/10 text-white border border-white/20">
                                </div>
                                <div>
                                    <label class="block text-white font-semibold mb-2">Instagram</label>
                                    <input type="url" name="social_instagram" value="" placeholder="https://instagram.com/yourchurch" class="w-full px-4 py-3 rounded-xl bg-white/10 text-white border border-white/20">
                                </div>
                            </div>

                            <!-- Vision & Mission -->
                            <div class="grid grid-cols-2 gap-6 mb-6">
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <label class="block text-white font-semibold">Vision Statement</label>
                                        <button type="button" onclick="generateMission('vision')" class="text-sm bg-purple-500 text-white px-3 py-1 rounded-lg">
                                            🤖 AI Generate
                                        </button>
                                    </div>
                                    <textarea name="church_vision" rows="4" placeholder="Enter your church vision statement..." class="w-full px-4 py-3 rounded-xl bg-white/10 text-white border border-white/20"></textarea>
                                </div>
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <label class="block text-white font-semibold">Mission Statement</label>
                                        <button type="button" onclick="generateMission('mission')" class="text-sm bg-purple-500 text-white px-3 py-1 rounded-lg">
                                            🤖 AI Generate
                                        </button>
                                    </div>
                                    <textarea name="church_mission" rows="4" placeholder="Enter your church mission statement..." class="w-full px-4 py-3 rounded-xl bg-white/10 text-white border border-white/20"></textarea>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-3">
                                <button type="submit" class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-4 rounded-xl font-semibold hover:opacity-90">
                                    💾 Save General Settings
                                </button>
                                <button type="button" onclick="generateQRCode()" class="bg-purple-600 text-white px-6 py-4 rounded-xl font-semibold hover:opacity-90">
                                    📱 Generate Church QR
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- SECTION 2: USERS & ROLES -->
                <div id="section-users" class="settings-section hidden">
                    <div class="bg-white/10 backdrop-blur rounded-2xl p-8">
                        <h2 class="text-3xl font-bold text-white mb-6">👥 User & Role Management</h2>
                        
                        <div class="bg-white/5 rounded-xl p-6 mb-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-xl font-bold text-white">System Users</h3>
                                <button onclick="showAddUserModal()" class="bg-green-600 text-white px-4 py-2 rounded-lg">
                                    + Add User
                                </button>
                            </div>
                            
                            <!-- Users Table -->
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="border-b border-white/20">
                                            <th class="text-left text-white py-3">Name</th>
                                            <th class="text-left text-white py-3">Email</th>
                                            <th class="text-left text-white py-3">Role</th>
                                            <th class="text-left text-white py-3">Last Login</th>
                                            <th class="text-left text-white py-3">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users ?? [] as $user)
                                        <tr class="border-b border-white/10 hover:bg-white/5">
                                            <td class="py-3 text-white">{{ $user->name }}</td>
                                            <td class="py-3 text-white/80">{{ $user->email }}</td>
                                            <td class="py-3">
                                                <span class="px-3 py-1 bg-blue-500/20 text-blue-300 rounded-full text-sm">
                                                    Admin
                                                </span>
                                            </td>
                                            <td class="py-3 text-white/60 text-sm">{{ $user->last_login_at?->format('M d, Y') ?? 'Never' }}</td>
                                            <td class="py-3">
                                                <button class="text-blue-400 hover:text-blue-300 mr-3">Edit</button>
                                                <button class="text-red-400 hover:text-red-300">Disable</button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Role Management -->
                        <div class="bg-white/5 rounded-xl p-6">
                            <h3 class="text-xl font-bold text-white mb-4">Role Permissions Matrix</h3>
                            <p class="text-white/60 text-sm mb-4">Control access for each role across different modules</p>
                            
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-white/20">
                                            <th class="text-left text-white py-2">Module</th>
                                            <th class="text-center text-white py-2">Admin</th>
                                            <th class="text-center text-white py-2">Finance</th>
                                            <th class="text-center text-white py-2">Secretary</th>
                                            <th class="text-center text-white py-2">Media</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="border-b border-white/10">
                                            <td class="py-3 text-white">Finance</td>
                                            <td class="text-center"><input type="checkbox" checked class="rounded"></td>
                                            <td class="text-center"><input type="checkbox" checked class="rounded"></td>
                                            <td class="text-center"><input type="checkbox" class="rounded"></td>
                                            <td class="text-center"><input type="checkbox" class="rounded"></td>
                                        </tr>
                                        <tr class="border-b border-white/10">
                                            <td class="py-3 text-white">Members</td>
                                            <td class="text-center"><input type="checkbox" checked class="rounded"></td>
                                            <td class="text-center"><input type="checkbox" class="rounded"></td>
                                            <td class="text-center"><input type="checkbox" checked class="rounded"></td>
                                            <td class="text-center"><input type="checkbox" class="rounded"></td>
                                        </tr>
                                        <tr class="border-b border-white/10">
                                            <td class="py-3 text-white">Media</td>
                                            <td class="text-center"><input type="checkbox" checked class="rounded"></td>
                                            <td class="text-center"><input type="checkbox" class="rounded"></td>
                                            <td class="text-center"><input type="checkbox" class="rounded"></td>
                                            <td class="text-center"><input type="checkbox" checked class="rounded"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: COMMUNICATION (Continuing...) -->
                <div id="section-communication" class="settings-section hidden">
                    <div class="bg-white/10 backdrop-blur rounded-2xl p-8">
                        <h2 class="text-3xl font-bold text-white mb-6">💬 Communication Settings</h2>
                        
                        <!-- Email Configuration -->
                        <div class="bg-white/5 rounded-xl p-6 mb-6">
                            <h3 class="text-xl font-bold text-white mb-4">Email Setup</h3>
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-white font-semibold mb-2">Email Provider</label>
                                    <select name="email_provider" class="w-full px-4 py-3 rounded-xl bg-white/10 text-white border border-white/20">
                                        <option>SMTP</option>
                                        <option>SendGrid</option>
                                        <option>Gmail API</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-white font-semibold mb-2">API Key / Password</label>
                                    <input type="password" name="email_api_key" class="w-full px-4 py-3 rounded-xl bg-white/10 text-white border border-white/20">
                                </div>
                            </div>
                            <button type="button" onclick="testEmail()" class="mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg">
                                📧 Send Test Email
                            </button>
                        </div>

                        <!-- SMS Configuration -->
                        <div class="bg-white/5 rounded-xl p-6">
                            <h3 class="text-xl font-bold text-white mb-4">SMS Setup</h3>
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-white font-semibold mb-2">SMS Provider</label>
                                    <select name="sms_provider" class="w-full px-4 py-3 rounded-xl bg-white/10 text-white border border-white/20">
                                        <option>Twilio</option>
                                        <option>Africastalking</option>
                                        <option>Hubtel</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-white font-semibold mb-2">API Key</label>
                                    <input type="password" name="sms_api_key" class="w-full px-4 py-3 rounded-xl bg-white/10 text-white border border-white/20">
                                </div>
                            </div>
                            <button type="button" onclick="testSMS()" class="mt-4 bg-green-600 text-white px-6 py-2 rounded-lg">
                                📱 Send Test SMS
                            </button>
                        </div>
                    </div>
                </div>

                <!-- SECTION 4: FINANCE SETTINGS -->
                <div id="section-finance" class="settings-section hidden">
                    <div class="bg-white/10 backdrop-blur rounded-2xl p-8">
                        <h2 class="text-3xl font-bold text-white mb-6">💳 Payment & Finance Settings</h2>
                        
                        <div class="bg-white/5 rounded-xl p-6 mb-6">
                            <h3 class="text-xl font-bold text-white mb-4">Financial Configuration</h3>
                            <div class="grid grid-cols-2 gap-6 mb-4">
                                <div>
                                    <label class="block text-white font-semibold mb-2">Default Currency</label>
                                    <select name="default_currency" class="w-full px-4 py-3 rounded-xl bg-white/10 text-white border border-white/20">
                                        <option>GHS - Ghana Cedis</option>
                                        <option>USD - US Dollar</option>
                                        <option>EUR - Euro</option>
                                        <option>GBP - British Pound</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-white font-semibold mb-2">Financial Year Start</label>
                                    <select name="financial_year" class="w-full px-4 py-3 rounded-xl bg-white/10 text-white border border-white/20">
                                        <option>January</option>
                                        <option>April</option>
                                        <option>July</option>
                                        <option>October</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white/5 rounded-xl p-6 mb-6">
                            <h3 class="text-xl font-bold text-white mb-4">Payment Gateways</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 bg-white/5 rounded-lg">
                                    <div>
                                        <h4 class="text-white font-semibold">Paystack</h4>
                                        <p class="text-white/60 text-sm">Accept card & mobile money payments</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-600 rounded-full peer peer-checked:bg-green-600"></div>
                                    </label>
                                </div>
                                <div class="ml-8">
                                    <label class="block text-white font-semibold mb-2">Paystack Public Key</label>
                                    <input type="text" name="paystack_public" placeholder="pk_..." class="w-full px-4 py-2 rounded-xl bg-white/10 text-white border border-white/20">
                                    <label class="block text-white font-semibold mb-2 mt-3">Paystack Secret Key</label>
                                    <input type="password" name="paystack_secret" placeholder="sk_..." class="w-full px-4 py-2 rounded-xl bg-white/10 text-white border border-white/20">
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-blue-500/20 to-purple-500/20 rounded-xl p-6">
                            <h4 class="text-white font-bold mb-2">💡 AI Finance Monitor</h4>
                            <p class="text-white/80 text-sm">Enable AI to detect unusual spending patterns and alert you automatically</p>
                            <button class="mt-3 bg-purple-600 text-white px-4 py-2 rounded-lg">Enable AI Monitor</button>
                        </div>
                    </div>
                </div>

                <!-- SECTION 5: AI & AUTOMATION -->
                <div id="section-ai" class="settings-section hidden">
                    <div class="bg-white/10 backdrop-blur rounded-2xl p-8">
                        <h2 class="text-3xl font-bold text-white mb-6">🧠 AI & Automation</h2>
                        
                        <div class="bg-white/5 rounded-xl p-6 mb-6">
                            <h3 class="text-xl font-bold text-white mb-4">AI Provider Configuration</h3>
                            <div class="grid grid-cols-2 gap-6 mb-4">
                                <div>
                                    <label class="block text-white font-semibold mb-2">AI Provider</label>
                                    <select name="ai_provider" class="w-full px-4 py-3 rounded-xl bg-white/10 text-white border border-white/20">
                                        <option>OpenAI (ChatGPT)</option>
                                        <option>Google Gemini</option>
                                        <option>Local Model</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-white font-semibold mb-2">AI Role</label>
                                    <select name="ai_role" class="w-full px-4 py-3 rounded-xl bg-white/10 text-white border border-white/20">
                                        <option>Financial Analyst</option>
                                        <option>Spiritual Advisor</option>
                                        <option>Admin Assistant</option>
                                        <option>General Purpose</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-white font-semibold mb-2">API Key</label>
                                <input type="password" name="ai_api_key" placeholder="sk-..." class="w-full px-4 py-3 rounded-xl bg-white/10 text-white border border-white/20">
                            </div>
                            <button onclick="testAI()" class="bg-green-600 text-white px-6 py-2 rounded-lg">🤖 Test AI Connection</button>
                        </div>

                        <div class="bg-white/5 rounded-xl p-6">
                            <h3 class="text-xl font-bold text-white mb-4">Automation Settings</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                                    <span class="text-white">Weekly Financial Summaries</span>
                                    <input type="checkbox" checked class="rounded">
                                </div>
                                <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                                    <span class="text-white">Monthly Attendance Reports</span>
                                    <input type="checkbox" checked class="rounded">
                                </div>
                                <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                                    <span class="text-white">Auto Follow-up Reminders</span>
                                    <input type="checkbox" class="rounded">
                                </div>
                                <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                                    <span class="text-white">Birthday Wishes (Auto)</span>
                                    <input type="checkbox" checked class="rounded">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 6: APPEARANCE -->
                <div id="section-appearance" class="settings-section hidden">
                    <div class="bg-white/10 backdrop-blur rounded-2xl p-8">
                        <h2 class="text-3xl font-bold text-white mb-6">🎨 Appearance & Theme</h2>
                        
                        <div class="bg-white/5 rounded-xl p-6 mb-6">
                            <h3 class="text-xl font-bold text-white mb-4">Logo & Branding</h3>
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-white font-semibold mb-2">System Logo</label>
                                    <div class="w-32 h-32 bg-white/10 rounded-xl mb-3 flex items-center justify-center overflow-hidden">
                                        <img id="systemLogoPreview" src="https://via.placeholder.com/150" class="w-full h-full object-cover">
                                    </div>
                                    <input type="file" accept="image/*" onchange="previewImage(event, 'systemLogoPreview')" class="text-white text-sm">
                                </div>
                                <div>
                                    <label class="block text-white font-semibold mb-2">Favicon</label>
                                    <div class="w-16 h-16 bg-white/10 rounded-xl mb-3 flex items-center justify-center overflow-hidden">
                                        <img id="faviconPreview" src="https://via.placeholder.com/64" class="w-full h-full object-cover">
                                    </div>
                                    <input type="file" accept="image/*" onchange="previewImage(event, 'faviconPreview')" class="text-white text-sm">
                                </div>
                            </div>
                        </div>

                        <div class="bg-white/5 rounded-xl p-6 mb-6">
                            <h3 class="text-xl font-bold text-white mb-4">Theme Settings</h3>
                            <div class="grid grid-cols-3 gap-4 mb-4">
                                <button onclick="selectTheme('light')" class="p-4 bg-white rounded-xl text-gray-900 font-semibold hover:scale-105 transition">
                                    ☀️ Light Mode
                                </button>
                                <button onclick="selectTheme('dark')" class="p-4 bg-gray-900 rounded-xl text-white font-semibold border-2 border-blue-500 hover:scale-105 transition">
                                    🌙 Dark Mode
                                </button>
                                <button onclick="selectTheme('auto')" class="p-4 bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl text-white font-semibold hover:scale-105 transition">
                                    🔄 Auto
                                </button>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-white font-semibold mb-2">Accent Color</label>
                                    <input type="color" value="#6366f1" class="w-full h-12 rounded-xl cursor-pointer">
                                </div>
                                <div>
                                    <label class="block text-white font-semibold mb-2">Font Family</label>
                                    <select class="w-full px-4 py-3 rounded-xl bg-white/10 text-white border border-white/20">
                                        <option>Inter (Default)</option>
                                        <option>Roboto</option>
                                        <option>Poppins</option>
                                        <option>Open Sans</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-purple-500/20 to-pink-500/20 rounded-xl p-6">
                            <h4 class="text-white font-bold mb-2">🤖 AI Theme Generator</h4>
                            <p class="text-white/80 text-sm mb-3">Let AI create a custom theme for special occasions</p>
                            <input type="text" placeholder="e.g., 'Christmas theme with gold and red'" class="w-full px-4 py-2 rounded-xl bg-white/10 text-white mb-3">
                            <button class="bg-purple-600 text-white px-6 py-2 rounded-lg">Generate Theme</button>
                        </div>
                    </div>
                </div>

                <!-- SECTION 7: NOTIFICATIONS -->
                <div id="section-notifications" class="settings-section hidden">
                    <div class="bg-white/10 backdrop-blur rounded-2xl p-8">
                        <h2 class="text-3xl font-bold text-white mb-6">🔔 Notifications & Reminders</h2>
                        
                        <div class="bg-white/5 rounded-xl p-6 mb-6">
                            <h3 class="text-xl font-bold text-white mb-4">Notification Channels</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 bg-white/5 rounded-lg">
                                    <div>
                                        <h4 class="text-white font-semibold">In-App Notifications</h4>
                                        <p class="text-white/60 text-sm">Show notifications within the dashboard</p>
                                    </div>
                                    <input type="checkbox" checked class="rounded w-5 h-5">
                                </div>
                                <div class="flex items-center justify-between p-4 bg-white/5 rounded-lg">
                                    <div>
                                        <h4 class="text-white font-semibold">Email Alerts</h4>
                                        <p class="text-white/60 text-sm">Send important alerts via email</p>
                                    </div>
                                    <input type="checkbox" checked class="rounded w-5 h-5">
                                </div>
                                <div class="flex items-center justify-between p-4 bg-white/5 rounded-lg">
                                    <div>
                                        <h4 class="text-white font-semibold">SMS Alerts</h4>
                                        <p class="text-white/60 text-sm">Critical notifications via SMS</p>
                                    </div>
                                    <input type="checkbox" class="rounded w-5 h-5">
                                </div>
                            </div>
                        </div>

                        <div class="bg-white/5 rounded-xl p-6">
                            <h3 class="text-xl font-bold text-white mb-4">Trigger Configuration</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                                    <span class="text-white">Birthday Reminders (Day Before)</span>
                                    <input type="checkbox" checked class="rounded">
                                </div>
                                <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                                    <span class="text-white">Follow-up Pending Alerts</span>
                                    <input type="checkbox" checked class="rounded">
                                </div>
                                <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                                    <span class="text-white">Finance Threshold Alerts</span>
                                    <input type="checkbox" class="rounded">
                                </div>
                                <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                                    <span class="text-white">Weekly Attendance Summary</span>
                                    <input type="checkbox" checked class="rounded">
                                </div>
                            </div>
                            <button onclick="testNotification()" class="mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg">
                                🔔 Send Test Notification
                            </button>
                        </div>
                    </div>
                </div>

                <!-- SECTION 8: SECURITY -->
                <div id="section-security" class="settings-section hidden">
                    <div class="bg-white/10 backdrop-blur rounded-2xl p-8">
                        <h2 class="text-3xl font-bold text-white mb-6">🔐 Security & Backups</h2>
                        
                        <div class="bg-white/5 rounded-xl p-6 mb-6">
                            <h3 class="text-xl font-bold text-white mb-4">Security Settings</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-white font-semibold mb-2">Minimum Password Length</label>
                                    <input type="number" value="8" min="6" max="20" class="w-full px-4 py-3 rounded-xl bg-white/10 text-white border border-white/20">
                                </div>
                                <div class="flex items-center justify-between p-4 bg-white/5 rounded-lg">
                                    <div>
                                        <h4 class="text-white font-semibold">Enable Two-Factor Authentication</h4>
                                        <p class="text-white/60 text-sm">Require 2FA for all admin users</p>
                                    </div>
                                    <input type="checkbox" class="rounded w-5 h-5">
                                </div>
                                <div>
                                    <label class="block text-white font-semibold mb-2">Session Timeout (minutes)</label>
                                    <input type="range" min="15" max="120" value="60" class="w-full">
                                    <p class="text-white/60 text-sm">Current: 60 minutes</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white/5 rounded-xl p-6 mb-6">
                            <h3 class="text-xl font-bold text-white mb-4">Database Backups</h3>
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="p-4 bg-gradient-to-br from-blue-500/20 to-cyan-500/20 rounded-xl">
                                    <h4 class="text-white font-bold mb-2">Last Backup</h4>
                                    <p class="text-white/80 text-sm">October 17, 2025 - 6:00 PM</p>
                                    <p class="text-green-400 text-xs mt-1">✓ Successful (2.3 MB)</p>
                                </div>
                                <div class="p-4 bg-gradient-to-br from-purple-500/20 to-pink-500/20 rounded-xl">
                                    <h4 class="text-white font-bold mb-2">Auto-Backup</h4>
                                    <p class="text-white/80 text-sm">Daily at 2:00 AM</p>
                                    <p class="text-blue-400 text-xs mt-1">⚡ Enabled</p>
                                </div>
                            </div>
                            <div class="flex space-x-3">
                                <button onclick="backupNow()" class="flex-1 bg-green-600 text-white px-6 py-3 rounded-xl font-semibold">
                                    💾 Backup Now
                                </button>
                                <button onclick="restoreBackup()" class="flex-1 bg-orange-600 text-white px-6 py-3 rounded-xl font-semibold">
                                    ↻ Restore Backup
                                </button>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-red-500/20 to-orange-500/20 rounded-xl p-6">
                            <h4 class="text-white font-bold mb-2">⚠️ Emergency Actions</h4>
                            <button class="mt-2 bg-red-600 text-white px-6 py-2 rounded-lg">Logout All Devices</button>
                        </div>
                    </div>
                </div>

                <!-- SECTION 9: INTEGRATIONS -->
                <div id="section-integrations" class="settings-section hidden">
                    <div class="bg-white/10 backdrop-blur rounded-2xl p-8">
                        <h2 class="text-3xl font-bold text-white mb-6">🔄 System & Integrations</h2>
                        
                        <div class="bg-white/5 rounded-xl p-6 mb-6">
                            <h3 class="text-xl font-bold text-white mb-4">System Information</h3>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="p-4 bg-white/5 rounded-xl">
                                    <p class="text-white/60 text-sm mb-1">Version</p>
                                    <p class="text-white font-bold text-lg">v3.2.1</p>
                                </div>
                                <div class="p-4 bg-white/5 rounded-xl">
                                    <p class="text-white/60 text-sm mb-1">Last Updated</p>
                                    <p class="text-white font-bold text-sm">Oct 15, 2025</p>
                                </div>
                                <div class="p-4 bg-white/5 rounded-xl">
                                    <p class="text-white/60 text-sm mb-1">Status</p>
                                    <p class="text-green-400 font-bold">✓ Up to Date</p>
                                </div>
                            </div>
                            <button class="mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg">Check for Updates</button>
                        </div>

                        <div class="bg-white/5 rounded-xl p-6">
                            <h3 class="text-xl font-bold text-white mb-4">External Integrations</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-4 bg-white/5 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <span class="text-2xl">📅</span>
                                        <div>
                                            <h4 class="text-white font-semibold">Google Calendar</h4>
                                            <p class="text-green-400 text-sm">● Connected</p>
                                        </div>
                                    </div>
                                    <button class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm">Disconnect</button>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-white/5 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <span class="text-2xl">💬</span>
                                        <div>
                                            <h4 class="text-white font-semibold">WhatsApp API</h4>
                                            <p class="text-gray-400 text-sm">● Not Connected</p>
                                        </div>
                                    </div>
                                    <button class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm">Connect</button>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-white/5 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <span class="text-2xl">☁️</span>
                                        <div>
                                            <h4 class="text-white font-semibold">Google Drive (Backups)</h4>
                                            <p class="text-gray-400 text-sm">● Not Connected</p>
                                        </div>
                                    </div>
                                    <button class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm">Connect</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 10: AUDIT LOGS -->
                <div id="section-logs" class="settings-section hidden">
                    <div class="bg-white/10 backdrop-blur rounded-2xl p-8">
                        <h2 class="text-3xl font-bold text-white mb-6">📊 Audit Logs & System Health</h2>
                        
                        <div class="bg-gradient-to-br from-purple-500/20 to-pink-500/20 rounded-xl p-6 mb-6">
                            <h3 class="text-xl font-bold text-white mb-3">🤖 AI Log Summarizer</h3>
                            <p class="text-white/80 text-sm mb-3">Ask AI to analyze and summarize your activity logs</p>
                            <input type="text" placeholder="e.g., 'Summarize last 7 days activity'" class="w-full px-4 py-3 rounded-xl bg-white/10 text-white mb-3">
                            <button class="bg-purple-600 text-white px-6 py-2 rounded-lg">Analyze Logs</button>
                        </div>

                        <div class="bg-white/5 rounded-xl p-6 mb-6">
                            <h3 class="text-xl font-bold text-white mb-4">Recent Activity</h3>
                            <div class="space-y-3">
                                <div class="flex items-start space-x-3 p-3 bg-white/5 rounded-lg">
                                    <span class="text-blue-400 text-xl">👤</span>
                                    <div class="flex-1">
                                        <p class="text-white font-semibold">John Doe updated Finance Settings</p>
                                        <p class="text-white/60 text-sm">2 hours ago • IP: 192.168.1.100</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3 p-3 bg-white/5 rounded-lg">
                                    <span class="text-green-400 text-xl">✓</span>
                                    <div class="flex-1">
                                        <p class="text-white font-semibold">System Backup Completed</p>
                                        <p class="text-white/60 text-sm">5 hours ago • Size: 2.3 MB</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3 p-3 bg-white/5 rounded-lg">
                                    <span class="text-yellow-400 text-xl">⚠️</span>
                                    <div class="flex-1">
                                        <p class="text-white font-semibold">Failed login attempt detected</p>
                                        <p class="text-white/60 text-sm">1 day ago • IP: 41.23.45.67</p>
                                    </div>
                                </div>
                            </div>
                            <button class="mt-4 w-full bg-blue-600 text-white px-6 py-2 rounded-lg">View Full Log</button>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div class="bg-white/5 rounded-xl p-4">
                                <h4 class="text-white/60 text-sm mb-2">Total Logins Today</h4>
                                <p class="text-white font-bold text-2xl">24</p>
                            </div>
                            <div class="bg-white/5 rounded-xl p-4">
                                <h4 class="text-white/60 text-sm mb-2">Active Users</h4>
                                <p class="text-green-400 font-bold text-2xl">8</p>
                            </div>
                            <div class="bg-white/5 rounded-xl p-4">
                                <h4 class="text-white/60 text-sm mb-2">System Health</h4>
                                <p class="text-green-400 font-bold text-2xl">98%</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
// Tab Switching
function showSection(sectionName) {
    // Hide all sections
    document.querySelectorAll('.settings-section').forEach(section => {
        section.classList.add('hidden');
    });
    
    // Remove active state from all tabs
    document.querySelectorAll('[id^="tab-"]').forEach(tab => {
        tab.classList.remove('bg-white/20', 'border-blue-500');
        tab.classList.add('bg-white/10', 'border-transparent', 'text-white/80');
    });
    
    // Show selected section
    document.getElementById('section-' + sectionName).classList.remove('hidden');
    
    // Activate selected tab
    const activeTab = document.getElementById('tab-' + sectionName);
    activeTab.classList.add('bg-white/20', 'border-blue-500', 'text-white');
    activeTab.classList.remove('bg-white/10', 'border-transparent', 'text-white/80');
}

// Image Preview
function previewImage(event, targetId) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(targetId).src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}

// AI Functions
function generateMission(type) {
    alert('Generating ' + type + ' statement with AI...');
    // Integrate with AI API
}

function askSettingsAI() {
    const query = document.getElementById('aiSettingsQuery').value;
    alert('AI Response for: ' + query);
}

function generateQRCode() {
    alert('Generating Church QR Code...');
}

function testEmail() {
    alert('Sending test email...');
}

function testSMS() {
    alert('Sending test SMS...');
}

function saveAllSettings() {
    alert('Saving all settings...');
}

function showAddUserModal() {
    alert('Opening Add User modal...');
}

// Additional functions for new sections
function testAI() {
    alert('Testing AI connection...');
}

function selectTheme(theme) {
    alert('Theme changed to: ' + theme);
}

function testNotification() {
    alert('Sending test notification...');
}

function backupNow() {
    if(confirm('Start database backup now?')) {
        alert('Backup started...');
    }
}

function restoreBackup() {
    if(confirm('This will restore from the last backup. Continue?')) {
        alert('Restoring backup...');
    }
}
</script>
@endsection
