@extends('layouts.member-portal')

@section('content')
<div>
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-robot mr-3 text-green-400"></i>
            AI Spiritual Assistant
        </h1>
        <p class="text-gray-300">Ask questions about faith, Bible verses, or spiritual guidance</p>
    </div>

    <!-- Chat Container -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden" style="height: calc(100vh - 250px);">
        <!-- Chat Messages -->
        <div id="chat-messages" class="p-6 overflow-y-auto" style="height: calc(100% - 80px);">
            <!-- Welcome Message -->
            <div class="mb-4">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-robot text-white"></i>
                    </div>
                    <div class="bg-gray-100 rounded-2xl rounded-tl-none px-4 py-3 max-w-2xl">
                        <p class="text-gray-800">
                            👋 Hello! I'm your AI Spiritual Assistant. I can help you with:
                        </p>
                        <ul class="mt-2 space-y-1 text-gray-700 text-sm">
                            <li>📖 Bible verses and explanations</li>
                            <li>🙏 Prayer guidance</li>
                            <li>✝️ Spiritual questions</li>
                            <li>💡 Daily devotional insights</li>
                            <li>❓ General faith questions</li>
                        </ul>
                        <p class="mt-2 text-gray-800">How can I assist you today?</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Input -->
        <div class="border-t border-gray-200 p-4 bg-gray-50">
            <form id="chat-form" class="flex items-center space-x-3">
                <input type="text" 
                       id="message-input"
                       placeholder="Type your question here..." 
                       class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-indigo-500"
                       autocomplete="off">
                <button type="submit" 
                        class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-indigo-700 transition flex items-center space-x-2">
                    <i class="fas fa-paper-plane"></i>
                    <span>Send</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Quick Suggestions -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <button onclick="askQuestion('What does the Bible say about prayer?')" 
                class="bg-white/10 hover:bg-white/20 text-white p-4 rounded-xl text-left transition border border-white/20">
            <i class="fas fa-praying-hands text-green-400 mb-2"></i>
            <p class="font-semibold">Prayer Guidance</p>
            <p class="text-sm text-gray-300">Learn about effective prayer</p>
        </button>
        
        <button onclick="askQuestion('Can you share an encouraging Bible verse?')" 
                class="bg-white/10 hover:bg-white/20 text-white p-4 rounded-xl text-left transition border border-white/20">
            <i class="fas fa-book-bible text-purple-400 mb-2"></i>
            <p class="font-semibold">Bible Verses</p>
            <p class="text-sm text-gray-300">Get encouraging scriptures</p>
        </button>
        
        <button onclick="askQuestion('How can I grow spiritually?')" 
                class="bg-white/10 hover:bg-white/20 text-white p-4 rounded-xl text-left transition border border-white/20">
            <i class="fas fa-seedling text-blue-400 mb-2"></i>
            <p class="font-semibold">Spiritual Growth</p>
            <p class="text-sm text-gray-300">Tips for spiritual development</p>
        </button>
    </div>
</div>

@push('scripts')
<script>
const chatMessages = document.getElementById('chat-messages');
const chatForm = document.getElementById('chat-form');
const messageInput = document.getElementById('message-input');

// Handle form submission
chatForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const message = messageInput.value.trim();
    
    if (!message) return;
    
    // Add user message to chat
    addMessage(message, 'user');
    messageInput.value = '';
    
    // Show typing indicator
    const typingId = showTyping();
    
    try {
        // Send message to AI
        const response = await fetch('{{ route("ai.chat.send") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message })
        });
        
        const data = await response.json();
        
        // Remove typing indicator
        removeTyping(typingId);
        
        if (data.success) {
            addMessage(data.message, 'ai');
        } else {
            addMessage('Sorry, I encountered an error. Please try again.', 'ai');
        }
    } catch (error) {
        removeTyping(typingId);
        addMessage('Sorry, I encountered an error. Please try again.', 'ai');
    }
});

// Add message to chat
function addMessage(text, type) {
    const messageDiv = document.createElement('div');
    messageDiv.className = 'mb-4';
    
    if (type === 'user') {
        messageDiv.innerHTML = `
            <div class="flex items-start justify-end space-x-3">
                <div class="bg-indigo-600 text-white rounded-2xl rounded-tr-none px-4 py-3 max-w-2xl">
                    <p>${escapeHtml(text)}</p>
                </div>
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user text-white"></i>
                </div>
            </div>
        `;
    } else {
        messageDiv.innerHTML = `
            <div class="flex items-start space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-robot text-white"></i>
                </div>
                <div class="bg-gray-100 rounded-2xl rounded-tl-none px-4 py-3 max-w-2xl">
                    <p class="text-gray-800">${escapeHtml(text)}</p>
                </div>
            </div>
        `;
    }
    
    chatMessages.appendChild(messageDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Show typing indicator
function showTyping() {
    const typingDiv = document.createElement('div');
    typingDiv.className = 'mb-4 typing-indicator';
    typingDiv.id = 'typing-' + Date.now();
    typingDiv.innerHTML = `
        <div class="flex items-start space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-robot text-white"></i>
            </div>
            <div class="bg-gray-100 rounded-2xl rounded-tl-none px-4 py-3">
                <div class="flex space-x-2">
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                </div>
            </div>
        </div>
    `;
    chatMessages.appendChild(typingDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
    return typingDiv.id;
}

// Remove typing indicator
function removeTyping(id) {
    const typingDiv = document.getElementById(id);
    if (typingDiv) {
        typingDiv.remove();
    }
}

// Quick question function
function askQuestion(question) {
    messageInput.value = question;
    messageInput.focus();
}

// Escape HTML to prevent XSS
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>
@endpush
@endsection
