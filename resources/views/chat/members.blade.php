@extends('layouts.member-portal')

@section('page-title', 'Member Chat')
@section('page-subtitle', 'Message fellow church members')

@section('content')
<div class="h-screen flex">
    <!-- Users List Sidebar -->
    <div class="w-80 glass-card flex flex-col">
        <!-- Search Bar -->
        <div class="p-4 border-b border-white/10">
            <div class="relative">
                <input type="text" id="searchUsers" placeholder="Search members..." 
                       class="w-full pl-10 pr-4 py-2 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
        </div>

        <!-- Users List -->
        <div id="usersList" class="flex-1 overflow-y-auto">
            @forelse($users as $user)
                <div class="user-item p-4 border-b border-white/5 hover:bg-white/5 cursor-pointer transition-colors"
                     data-user-id="{{ $user->id }}"
                     data-user-name="{{ $user->name }}">
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            @php
                                $userMember = \App\Models\Member::where('email', $user->email)->first();
                            @endphp
                            @if($userMember && $userMember->photo)
                                <img src="{{ asset('storage/' . $userMember->photo) }}" 
                                     alt="{{ $user->name }}"
                                     class="w-12 h-12 rounded-full object-cover border-2 border-green-500">
                            @else
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white font-bold text-lg">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            @if($user->unread_count > 0)
                                <div class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                    {{ $user->unread_count }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold text-white">{{ $user->name }}</div>
                            <div class="text-sm text-gray-400">{{ $user->email }}</div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-400">
                    <i class="fas fa-users fa-3x mb-3"></i>
                    <p>No members found</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Chat Area -->
    <div class="flex-1 flex flex-col glass-card ml-6">
        <div id="chatPlaceholder" class="flex-1 flex items-center justify-center text-gray-400">
            <div class="text-center">
                <i class="fas fa-comments fa-4x mb-4"></i>
                <p class="text-xl">Select a member to start chatting</p>
            </div>
        </div>

        <div id="chatArea" class="flex-1 flex-col hidden">
            <!-- Chat Header -->
            <div class="p-4 border-b border-white/10 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <img id="chatUserPhoto" class="w-10 h-10 rounded-full object-cover border-2 border-green-500 hidden">
                    <div id="chatUserAvatar" class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white font-bold"></div>
                    <div>
                        <div id="chatUserName" class="font-semibold text-white"></div>
                        <div class="text-sm text-gray-400">Active now</div>
                    </div>
                </div>
                <button id="closeChatBtn" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Messages Area -->
            <div id="messagesContainer" class="flex-1 overflow-y-auto p-6 space-y-4">
                <!-- Messages will be loaded here -->
            </div>

            <!-- Message Input -->
            <div class="p-4 border-t border-white/10">
                <form id="sendMessageForm" class="flex space-x-3">
                    @csrf
                    <input type="hidden" id="receiverId" name="receiver_id">
                    <input type="text" id="messageInput" name="message" placeholder="Type your message..." 
                           class="flex-1 px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500"
                           required>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 rounded-xl text-white font-semibold hover:from-green-600 hover:to-green-700 transition-all">
                        <i class="fas fa-paper-plane"></i> Send
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>
<script>
    let currentChatUserId = null;
    let currentChatUserName = null;

    // Initialize Laravel Echo for real-time broadcasting
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: '{{ env('PUSHER_APP_KEY') }}',
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
        forceTLS: true,
        encrypted: true,
        authEndpoint: '/broadcasting/auth',
        auth: {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }
    });

    // Listen for incoming messages
    window.Echo.private('user.{{ auth()->id() }}')
        .listen('.message.sent', (data) => {
            console.log('Broadcast message received:', data);
            // Only append if we're in a chat with this sender
            if (currentChatUserId && data.sender_id == currentChatUserId) {
                const message = {
                    id: data.id,
                    sender_id: data.sender_id,
                    message: data.message,
                    created_at: data.created_at
                };
                appendMessage(message, 'received');
            }
        });

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Chat page loaded');
        
        // User selection - Add click handlers
        document.querySelectorAll('.user-item').forEach(item => {
            item.addEventListener('click', function() {
                console.log('User clicked');
                const userId = this.dataset.userId;
                const userName = this.dataset.userName;
                console.log('Opening chat with:', userName, 'ID:', userId);
                openChat(userId, userName);
            });
        });

    // Open chat with a user
    function openChat(userId, userName) {
        console.log('openChat called with:', userId, userName);
        currentChatUserId = userId;
        currentChatUserName = userName;

        // Update UI
        const placeholder = document.getElementById('chatPlaceholder');
        const chatArea = document.getElementById('chatArea');
        
        placeholder.classList.add('hidden');
        chatArea.classList.remove('hidden');
        chatArea.classList.add('flex');
        
        document.getElementById('chatUserName').textContent = userName;
        document.getElementById('chatUserAvatar').textContent = userName.charAt(0).toUpperCase();
        document.getElementById('receiverId').value = userId;
        
        // Check if user has a photo by looking at the user item
        const userItem = document.querySelector(`.user-item[data-user-id="${userId}"]`);
        const userPhoto = userItem?.querySelector('img');
        const chatUserPhoto = document.getElementById('chatUserPhoto');
        const chatUserAvatar = document.getElementById('chatUserAvatar');
        
        if (userPhoto) {
            chatUserPhoto.src = userPhoto.src;
            chatUserPhoto.classList.remove('hidden');
            chatUserAvatar.classList.add('hidden');
        } else {
            chatUserPhoto.classList.add('hidden');
            chatUserAvatar.classList.remove('hidden');
        }

        // Clear previous messages
        document.getElementById('messagesContainer').innerHTML = '<div class="text-center text-white"><i class="fas fa-spinner fa-spin text-2xl"></i><p class="mt-2">Loading messages...</p></div>';

        // Load messages
        loadMessages(userId);
        
        // Mark as read
        fetch(`/chat/mark-read/${userId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        }).catch(err => console.log('Mark as read error:', err));
    }

    // Load messages
    function loadMessages(userId) {
        console.log('Loading messages for user:', userId);
        
        fetch(`/chat/messages/${userId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Messages loaded:', data);
            const container = document.getElementById('messagesContainer');
            container.innerHTML = '';
            
            if (!data || data.length === 0) {
                container.innerHTML = '<div class="text-center text-gray-400"><i class="fas fa-comments fa-2x mb-2"></i><p>No messages yet. Start the conversation!</p></div>';
                return;
            }

            data.forEach(msg => {
                const type = msg.sender_id == {{ auth()->id() }} ? 'sent' : 'received';
                appendMessage(msg, type);
            });

            // Scroll to bottom
            container.scrollTop = container.scrollHeight;
        })
        .catch(error => {
            console.error('Error loading messages:', error);
            document.getElementById('messagesContainer').innerHTML = '<div class="text-center text-red-400"><i class="fas fa-exclamation-circle fa-2x mb-2"></i><p>Error loading messages. Please try again.</p></div>';
        });
    }

    // Append message to chat
    function appendMessage(msg, type) {
        const container = document.getElementById('messagesContainer');
        const time = new Date(msg.created_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
        
        const messageHtml = `
            <div class="flex ${type === 'sent' ? 'justify-end' : 'justify-start'}">
                <div class="max-w-md ${type === 'sent' ? 'bg-gradient-to-r from-green-500 to-green-600' : 'bg-white/10'} rounded-2xl px-4 py-3">
                    <p class="text-white">${escapeHtml(msg.message)}</p>
                    <span class="text-xs ${type === 'sent' ? 'text-green-100' : 'text-gray-400'} mt-1 block">${time}</span>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', messageHtml);
        container.scrollTop = container.scrollHeight;
    }

    // Send message
    document.getElementById('sendMessageForm').addEventListener('submit', function(e) {
        e.preventDefault();
        console.log('Send message form submitted');
        
        const messageInput = document.getElementById('messageInput');
        const message = messageInput.value.trim();
        
        if (!message) {
            console.log('No message to send');
            return;
        }
        
        if (!currentChatUserId) {
            alert('Please select a user first');
            return;
        }

        const data = {
            receiver_id: currentChatUserId,
            message: message
        };

        console.log('Sending message:', data);

        fetch('/chat/send', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            console.log('Message sent:', data);
            if (data.success && data.message) {
                appendMessage(data.message, 'sent');
                messageInput.value = '';
            }
        })
        .catch(error => {
            console.error('Error sending message:', error);
            alert('Failed to send message. Please try again.');
        });
    });

        // Close chat
        document.getElementById('closeChatBtn').addEventListener('click', function() {
            console.log('Close chat clicked');
            document.getElementById('chatArea').classList.add('hidden');
            document.getElementById('chatPlaceholder').classList.remove('hidden');
            currentChatUserId = null;
            currentChatUserName = null;
        });

        // Search users
        document.getElementById('searchUsers').addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase();
            document.querySelectorAll('.user-item').forEach(item => {
                const name = item.dataset.userName.toLowerCase();
                item.style.display = name.includes(query) ? 'block' : 'none';
            });
        });
    }); // End of DOMContentLoaded

    // Escape HTML to prevent XSS
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
</script>
@endpush
@endsection
