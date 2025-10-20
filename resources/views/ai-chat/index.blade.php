@extends('layouts.app')

@section('content')
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

* { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }

.modern-chat-bg {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
}

.modern-sidebar {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(20px);
    box-shadow: 4px 0 24px rgba(0, 0, 0, 0.08);
}

.modern-main {
    background: #ffffff;
}

.assistant-btn {
    background: linear-gradient(135deg, #667eea08 0%, #764ba208 100%);
    border: 1px solid #e5e7eb;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.assistant-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.15);
    border-color: #667eea;
}

.assistant-btn.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: transparent;
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);
}

.assistant-btn.active * { color: white !important; }

.msg-user {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 18px 18px 4px 18px;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2);
    padding: 12px 16px;
    max-width: 70%;
}

.msg-ai {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    color: #1f2937;
    border-radius: 18px 18px 18px 4px;
    padding: 12px 16px;
    max-width: 80%;
}

.typing-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #6366f1;
    animation: typing 1.4s infinite;
    display: inline-block;
}

.typing-dot:nth-child(2) { animation-delay: 0.2s; }
.typing-dot:nth-child(3) { animation-delay: 0.4s; }

@keyframes typing {
    0%, 60%, 100% { transform: translateY(0); opacity: 0.5; }
    30% { transform: translateY(-10px); opacity: 1; }
}

@keyframes slideIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.message-animate { animation: slideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1); }

.input-modern {
    border: 2px solid #e5e7eb;
    transition: all 0.3s ease;
}

.input-modern:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    outline: none;
}

.btn-send {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    transition: all 0.3s ease;
}

.btn-send:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

