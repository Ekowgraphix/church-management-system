@extends('layouts.app')

@section('content')
<div class="chatgpt-container">
    <!-- Sidebar -->
    <div class="chatgpt-sidebar">
        <div class="sidebar-header">
            <a href="{{ route('chat.new') }}" class="new-chat-btn">
                <i class="fas fa-plus me-2"></i> New chat
            </a>
        </div>
        
        <div class="sidebar-conversations">
            @foreach($conversations as $conv)
                <a href="{{ route('chat.show', $conv->id) }}" 
                   class="conversation-item {{ $conv->id == $conversation->id ? 'active' : '' }}">
                    <i class="fas fa-message me-2"></i>
                    <span class="conversation-title">{{ Str::limit($conv->title, 30) }}</span>
                </a>
            @endforeach
        </div>
        
        <div class="sidebar-footer">
            <a href="{{ route('chat.index') }}" class="sidebar-link">
                <i class="fas fa-home me-2"></i> All Chats
            </a>
        </div>
    </div>

    <!-- Main Chat Area -->
    <div class="chatgpt-main">
        <!-- Header -->
        <div class="chat-header">
            <div class="chat-title">
                <i class="fas fa-robot me-2"></i>
                <span>{{ $conversation->title }}</span>
            </div>
            <div class="chat-actions">
                <a href="{{ route('chat.export', $conversation->id) }}" class="btn-icon" title="Export chat">
                    <i class="fas fa-download"></i>
                </a>
                <form action="{{ route('chat.clear', $conversation->id) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Clear all messages?');">
                    @csrf
                    <button type="submit" class="btn-icon" title="Clear chat">
                        <i class="fas fa-eraser"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Messages Container -->
        <div class="chat-messages-container" id="chatMessages">
            <div class="chat-messages-inner">
                @forelse($conversation->messages as $message)
                    @if($message->role !== 'system')
                        <div class="message-row {{ $message->role === 'user' ? 'user-row' : 'assistant-row' }}">
                            <div class="message-wrapper">
                                <div class="message-avatar">
                                    @if($message->role === 'user')
                                        <div class="avatar-circle user-avatar">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    @else
                                        <div class="avatar-circle ai-avatar">
                                            <i class="fas fa-robot"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="message-content-wrapper">
                                    <div class="message-text">{!! nl2br(e($message->content)) !!}</div>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="empty-chat">
                        <div class="empty-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <h3>How can I help you today?</h3>
                        <p>Start a conversation with the AI assistant</p>
                    </div>
                @endforelse
                
                <!-- Typing Indicator -->
                <div id="typingIndicator" style="display: none;" class="message-row assistant-row">
                    <div class="message-wrapper">
                        <div class="message-avatar">
                            <div class="avatar-circle ai-avatar">
                                <i class="fas fa-robot"></i>
                            </div>
                        </div>
                        <div class="message-content-wrapper">
                            <div class="typing-indicator">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="chat-input-area">
            <form id="chatForm" class="chat-input-form">
                @csrf
                <div class="input-wrapper">
                    <textarea 
                        id="messageInput" 
                        class="chat-textarea" 
                        placeholder="Message AI Assistant..."
                        rows="1"
                        required
                    ></textarea>
                    <button type="submit" class="send-button" id="sendBtn">
                        <i class="fas fa-arrow-up"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* ChatGPT-like Container */
.chatgpt-container {
    display: flex;
    height: calc(100vh - 60px);
    background: #f7f7f8;
    margin: -20px;
}

/* Sidebar */
.chatgpt-sidebar {
    width: 260px;
    background: #202123;
    display: flex;
    flex-direction: column;
    border-right: 1px solid #444654;
}

.sidebar-header {
    padding: 12px;
    border-bottom: 1px solid #444654;
}

.new-chat-btn {
    display: flex;
    align-items: center;
    padding: 12px;
    background: transparent;
    border: 1px solid #565869;
    border-radius: 6px;
    color: #fff;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.2s;
}

.new-chat-btn:hover {
    background: #2a2b32;
    color: #fff;
}

.sidebar-conversations {
    flex: 1;
    overflow-y: auto;
    padding: 8px;
}

.sidebar-conversations::-webkit-scrollbar {
    width: 6px;
}

.sidebar-conversations::-webkit-scrollbar-thumb {
    background: #565869;
    border-radius: 3px;
}

.conversation-item {
    display: flex;
    align-items: center;
    padding: 12px;
    margin-bottom: 4px;
    border-radius: 6px;
    color: #ececf1;
    text-decoration: none;
    font-size: 14px;
    transition: background 0.2s;
}

.conversation-item:hover {
    background: #2a2b32;
    color: #fff;
}

.conversation-item.active {
    background: #343541;
    color: #fff;
}

.conversation-title {
    flex: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.sidebar-footer {
    padding: 12px;
    border-top: 1px solid #444654;
}

.sidebar-link {
    display: flex;
    align-items: center;
    padding: 12px;
    color: #ececf1;
    text-decoration: none;
    font-size: 14px;
    border-radius: 6px;
}

.sidebar-link:hover {
    background: #2a2b32;
    color: #fff;
}

/* Main Chat Area */
.chatgpt-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: #ffffff;
}

.chat-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 24px;
    border-bottom: 1px solid #e5e5e5;
    background: #fff;
}

.chat-title {
    font-size: 18px;
    font-weight: 600;
    color: #202123;
}

.chat-actions {
    display: flex;
    gap: 8px;
}

.btn-icon {
    width: 36px;
    height: 36px;
    border-radius: 6px;
    border: 1px solid #d1d5db;
    background: #fff;
    color: #374151;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
}

.btn-icon:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
}

/* Messages Container */
.chat-messages-container {
    flex: 1;
    overflow-y: auto;
    background: #ffffff;
}

