@extends('layouts.member-portal')

@section('page-title', 'Member Chat')
@section('page-subtitle', 'Message fellow church members')

@section('content')
<style>
    .chat-wrapper {
        padding: 0.5rem;
        min-height: 100vh;
    }
    
    .members-list {
        display: block;
    }
    
    .chat-area {
        display: none;
        position: fixed;
        inset: 0;
        top: 60px;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        z-index: 50;
        padding: 0.5rem;
    }
    
    .chat-area.active {
        display: block;
    }
    
    .members-list.hidden {
        display: none;
    }
    
    @media (min-width: 1024px) {
        .chat-wrapper {
            display: flex;
            gap: 1rem;
            padding: 1rem;
        }
        
        .members-list {
            width: 320px;
            flex-shrink: 0;
            display: block !important;
        }
        
        .chat-area {
            flex: 1;
            display: block;
            position: relative;
            inset: auto;
            top: auto;
            background: none;
            padding: 0;
        }
    }
</style>

<div class="chat-wrapper">
    <!-- Members List -->
    <div class="members-list">
        <div class="bg-slate-900/50 backdrop-blur-xl rounded-2xl p-4">
            <h2 class="text-xl font-bold text-white mb-4">Messages</h2>
            <div class="relative mb-4">
                <input type="text" id="searchUsers" placeholder="Search members..." 
                       class="w-full pl-10 pr-4 py-3 bg-white/5 border border-white/20 rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all">
                <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
            </div>

            <div id="usersList" class="space-y-2">
            @forelse($users as $user)
                <div class="user-item p-4 border-b border-white/5 hover:bg-white/10 cursor-pointer transition-all active:scale-98"
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
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            @if($user->unread_count > 0)
                                <div class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                    {{ $user->unread_count }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold text-white truncate">{{ $user->name }}</div>
                            <div class="text-sm text-gray-400 truncate">{{ $user->email }}</div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center p-8 text-gray-400">
                    <i class="fas fa-users text-6xl mb-4 opacity-50"></i>
                    <p class="text-lg">No members found</p>
                    <p class="text-sm mt-2">Start connecting with your church community</p>
                </div>
            @endforelse
            </div>
        </div>
    </div>

    <!-- Chat Area -->
    <div class="chat-area">
        <div class="bg-slate-900/50 backdrop-blur-xl rounded-2xl flex flex-col overflow-hidden" style="min-height: 80vh;">
            <!-- Empty State -->
            <div id="chatPlaceholder" class="flex-1 flex items-center justify-center p-8">
                <div class="text-center max-w-md mx-auto">
                    <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                        <i class="fas fa-comments text-3xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Select a Member</h3>
                    <p class="text-gray-400">Choose someone to start chatting</p>
                </div>
            </div>

            <!-- Active Chat -->
            <div id="chatArea" class="flex flex-col h-full hidden">
            <!-- Chat Header -->
            <div class="p-4 border-b border-white/10 bg-slate-900/50 backdrop-blur-xl sticky top-0 z-10">
                <div class="flex items-center space-x-3">
                    <button id="backToListBtn" class="lg:hidden text-green-400 hover:text-green-300 w-10 h-10 flex items-center justify-center rounded-full hover:bg-white/5 transition-all flex-shrink-0">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </button>
                    <img id="chatUserPhoto" class="w-12 h-12 rounded-full object-cover border-2 border-green-500 shadow-lg hidden flex-shrink-0">
                    <div id="chatUserAvatar" class="w-12 h-12 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white font-bold text-xl shadow-lg flex-shrink-0"></div>
                    <div class="flex-1 min-w-0">
                        <div id="chatUserName" class="font-semibold text-white text-lg truncate"></div>
                        <div class="text-sm text-green-400 flex items-center">
                            <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                            Active now
                        </div>
                    </div>
                    <button id="closeChatBtn" class="hidden lg:block text-gray-400 hover:text-white w-10 h-10 flex items-center justify-center rounded-full hover:bg-white/5 transition-all flex-shrink-0">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Messages Area -->
            <div id="messagesContainer" class="flex-1 overflow-y-auto p-4 md:p-6 space-y-4" style="max-height: calc(80vh - 140px);">
                <!-- Messages will be loaded here -->
            </div>

            <!-- Message Input -->
            <div class="p-4 border-t border-white/10 bg-slate-900/50 backdrop-blur-xl">
                <form id="sendMessageForm" class="flex items-end space-x-2">
                    @csrf
                    <input type="hidden" id="receiverId" name="receiver_id">
                    <div class="flex-1 relative">
                        <input type="text" id="messageInput" name="message" placeholder="Message..." 
                               class="w-full px-4 py-4 pr-12 bg-white/5 border border-white/20 rounded-3xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all"
                               required>
                        <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition-colors">
                            <i class="fas fa-paperclip text-lg"></i>
                        </button>
                    </div>
                    <button type="submit" class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-full text-white font-semibold hover:from-green-600 hover:to-green-700 transition-all shadow-lg hover:shadow-xl flex items-center justify-center">
                        <i class="fas fa-paper-plane"></i>
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

        // Mobile: Show chat area, hide members list
        const chatArea = document.querySelector('.chat-area');
        const membersList = document.querySelector('.members-list');
        
        if (window.innerWidth < 1024) {
            membersList.classList.add('hidden');
            chatArea.classList.add('active');
        }

        // Update UI
        const placeholder = document.getElementById('chatPlaceholder');
        const chatContent = document.getElementById('chatArea');
        
        placeholder.classList.add('hidden');
        chatContent.classList.remove('hidden');
        
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

        // Back to members list (mobile)
        document.getElementById('backToListBtn').addEventListener('click', function() {
            console.log('Back to list clicked');
            const chatArea = document.querySelector('.chat-area');
            const membersList = document.querySelector('.members-list');
            
            chatArea.classList.remove('active');
            membersList.classList.remove('hidden');
            
            document.getElementById('chatArea').classList.add('hidden');
            document.getElementById('chatPlaceholder').classList.remove('hidden');
            currentChatUserId = null;
            currentChatUserName = null;
        });

        // Close chat (desktop)
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
