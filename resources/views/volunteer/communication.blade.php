@extends('layouts.volunteer')

@section('content')
<div>
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <h1 class="text-3xl font-bold text-gray-800">🗣️ Communication</h1>
        <p class="text-gray-600">Chat with team members and view announcements</p>
    </div>

    <!-- Pinned Announcements -->
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl shadow-lg p-6 text-white mb-6">
        <div class="flex items-center space-x-3 mb-4">
            <i class="fas fa-thumbtack text-2xl"></i>
            <h3 class="text-xl font-bold">Pinned Announcement</h3>
        </div>
        <p class="text-lg mb-2">Volunteer Appreciation Event - Saturday 4 PM</p>
        <p class="text-sm opacity-90">Join us for a special appreciation event to honor all our faithful volunteers. Refreshments will be served!</p>
    </div>

    <!-- Chat Interface -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Contacts Sidebar -->
        <div class="lg:col-span-1 bg-white rounded-2xl shadow-lg p-4">
            <h3 class="font-bold text-gray-800 mb-4">Chats</h3>
            <div class="space-y-2">
                <div onclick="switchChat('ushering-team')" class="p-3 bg-orange-50 rounded-lg cursor-pointer hover:bg-orange-100 transition-all">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center relative">
                            <span class="font-bold text-orange-600">UT</span>
                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 rounded-full text-white text-xs flex items-center justify-center">3</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 text-sm truncate">Ushering Team</p>
                            <p class="text-xs text-gray-600 truncate">Team Leader: Great work today!</p>
                        </div>
                    </div>
                </div>
                <div onclick="switchChat('john-mensah')" class="p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-all">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="font-bold text-blue-600">JM</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 text-sm truncate">John Mensah</p>
                            <p class="text-xs text-gray-600 truncate">See you Sunday!</p>
                        </div>
                    </div>
                </div>
                <div onclick="switchChat('pastor')" class="p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition-all">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <span class="font-bold text-green-600">PT</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 text-sm truncate">Pastor Team</p>
                            <p class="text-xs text-gray-600 truncate">God bless you!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="lg:col-span-3 bg-white rounded-2xl shadow-lg flex flex-col" style="height: 500px;">
            <!-- Chat Header -->
            <div class="border-b p-4">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                        <span class="font-bold text-orange-600">UT</span>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800">Ushering Team</p>
                        <p class="text-sm text-gray-600">8 members</p>
                    </div>
                </div>
            </div>

            <!-- Messages -->
            <div id="messageArea" class="flex-1 overflow-y-auto p-4 space-y-4">
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-xs font-bold text-blue-600">JM</span>
                    </div>
                    <div>
                        <div class="bg-gray-100 rounded-2xl rounded-tl-none px-4 py-2">
                            <p class="text-sm text-gray-800">Great work everyone today! The service ran smoothly.</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">10:30 AM</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3 justify-end">
                    <div>
                        <div class="bg-orange-600 text-white rounded-2xl rounded-tr-none px-4 py-2">
                            <p class="text-sm">Thank you! It was a blessing to serve.</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 text-right">10:32 AM</p>
                    </div>
                    <div class="w-8 h-8 bg-orange-200 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user text-orange-700 text-xs"></i>
                    </div>
                </div>
            </div>

            <!-- Message Input -->
            <div class="border-t p-4">
                <div class="flex space-x-3">
                    <button onclick="attachFile()" class="text-gray-600 hover:text-gray-800 transition-all">
                        <i class="fas fa-paperclip text-xl"></i>
                    </button>
                    <input type="text" id="messageInput" placeholder="Type a message..." class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:border-orange-500 focus:ring-2 focus:ring-orange-200" onkeypress="if(event.key==='Enter') sendChatMessage()">
                    <button onclick="sendChatMessage()" class="bg-orange-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-orange-700 transition-all">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Send Chat Message
function sendChatMessage() {
    const input = document.getElementById('messageInput');
    const message = input.value.trim();
    
    if (!message) return;
    
    // Add message to chat
    const messageArea = document.getElementById('messageArea');
    const now = new Date();
    const timeStr = now.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
    
    const messageDiv = document.createElement('div');
    messageDiv.className = 'flex items-start space-x-3 justify-end';
    messageDiv.innerHTML = `
        <div>
            <div class="bg-orange-600 text-white rounded-2xl rounded-tr-none px-4 py-2">
                <p class="text-sm">${message}</p>
            </div>
            <p class="text-xs text-gray-500 mt-1 text-right">${timeStr}</p>
        </div>
        <div class="w-8 h-8 bg-orange-200 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fas fa-user text-orange-700 text-xs"></i>
        </div>
    `;
    
    messageArea.appendChild(messageDiv);
    messageArea.scrollTop = messageArea.scrollHeight;
    
    // Clear input
    input.value = '';
    
    // Show "sent" feedback
    const feedback = document.createElement('div');
    feedback.className = 'text-xs text-green-600 text-right mt-1';
    feedback.innerHTML = '✓ Sent';
    messageDiv.appendChild(feedback);
    
    // Simulate reply (optional)
    setTimeout(() => {
        simulateReply();
    }, 2000);
}