.chat-messages-container::-webkit-scrollbar {
    width: 8px;
}

.chat-messages-container::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 4px;
}

.chat-messages-inner {
    max-width: 800px;
    margin: 0 auto;
    padding: 24px;
}

.message-row {
    padding: 24px 0;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.user-row {
    background: transparent;
}

.assistant-row {
    background: #f7f7f8;
    margin-left: -24px;
    margin-right: -24px;
    padding-left: 24px;
    padding-right: 24px;
}

.message-wrapper {
    display: flex;
    gap: 16px;
    max-width: 800px;
    margin: 0 auto;
}

.message-avatar {
    flex-shrink: 0;
}

.avatar-circle {
    width: 36px;
    height: 36px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.user-avatar {
    background: #19c37d;
    color: #fff;
}

.ai-avatar {
    background: #ab68ff;
    color: #fff;
}

.message-content-wrapper {
    flex: 1;
    min-width: 0;
}

.message-text {
    font-size: 16px;
    line-height: 1.7;
    color: #374151;
    word-wrap: break-word;
}

.empty-chat {
    text-align: center;
    padding: 80px 20px;
}

.empty-icon {
    font-size: 64px;
    color: #d1d5db;
    margin-bottom: 24px;
}

.empty-chat h3 {
    font-size: 28px;
    font-weight: 600;
    color: #202123;
    margin-bottom: 12px;
}

.empty-chat p {
    font-size: 16px;
    color: #6b7280;
}

/* Typing Indicator */
.typing-indicator {
    display: flex;
    gap: 6px;
    padding: 8px 0;
}

.typing-indicator span {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #9ca3af;
    animation: typing 1.4s infinite;
}

.typing-indicator span:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-indicator span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0%, 60%, 100% { transform: translateY(0); opacity: 0.7; }
    30% { transform: translateY(-8px); opacity: 1; }
}

/* Input Area */
.chat-input-area {
    padding: 24px;
    background: #ffffff;
    border-top: 1px solid #e5e5e5;
}

.chat-input-form {
    max-width: 800px;
    margin: 0 auto;
}

.input-wrapper {
    position: relative;
    display: flex;
    align-items: flex-end;
    background: #ffffff;
    border: 1px solid #d1d5db;
    border-radius: 12px;
    padding: 12px 16px;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
    transition: all 0.2s;
}

.input-wrapper:focus-within {
    border-color: #9ca3af;
    box-shadow: 0 0 20px rgba(0,0,0,0.15);
}

.chat-textarea {
    flex: 1;
    border: none;
    outline: none;
    resize: none;
    font-size: 16px;
    line-height: 1.5;
    max-height: 200px;
    overflow-y: auto;
    font-family: inherit;
}

.chat-textarea::placeholder {
    color: #9ca3af;
}

.send-button {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: #19c37d;
    border: none;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    flex-shrink: 0;
    margin-left: 12px;
}

.send-button:hover {
    background: #17b374;
}

.send-button:disabled {
    background: #d1d5db;
    cursor: not-allowed;
}

.send-button i {
    font-size: 16px;
}

/* Responsive */
@media (max-width: 768px) {
    .chatgpt-sidebar {
        position: fixed;
        left: -260px;
        height: 100vh;
        z-index: 1000;
        transition: left 0.3s;
    }
    
    .chatgpt-sidebar.open {
        left: 0;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('messageInput');
    const chatMessages = document.getElementById('chatMessages');
    const sendBtn = document.getElementById('sendBtn');
    const typingIndicator = document.getElementById('typingIndicator');

    // Auto-resize textarea
    messageInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 200) + 'px';
    });

    // Submit on Enter (but Shift+Enter for new line)
    messageInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            chatForm.dispatchEvent(new Event('submit'));
        }
    });

    // Scroll to bottom
    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Initial scroll
    scrollToBottom();

    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const message = messageInput.value.trim();
        if (!message) return;

        // Disable input
        messageInput.disabled = true;
        sendBtn.disabled = true;

        // Add user message to UI
        addMessageToUI('user', message);
        messageInput.value = '';
        messageInput.style.height = 'auto';

        // Show typing indicator
        typingIndicator.style.display = 'block';
        scrollToBottom();

        try {
            const response = await fetch('{{ route("chat.send", $conversation->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: message })
            });

            const data = await response.json();

            // Hide typing indicator
            typingIndicator.style.display = 'none';

            if (data.success) {
                // Add assistant message to UI
                addMessageToUI('assistant', data.assistant_message.content);
            } else {
                alert('Failed to send message. Please try again.');
            }
        } catch (error) {
            console.error('Error:', error);
            typingIndicator.style.display = 'none';
            alert('An error occurred. Please check your connection.');
        }

        // Re-enable input
        messageInput.disabled = false;
        sendBtn.disabled = false;
        messageInput.focus();
    });

    function addMessageToUI(role, content) {
        const messageRow = document.createElement('div');
        messageRow.className = `message-row ${role === 'user' ? 'user-row' : 'assistant-row'}`;
        
        messageRow.innerHTML = `
            <div class="message-wrapper">
                <div class="message-avatar">
                    <div class="avatar-circle ${role === 'user' ? 'user-avatar' : 'ai-avatar'}">
                        <i class="fas fa-${role === 'user' ? 'user' : 'robot'}"></i>
                    </div>
                </div>
                <div class="message-content-wrapper">
                    <div class="message-text">${content.replace(/\n/g, '<br>')}</div>
                </div>
            </div>
        `;

        const inner = chatMessages.querySelector('.chat-messages-inner');
        inner.insertBefore(messageRow, typingIndicator);
        scrollToBottom();
    }
});
</script>
@endsection