::-webkit-scrollbar { width: 6px; }
::-webkit-scrollbar-track { background: #f3f4f6; }
::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
</style>

<div class="modern-chat-bg flex h-screen">
    
    <!-- Modern Sidebar -->
    <div class="modern-sidebar w-80 flex flex-col">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Church AI</h1>
                    <p class="text-xs text-gray-500">Powered by Intelligence</p>
                </div>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-2">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 px-2">Assistants</p>
            
            @foreach([
                ['emoji' => '📖', 'name' => 'Sermon Builder', 'desc' => 'Outlines & scriptures', 'role' => 'sermon'],
                ['emoji' => '📚', 'name' => 'Bible Study', 'desc' => 'Study guides', 'role' => 'bible-study'],
                ['emoji' => '📱', 'name' => 'SMS Writer', 'desc' => 'Messages & alerts', 'role' => 'sms'],
                ['emoji' => '🙏', 'name' => 'Prayer Writer', 'desc' => 'Prayer points', 'role' => 'prayer'],
                ['emoji' => '💰', 'name' => 'Finance Analyst', 'desc' => 'Reports & insights', 'role' => 'finance'],
                ['emoji' => '📋', 'name' => 'Meeting Minutes', 'desc' => 'Documentation', 'role' => 'minutes'],
                ['emoji' => '✉️', 'name' => 'Letter Writer', 'desc' => 'Correspondence', 'role' => 'letter'],
                ['emoji' => '📊', 'name' => 'Report Generator', 'desc' => 'Data reports', 'role' => 'report']
            ] as $assistant)
            <button onclick="selectAssistant('{{ $assistant['name'] }}', '{{ $assistant['role'] }}')" 
                    class="assistant-btn w-full p-3 rounded-xl text-left" data-role="{{ $assistant['role'] }}">
                <div class="flex items-center space-x-3">
                    <span class="text-2xl">{{ $assistant['emoji'] }}</span>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-900 text-sm truncate">{{ $assistant['name'] }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $assistant['desc'] }}</p>
                    </div>
                </div>
            </button>
            @endforeach
        </div>

        <div class="p-4 border-t border-gray-200">
            <button onclick="showHistory()" class="w-full flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium text-sm">History</span>
            </button>
        </div>
    </div>

    <!-- Main Chat Area -->
    <div class="modern-main flex-1 flex flex-col">
        
        <!-- Top Header -->
        <div class="bg-white border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 id="currentAssistant" class="text-lg font-bold text-gray-900">General Assistant</h2>
                        <div class="flex items-center space-x-2">
                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                            <span class="text-xs text-gray-500">Online</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="exportChat()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors" title="Export">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                    </button>
                    <button onclick="clearChat()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors" title="Clear">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Messages Container -->
        <div id="messagesContainer" class="flex-1 overflow-y-auto px-6 py-6 space-y-6 bg-gray-50">
            <!-- Welcome Message -->
            <div class="max-w-3xl mx-auto">
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Welcome to Church AI Assistant</h3>
                    <p class="text-gray-600 mb-8">Your intelligent partner for ministry work</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-8">
                        <button onclick="sendQuickPrompt('Help me create a sermon outline')" class="bg-gradient-to-br from-purple-50 to-indigo-50 border border-purple-200 p-4 rounded-xl text-left hover:shadow-lg transition group">
                            <i class="fas fa-book-bible text-purple-600 text-xl mr-3"></i>
                            <span class="text-gray-900 font-semibold group-hover:text-purple-600 transition">Create sermon outline</span>
                            <p class="text-gray-600 text-sm mt-1">Get structured sermon outlines with scripture</p>
                        </button>
                        <button onclick="sendQuickPrompt('Show me attendance trends')" class="bg-gradient-to-br from-blue-50 to-cyan-50 border border-blue-200 p-4 rounded-xl text-left hover:shadow-lg transition group">
                            <i class="fas fa-chart-line text-blue-600 text-xl mr-3"></i>
                            <span class="text-gray-900 font-semibold group-hover:text-blue-600 transition">Analyze attendance</span>
                            <p class="text-gray-600 text-sm mt-1">Get insights on attendance trends</p>
                        </button>
                        <button onclick="sendQuickPrompt('Draft a prayer for our church')" class="bg-gradient-to-br from-pink-50 to-rose-50 border border-pink-200 p-4 rounded-xl text-left hover:shadow-lg transition group">
                            <i class="fas fa-praying-hands text-pink-600 text-xl mr-3"></i>
                            <span class="text-gray-900 font-semibold group-hover:text-pink-600 transition">Draft a prayer</span>
                            <p class="text-gray-600 text-sm mt-1">Create prayers for different occasions</p>
                        </button>
                        <button onclick="sendQuickPrompt('Help me with financial reports')" class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 p-4 rounded-xl text-left hover:shadow-lg transition group">
                            <i class="fas fa-dollar-sign text-green-600 text-xl mr-3"></i>
                            <span class="text-gray-900 font-semibold group-hover:text-green-600 transition">Financial guidance</span>
                            <p class="text-gray-600 text-sm mt-1">Help with church financial management</p>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="bg-white border-t border-gray-200 p-6">
            <form id="chatForm" class="space-y-4">
                <!-- Suggested Prompts -->
                <div id="suggestedPrompts" class="flex flex-wrap gap-2 mb-4">
                    <button type="button" onclick="sendQuickPrompt('Hello! How can you help me?')" class="bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-full text-gray-700 font-medium text-sm transition">
                        👋 Get started
                    </button>
                    <button type="button" onclick="sendQuickPrompt('What features do you have?')" class="bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-full text-gray-700 font-medium text-sm transition">
                        ℹ️ Features
                    </button>
                    <button type="button" onclick="sendQuickPrompt('Help me plan an event')" class="bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-full text-gray-700 font-medium text-sm transition">
                        📅 Event planning
                    </button>
                </div>

                <div class="flex space-x-3">
                    <div class="flex-1 relative">
                        <textarea 
                            id="messageInput"
                            rows="1"
                            placeholder="Ask me anything about church management, ministry, or get spiritual guidance..."
                            class="w-full px-6 py-4 pr-24 bg-gray-50 border-2 border-gray-200 rounded-2xl text-gray-900 placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition resize-none"
                            onkeydown="handleKeyPress(event)"></textarea>
                        
                        <div class="absolute right-4 top-1/2 transform -translate-y-1/2 flex space-x-2">
                            <button type="button" onclick="attachFile()" class="text-gray-400 hover:text-gray-600 transition" title="Attach file">
                                <i class="fas fa-paperclip"></i>
                            </button>
                            <button type="button" onclick="toggleVoice()" class="text-gray-400 hover:text-gray-600 transition" title="Voice input">
                                <i class="fas fa-microphone"></i>
                            </button>
                        </div>
                    </div>

                    <button 
                        type="submit"
                        id="sendButton"
                        class="px-8 py-4 rounded-2xl font-bold text-white bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 transition-all hover:scale-105 shadow-lg">
                        <i class="fas fa-paper-plane mr-2"></i>Send
                    </button>
                </div>

                <p class="text-gray-500 text-xs text-center">
                    <i class="fas fa-info-circle mr-1"></i>
                    AI responses may vary. Always verify important information.
                </p>
            </form>
        </div>
    </div>
</div>

<script>
let conversationHistory = [];

function selectAssistant(name) {
    document.getElementById('currentAssistant').textContent = name;
    addSystemMessage(`Switched to ${name}. How can I assist you?`);
}

function sendQuickPrompt(prompt) {
    document.getElementById('messageInput').value = prompt;
    document.getElementById('chatForm').dispatchEvent(new Event('submit'));
}

function handleKeyPress(event) {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        document.getElementById('chatForm').dispatchEvent(new Event('submit'));
    }
}

