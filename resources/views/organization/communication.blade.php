@extends('layouts.organization')

@section('content')
<div>
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">🗣️ Communication Center</h1>
                <p class="text-gray-600">Broadcast messages and chat with staff</p>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <p class="text-gray-600 text-sm">Messages Sent</p>
            <p class="text-3xl font-bold text-gray-800">1,245</p>
            <p class="text-xs text-gray-500 mt-1">This month</p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <p class="text-gray-600 text-sm">Open Rate</p>
            <p class="text-3xl font-bold text-gray-800">87%</p>
            <p class="text-xs text-gray-500 mt-1">Average</p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <p class="text-gray-600 text-sm">Active Chats</p>
            <p class="text-3xl font-bold text-gray-800">24</p>
            <p class="text-xs text-gray-500 mt-1">Live conversations</p>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <p class="text-gray-600 text-sm">Scheduled</p>
            <p class="text-3xl font-bold text-gray-800">8</p>
            <p class="text-xs text-gray-500 mt-1">Upcoming broadcasts</p>
        </div>
    </div>

    <!-- Broadcast Form -->
    <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
        <h3 class="text-xl font-bold text-gray-800 mb-6">📢 Create Broadcast</h3>
        <form>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Target Audience</label>
                    <select class="w-full border border-gray-300 rounded-lg px-4 py-3">
                        <option>All Pastors</option>
                        <option>All Members</option>
                        <option>All Volunteers</option>
                        <option>All Ministry Leaders</option>
                        <option>Specific Branch</option>
                        <option>Custom Tag</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Delivery Channel</label>
                    <div class="flex space-x-4 pt-3">
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2" checked>
                            <span>Email</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2" checked>
                            <span>SMS</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2" checked>
                            <span>In-App</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Subject</label>
                <input type="text" class="w-full border border-gray-300 rounded-lg px-4 py-3" placeholder="Enter message subject">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Message</label>
                <textarea rows="6" class="w-full border border-gray-300 rounded-lg px-4 py-3" placeholder="Type your message..."></textarea>
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" class="mr-2">
                    <span class="text-gray-700">Schedule for later</span>
                </label>
            </div>

            <div class="flex space-x-4">
                <button type="button" onclick="sendBroadcast()" class="flex-1 bg-green-600 text-white py-4 rounded-lg font-semibold hover:bg-green-700 text-lg transition-all">
                    <i class="fas fa-paper-plane mr-2"></i>Send Now
                </button>
                <button type="button" onclick="scheduleBroadcast()" class="bg-blue-600 text-white px-8 py-4 rounded-lg font-semibold hover:bg-blue-700 transition-all">
                    <i class="fas fa-clock mr-2"></i>Schedule
                </button>
            </div>
        </form>
    </div>

    <!-- Recent Broadcasts & Message Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Broadcasts -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Recent Broadcasts</h3>
            <div class="space-y-3">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-between mb-2">
                        <p class="font-semibold text-gray-800">Christmas Program Announcement</p>
                        <span class="text-xs text-gray-500">2 days ago</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">Sent to: All Branches</p>
                    <div class="flex items-center space-x-4 text-sm">
                        <span class="text-green-600">
                            <i class="fas fa-check-circle mr-1"></i>856 delivered
                        </span>
                        <span class="text-blue-600">
                            <i class="fas fa-eye mr-1"></i>742 opened (87%)
                        </span>
                    </div>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-between mb-2">
                        <p class="font-semibold text-gray-800">Leadership Meeting Reminder</p>
                        <span class="text-xs text-gray-500">5 days ago</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">Sent to: All Pastors</p>
                    <div class="flex items-center space-x-4 text-sm">
                        <span class="text-green-600">
                            <i class="fas fa-check-circle mr-1"></i>12 delivered
                        </span>
                        <span class="text-blue-600">
                            <i class="fas fa-eye mr-1"></i>12 opened (100%)
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Two-way Chat -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">💬 Active Conversations</h3>
            <div class="space-y-3">
                <div onclick="openChat('Pastor Owusu')" class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer transition">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="font-bold text-blue-600">PO</span>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800 text-sm">Pastor Owusu</p>
                            <p class="text-xs text-gray-600">Thanks for the update...</p>
                        </div>
                        <span class="text-xs text-gray-500">1h</span>
                    </div>
                </div>
                <div onclick="openChat('Pastor K. Appiah')" class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer transition">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <span class="font-bold text-green-600">KA</span>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800 text-sm">Pastor K. Appiah</p>
                            <p class="text-xs text-gray-600">The event was successful...</p>
                        </div>
                        <span class="text-xs text-gray-500">3h</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function sendBroadcast() {
    const subject = document.querySelector('input[placeholder*="Subject"]').value;
    const message = document.querySelector('textarea').value;
    const recipients = document.querySelector('select').value;
    
    if(!subject || !message) {
        alert('⚠️ Please fill in subject and message!');
        return;
    }
    
    if(confirm(`📤 Send broadcast to ${recipients}?\n\nSubject: ${subject}`)) {
        alert('✅ Broadcast sent successfully!\n\nYour message has been delivered.');
        // TODO: Send broadcast via API
    }
}

function scheduleBroadcast() {
    alert('🕐 Schedule Broadcast\n\nChoose date and time to send this message later.');
    // TODO: Open date/time picker modal
}

function openChat(name) {
    alert(`💬 Opening chat with ${name}...`);
    // TODO: Open chat window or redirect to messaging page
}
</script>
@endsection