// Simulate Reply (for demonstration)
function simulateReply() {
    const replies = [
        'Thanks for your message!',
        'Got it, see you then!',
        'Appreciate it!',
        'Will do, thanks!',
        'Perfect, thank you!'
    ];
    
    const messageArea = document.getElementById('messageArea');
    const now = new Date();
    const timeStr = now.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
    const reply = replies[Math.floor(Math.random() * replies.length)];
    
    const messageDiv = document.createElement('div');
    messageDiv.className = 'flex items-start space-x-3';
    messageDiv.innerHTML = `
        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
            <span class="text-xs font-bold text-blue-600">JM</span>
        </div>
        <div>
            <div class="bg-gray-100 rounded-2xl rounded-tl-none px-4 py-2">
                <p class="text-sm text-gray-800">${reply}</p>
            </div>
            <p class="text-xs text-gray-500 mt-1">${timeStr}</p>
        </div>
    `;
    
    messageArea.appendChild(messageDiv);
    messageArea.scrollTop = messageArea.scrollHeight;
}

// Attach File
function attachFile() {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*,.pdf,.doc,.docx';
    
    input.onchange = function(e) {
        const file = e.target.files[0];
        if (!file) return;
        
        // File size check (max 10MB)
        if (file.size > 10 * 1024 * 1024) {
            alert('⚠️ File too large! Maximum size is 10MB.');
            return;
        }
        
        // Show confirmation
        if (confirm(`📎 Send "${file.name}"?\\n\\nSize: ${(file.size / 1024).toFixed(2)} KB`)) {
            // Add file message to chat
            const messageArea = document.getElementById('messageArea');
            const now = new Date();
            const timeStr = now.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
            
            const messageDiv = document.createElement('div');
            messageDiv.className = 'flex items-start space-x-3 justify-end';
            messageDiv.innerHTML = `
                <div>
                    <div class="bg-orange-600 text-white rounded-2xl rounded-tr-none px-4 py-2">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-file text-lg"></i>
                            <div>
                                <p class="text-sm font-semibold">${file.name}</p>
                                <p class="text-xs opacity-75">${(file.size / 1024).toFixed(2)} KB</p>
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 text-right">${timeStr}</p>
                </div>
                <div class="w-8 h-8 bg-orange-200 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user text-orange-700 text-xs"></i>
                </div>
            `;
            
            messageArea.appendChild(messageDiv);
            messageArea.scrollTop = messageArea.scrollHeight;
            
            alert('✅ File sent successfully!');
            
            // TODO: Upload to server
            // const formData = new FormData();
            // formData.append('file', file);
            // fetch('/api/upload', { method: 'POST', body: formData });
        }
    };
    
    input.click();
}

// Switch Chat
function switchChat(chatId) {
    const chats = {
        'ushering-team': {
            name: 'Ushering Team',
            members: '8 members',
            initials: 'UT',
            color: 'orange'
        },
        'john-mensah': {
            name: 'John Mensah',
            members: 'Team Leader',
            initials: 'JM',
            color: 'blue'
        },
        'pastor': {
            name: 'Pastor Team',
            members: '3 members',
            initials: 'PT',
            color: 'green'
        }
    };
    
    const chat = chats[chatId];
    if (!chat) return;
    
    // Update chat header
    const header = document.querySelector('.border-b.p-4');
    header.innerHTML = `
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-${chat.color}-100 rounded-full flex items-center justify-center">
                <span class="font-bold text-${chat.color}-600">${chat.initials}</span>
            </div>
            <div>
                <p class="font-bold text-gray-800">${chat.name}</p>
                <p class="text-sm text-gray-600">${chat.members}</p>
            </div>
        </div>
    `;
    
    // Clear messages
    document.getElementById('messageArea').innerHTML = `
        <div class="text-center text-gray-500 py-8">
            <p>💬 Start a conversation with ${chat.name}</p>
        </div>
    `;
    
    // Show notification
    alert(`📱 Switched to ${chat.name}`);
}
</script>
@endsection