document.getElementById('chatForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const input = document.getElementById('messageInput');
    const message = input.value.trim();
    
    if (!message) return;
    
    // Hide suggested prompts after first message
    document.getElementById('suggestedPrompts').style.display = 'none';
    
    // Add user message
    addMessage(message, 'user');
    input.value = '';
    
    // Show typing indicator
    const typingId = showTypingIndicator();
    
    // Simulate AI response (replace with actual API call)
    setTimeout(() => {
        removeTypingIndicator(typingId);
        const response = getAIResponse(message);
        addMessage(response, 'assistant');
    }, 1500);
});

function addMessage(content, role) {
    const container = document.getElementById('messagesContainer');
    const messageDiv = document.createElement('div');
    messageDiv.className = 'max-w-4xl mx-auto message-animate';
    
    const isUser = role === 'user';
    const alignment = isUser ? 'ml-auto' : '';
    const bgColor = isUser ? 'bg-gradient-to-br from-purple-500 to-indigo-600' : 'bg-white border border-gray-200 shadow-sm';
    const textColor = isUser ? 'text-white' : 'text-gray-800';
    const timeColor = isUser ? 'text-white/70' : 'text-gray-500';
    const icon = isUser ? 'fa-user' : 'fa-robot';
    const iconBg = isUser ? 'bg-gradient-to-br from-purple-500 to-indigo-600' : 'bg-gradient-to-br from-blue-500 to-cyan-600';
    
    messageDiv.innerHTML = `
        <div class="flex items-start space-x-4 ${alignment}">
            <div class="w-10 h-10 ${iconBg} rounded-full flex items-center justify-center flex-shrink-0 shadow-lg">
                <i class="fas ${icon} text-white"></i>
            </div>
            <div class="flex-1 ${bgColor} p-4 rounded-2xl">
                <div class="${textColor} whitespace-pre-wrap">${formatMessage(content)}</div>
                <div class="${timeColor} text-xs mt-2">${new Date().toLocaleTimeString()}</div>
            </div>
        </div>
    `;
    
    container.appendChild(messageDiv);
    container.scrollTop = container.scrollHeight;
    
    conversationHistory.push({ role, content, timestamp: new Date() });
}

function addSystemMessage(content) {
    const container = document.getElementById('messagesContainer');
    const messageDiv = document.createElement('div');
    messageDiv.className = 'max-w-4xl mx-auto message-animate';
    messageDiv.innerHTML = `
        <div class="text-center">
            <span class="bg-gray-100 border border-gray-200 px-4 py-2 rounded-full text-gray-600 text-sm shadow-sm">
                <i class="fas fa-info-circle mr-2 text-blue-500"></i>${content}
            </span>
        </div>
    `;
    container.appendChild(messageDiv);
    container.scrollTop = container.scrollHeight;
}

function showTypingIndicator() {
    const container = document.getElementById('messagesContainer');
    const typingDiv = document.createElement('div');
    const id = 'typing-' + Date.now();
    typingDiv.id = id;
    typingDiv.className = 'max-w-4xl mx-auto';
    typingDiv.innerHTML = `
        <div class="flex items-start space-x-4">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-full flex items-center justify-center flex-shrink-0 shadow-lg">
                <i class="fas fa-robot text-white"></i>
            </div>
            <div class="bg-white border border-gray-200 shadow-sm p-4 rounded-2xl">
                <div class="typing-indicator flex space-x-1">
                    <span class="typing-dot"></span>
                    <span class="typing-dot"></span>
                    <span class="typing-dot"></span>
                </div>
            </div>
        </div>
    `;
    container.appendChild(typingDiv);
    container.scrollTop = container.scrollHeight;
    return id;
}

function removeTypingIndicator(id) {
    const element = document.getElementById(id);
    if (element) element.remove();
}

