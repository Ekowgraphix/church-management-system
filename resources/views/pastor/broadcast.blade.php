@extends('layouts.pastor')

@section('content')
<div>
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">✉️ Announcements & Broadcast</h1>
                <p class="text-gray-600">Send messages to church members</p>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
            <p class="font-semibold">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Broadcast Form -->
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <form action="{{ route('pastor.broadcast.send') }}" method="POST" id="broadcastForm">
            @csrf
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Recipient Group</label>
                <select name="recipient_group" required class="w-full border border-gray-300 rounded-lg px-4 py-3">
                    <option value="All Members">All Members</option>
                    <option value="Active Members Only">Active Members Only</option>
                    <option value="Specific Ministry">Specific Ministry</option>
                    <option value="Specific Small Group">Specific Small Group</option>
                    <option value="Custom Selection">Custom Selection</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Delivery Channel</label>
                <div class="flex space-x-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="channels[]" value="sms" class="mr-2" checked>
                        <span>SMS</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="channels[]" value="email" class="mr-2" checked>
                        <span>Email</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="channels[]" value="notification" class="mr-2" checked>
                        <span>In-App Notification</span>
                    </label>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Subject/Title</label>
                <input type="text" name="subject" required maxlength="255" class="w-full border border-gray-300 rounded-lg px-4 py-3" placeholder="Enter announcement title">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Message</label>
                <textarea name="message" id="messageText" rows="6" required maxlength="1000" class="w-full border border-gray-300 rounded-lg px-4 py-3" placeholder="Type your message here..." oninput="updateCharCount()"></textarea>
                <p class="text-sm text-gray-500 mt-2">Character count: <span id="charCount">0</span> / 1000</p>
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="schedule" class="mr-2">
                    <span class="text-gray-700">Schedule for later (Coming soon)</span>
                </label>
            </div>

            <div class="flex space-x-4">
                <button type="submit" class="flex-1 bg-blue-600 text-white py-4 rounded-lg font-semibold hover:bg-blue-700 text-lg">
                    <i class="fas fa-paper-plane mr-2"></i>Send Now
                </button>
                <button type="button" onclick="aiAssist()" class="bg-purple-600 text-white px-8 py-4 rounded-lg font-semibold hover:bg-purple-700">
                    <i class="fas fa-magic mr-2"></i>AI Assist
                </button>
            </div>
        </form>
    </div>

    <script>
    // Update character count
    function updateCharCount() {
        const text = document.getElementById('messageText').value;
        const count = text.length;
        const charCountElement = document.getElementById('charCount');
        charCountElement.textContent = count;
        
        // Change color based on character count
        if (count > 900) {
            charCountElement.style.color = '#dc2626'; // Red
        } else if (count > 700) {
            charCountElement.style.color = '#f59e0b'; // Orange
        } else {
            charCountElement.style.color = '#6b7280'; // Gray
        }
    }

    // AI Assist function with suggestions
    function aiAssist() {
        const suggestions = [
            {
                title: "Sunday Service Reminder",
                subject: "Join Us This Sunday!",
                message: "Dear Church Family,\n\nWe're excited to welcome you this Sunday at 9:00 AM for our worship service. This week, Pastor will be speaking on 'Walking in Faith'.\n\nService Details:\n- Time: 9:00 AM & 11:00 AM\n- Location: Main Sanctuary\n- Kids Ministry Available\n\nSee you there!\nBlessings, Church Leadership"
            },
            {
                title: "Prayer Meeting Announcement",
                subject: "Weekly Prayer Meeting",
                message: "Join us for our weekly prayer meeting this Wednesday at 7:00 PM.\n\nWe'll be praying for:\n- Church families\n- Community needs\n- Ministry outreach\n\nAll are welcome to attend!\nLocation: Fellowship Hall"
            },
            {
                title: "Event Invitation",
                subject: "You're Invited!",
                message: "We're hosting a special community event and would love to see you there!\n\nDate: [Date]\nTime: [Time]\nLocation: [Location]\n\nPlease RSVP by [Date].\nBring your family and friends!\n\nGod bless,\nEvent Team"
            },
            {
                title: "Volunteer Call",
                subject: "Volunteers Needed",
                message: "We're looking for volunteers to serve in our ministry!\n\nAreas of need:\n- Children's Ministry\n- Worship Team\n- Welcome Team\n- Prayer Team\n\nIf you're interested, please contact us.\nThank you for serving!"
            },
            {
                title: "Thanksgiving Message",
                subject: "We're Grateful for You",
                message: "Dear Church Family,\n\nWe want to take a moment to express our gratitude for each of you. Your faithfulness, support, and love make our church community stronger.\n\nThank you for being part of this family.\n\nWith love and appreciation,\nPastoral Team"
            }
        ];

        // Create modal
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        modal.innerHTML = `
            <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-4xl max-h-[80vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-800">
                        <i class="fas fa-magic text-purple-600 mr-2"></i>AI Announcement Templates
                    </h3>
                    <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
                <p class="text-gray-600 mb-6">Choose a template to get started, then customize it for your needs:</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    ${suggestions.map((suggestion, index) => `
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-purple-500 hover:shadow-lg transition-all cursor-pointer" onclick="applyTemplate(${index})">
                            <h4 class="font-bold text-lg text-gray-800 mb-2">${suggestion.title}</h4>
                            <p class="text-sm text-gray-600 mb-2"><strong>Subject:</strong> ${suggestion.subject}</p>
                            <p class="text-xs text-gray-500 line-clamp-3">${suggestion.message.substring(0, 100)}...</p>
                            <button class="mt-3 text-purple-600 font-semibold text-sm hover:text-purple-700">
                                Use This Template →
                            </button>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
        document.body.appendChild(modal);

        // Store suggestions in window for access
        window.broadcastSuggestions = suggestions;
    }

    // Apply template
    function applyTemplate(index) {
        const suggestion = window.broadcastSuggestions[index];
        document.querySelector('input[name="subject"]').value = suggestion.subject;
        document.getElementById('messageText').value = suggestion.message;
        updateCharCount();
        closeModal();
        showNotification('✓ Template applied! Feel free to customize it.', 'success');
    }

    // Close modal
    function closeModal() {
        const modal = document.querySelector('.fixed.inset-0');
        if (modal) modal.remove();
    }

    // Show notification
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${type === 'success' ? 'bg-green-500' : 'bg-blue-500'} text-white`;
        notification.textContent = message;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }

    // Form validation before submit
    document.getElementById('broadcastForm').addEventListener('submit', function(e) {
        const channels = document.querySelectorAll('input[name="channels[]"]:checked');
        if (channels.length === 0) {
            e.preventDefault();
            alert('⚠️ Please select at least one delivery channel (SMS, Email, or Notification)');
            return false;
        }

        const message = document.getElementById('messageText').value;
        if (message.length < 10) {
            e.preventDefault();
            alert('⚠️ Message is too short. Please write at least 10 characters.');
            return false;
        }

        // Show sending indicator
        const submitBtn = e.target.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending...';
        submitBtn.disabled = true;
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });

    // Initialize character count on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateCharCount();
    });
    </script>

    <!-- Recent Broadcasts -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mt-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Recent Broadcasts</h3>
        <div class="space-y-3">
            <div class="p-4 bg-gray-50 rounded-lg flex items-center justify-between">
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">Sunday Service Reminder</p>
                    <p class="text-sm text-gray-600">Sent to All Members • 2 days ago</p>
                </div>
                <div class="text-sm text-gray-600">
                    <i class="fas fa-check-circle text-green-600 mr-1"></i>
                    348 delivered
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