function formatMessage(content) {
    // Simple markdown-like formatting
    return content
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
        .replace(/\*(.*?)\*/g, '<em>$1</em>')
        .replace(/\n/g, '<br>');
}

function getAIResponse(message) {
    const msg = message.toLowerCase();
    
    if (msg.includes('hello') || msg.includes('hi')) {
        return "Hello! 👋 I'm your AI Church Assistant. I can help you with:\n\n• Sermon preparation and Bible study\n• Prayer ministry and spiritual guidance\n• Administrative tasks and reports\n• Event planning and coordination\n• Financial management\n• Member engagement strategies\n\nWhat would you like help with today?";
    }
    
    if (msg.includes('sermon')) {
        return "I'd be happy to help with your sermon! Here's a suggested outline:\n\n**Introduction** (5 min)\n• Hook: Relevant story or question\n• Scripture reading\n\n**Body** (20 min)\n• Point 1: Context and background\n• Point 2: Application to daily life\n• Point 3: Call to action\n\n**Conclusion** (5 min)\n• Summary\n• Prayer\n\nWould you like me to develop any specific section?";
    }
    
    if (msg.includes('prayer')) {
        return "Here's a sample prayer for your church:\n\n**Heavenly Father,**\n\nWe come before You with grateful hearts, thanking You for Your endless love and mercy. We lift up our church family to You today.\n\nGrant us wisdom in our decisions, strength in our challenges, and unity in our fellowship. Help us to be lights in our community and faithful stewards of all You've blessed us with.\n\nIn Jesus' name, Amen.\n\nWould you like me to customize this for a specific occasion?";
    }
    
    if (msg.includes('event') || msg.includes('plan')) {
        return "Let me help you plan your event! Here's a checklist:\n\n✅ **Pre-Event** (4 weeks before)\n• Set date and venue\n• Create budget\n• Form planning committee\n\n✅ **Planning** (2 weeks before)\n• Send invitations\n• Arrange catering\n• Prepare program\n\n✅ **Final Week**\n• Confirm all vendors\n• Brief volunteers\n• Prepare materials\n\nWhat type of event are you planning?";
    }
    
    return "Thank you for your question! I'm here to assist you with:\n\n• **Ministry Planning** - Sermons, Bible studies, prayers\n• **Church Administration** - Reports, bulletins, communications\n• **Member Care** - Pastoral support, counseling resources\n• **Financial Management** - Budgets, reports, analysis\n• **Event Coordination** - Planning and execution\n\nCould you provide more details so I can give you a more specific answer?";
}

function clearChat() {
    if (confirm('Clear all messages? This cannot be undone.')) {
        const container = document.getElementById('messagesContainer');
        container.innerHTML = '';
        conversationHistory = [];
        document.getElementById('suggestedPrompts').style.display = 'flex';
        addSystemMessage('Chat cleared. Start a new conversation!');
    }
}

function exportChat() {
    if (conversationHistory.length === 0) {
        alert('No messages to export!');
        return;
    }
    
    let text = '='.repeat(60) + '\n';
    text += 'CHURCH MANAGEMENT AI CHAT EXPORT\n';
    text += '='.repeat(60) + '\n\n';
    text += 'Exported: ' + new Date().toLocaleString() + '\n\n';
    text += '-'.repeat(60) + '\n\n';
    
    conversationHistory.forEach(msg => {
        const role = msg.role === 'user' ? 'YOU' : 'AI ASSISTANT';
        const time = msg.timestamp.toLocaleTimeString();
        text += `[${time}] ${role}:\n${msg.content}\n\n`;
    });
    
    text += '='.repeat(60) + '\n';
    text += 'End of conversation\n';
    
    const blob = new Blob([text], { type: 'text/plain' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'chat-export-' + Date.now() + '.txt';
    a.click();
}

function toggleSettings() {
    alert('Settings panel coming soon! Configure AI behavior, language preferences, and more.');
}

function showQuickActions() {
    alert('Quick Actions:\n\n• Generate Announcement\n• Create Prayer List\n• Draft Report\n• Plan Event\n\nComing soon!');
}

function showHistory() {
    alert('Chat History feature coming soon! View and restore previous conversations.');
}

function attachFile() {
    alert('File attachment coming soon! Upload documents for AI analysis.');
}

function toggleVoice() {
    alert('Voice input coming soon! Speak your messages instead of typing.');
}
</script>
@endsection
