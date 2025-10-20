@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-900 via-purple-900 to-pink-900">
    
    <!-- Header -->
    <div class="sticky top-0 z-50 bg-black/30 backdrop-blur-xl border-b border-white/10">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-purple-600 rounded-full flex items-center justify-center animate-pulse">
                            <span class="text-2xl">🤖</span>
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-black"></div>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white">Divine AI Hub</h1>
                        <p class="text-white/60 text-sm">Next-Generation Church Intelligence</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-3">
                    <button onclick="toggleTheme()" class="p-2 rounded-lg bg-white/10 hover:bg-white/20 text-white">
                        🌙
                    </button>
                    <button onclick="showSettings()" class="p-2 rounded-lg bg-white/10 hover:bg-white/20 text-white">
                        ⚙️
                    </button>
                    <button onclick="newChat()" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2 rounded-xl font-semibold hover:opacity-90">
                        + New Chat
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6 py-6">
        <div class="grid grid-cols-12 gap-6 h-[calc(100vh-160px)]">
            
            <!-- LEFT SIDEBAR: Chat History & Tools -->
            <div class="col-span-3 space-y-4 overflow-y-auto">
                
                <!-- AI Modes -->
                <div class="bg-white/10 backdrop-blur rounded-2xl p-4">
                    <h3 class="text-white font-bold mb-3 flex items-center">
                        <span class="mr-2">🧠</span> AI Modes
                    </h3>
                    <div class="space-y-2">
                        <button onclick="selectMode('pastoral')" class="w-full text-left p-3 rounded-xl bg-purple-500/20 border-2 border-purple-500 text-white hover:bg-purple-500/30 transition">
                            <div class="flex items-center justify-between">
                                <span>✝️ Pastoral</span>
                                <span class="text-xs bg-purple-600 px-2 py-1 rounded-full">Active</span>
                            </div>
                        </button>
                        <button onclick="selectMode('admin')" class="w-full text-left p-3 rounded-xl bg-white/5 text-white/80 hover:bg-white/10 transition">
                            📊 Admin & Analytics
                        </button>
                        <button onclick="selectMode('media')" class="w-full text-left p-3 rounded-xl bg-white/5 text-white/80 hover:bg-white/10 transition">
                            🎬 Media Creation
                        </button>
                        <button onclick="selectMode('communication')" class="w-full text-left p-3 rounded-xl bg-white/5 text-white/80 hover:bg-white/10 transition">
                            💬 Communication
                        </button>
                        <button onclick="selectMode('children')" class="w-full text-left p-3 rounded-xl bg-white/5 text-white/80 hover:bg-white/10 transition">
                            🧒 Children Ministry
                        </button>
                        <button onclick="selectMode('worship')" class="w-full text-left p-3 rounded-xl bg-white/5 text-white/80 hover:bg-white/10 transition">
                            🎵 Worship Planning
                        </button>
                    </div>
                </div>

                <!-- Recent Chats -->
                <div class="bg-white/10 backdrop-blur rounded-2xl p-4">
                    <h3 class="text-white font-bold mb-3 flex items-center">
                        <span class="mr-2">💬</span> Recent Chats
                    </h3>
                    <div class="space-y-2">
                        <div onclick="loadChat('sermon-faith')" class="p-3 rounded-xl bg-white/5 hover:bg-white/10 cursor-pointer transition">
                            <p class="text-white font-semibold text-sm">Sermon Outline - Faith</p>
                            <p class="text-white/60 text-xs mt-1">2 hours ago • 12 messages</p>
                        </div>
                        <div onclick="loadChat('finance-report')" class="p-3 rounded-xl bg-white/5 hover:bg-white/10 cursor-pointer transition">
                            <p class="text-white font-semibold text-sm">Finance Report Summary</p>
                            <p class="text-white/60 text-xs mt-1">Yesterday • 5 messages</p>
                        </div>
                        <div onclick="loadChat('event-flyer')" class="p-3 rounded-xl bg-white/5 hover:bg-white/10 cursor-pointer transition">
                            <p class="text-white font-semibold text-sm">Event Flyer Design</p>
                            <p class="text-white/60 text-xs mt-1">2 days ago • 8 messages</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-gradient-to-br from-blue-500/20 to-purple-500/20 rounded-2xl p-4">
                    <h3 class="text-white font-bold mb-3">⚡ Quick Actions</h3>
                    <div class="space-y-2">
                        <button onclick="quickAction('sermon')" class="w-full text-left p-2 rounded-lg bg-white/10 text-white text-sm hover:bg-white/20 transition">
                            📝 Generate Sermon
                        </button>
                        <button onclick="quickAction('attendance')" class="w-full text-left p-2 rounded-lg bg-white/10 text-white text-sm hover:bg-white/20 transition">
                            📊 Analyze Attendance
                        </button>
                        <button onclick="quickAction('flyer')" class="w-full text-left p-2 rounded-lg bg-white/10 text-white text-sm hover:bg-white/20 transition">
                            🎨 Create Flyer
                        </button>
                        <button onclick="quickAction('announcement')" class="w-full text-left p-2 rounded-lg bg-white/10 text-white text-sm hover:bg-white/20 transition">
                            ✉️ Draft Announcement
                        </button>
                    </div>
                </div>

            </div>

            <!-- MIDDLE: Chat Area -->
            <div class="col-span-6 flex flex-col bg-white/5 backdrop-blur rounded-2xl overflow-hidden">
                
                <!-- Chat Messages -->
                <div id="chatMessages" class="flex-1 overflow-y-auto p-6 space-y-4">
                    
                    <!-- Welcome Message -->
                    <div class="text-center py-12">
                        <div class="inline-block p-6 bg-gradient-to-br from-blue-500/20 to-purple-500/20 rounded-full mb-4">
                            <span class="text-6xl">🕊️</span>
                        </div>
                        <h2 class="text-3xl font-bold text-white mb-3">Welcome to Divine AI Hub</h2>
                        <p class="text-white/60 text-lg mb-6">Your Intelligent Ministry Companion</p>
                        <div class="grid grid-cols-2 gap-3 max-w-md mx-auto">
                            <button onclick="quickAction('sermon')" class="p-4 bg-white/10 rounded-xl text-white hover:bg-white/20 transition">
                                <div class="text-2xl mb-2">✝️</div>
                                <div class="text-sm">Create Sermon</div>
                            </button>
                            <button onclick="viewAnalytics()" class="p-4 bg-white/10 rounded-xl text-white hover:bg-white/20 transition">
                                <div class="text-2xl mb-2">📊</div>
                                <div class="text-sm">View Analytics</div>
                            </button>
                            <button onclick="quickAction('flyer')" class="p-4 bg-white/10 rounded-xl text-white hover:bg-white/20 transition">
                                <div class="text-2xl mb-2">🎬</div>
                                <div class="text-sm">Design Media</div>
                            </button>
                            <button onclick="quickAction('announcement')" class="p-4 bg-white/10 rounded-xl text-white hover:bg-white/20 transition">
                                <div class="text-2xl mb-2">💬</div>
                                <div class="text-sm">Send Message</div>
                            </button>
                        </div>
                    </div>

                    <!-- Example User Message -->
                    <div class="flex justify-end">
                        <div class="max-w-md">
                            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-4 text-white">
                                <p>Help me create a sermon outline on the topic of faith</p>
                            </div>
                            <p class="text-white/40 text-xs mt-1 text-right">You • Just now</p>
                        </div>
                    </div>

                    <!-- Example AI Response -->
                    <div class="flex">
                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-600 rounded-full flex items-center justify-center mr-3">
                            🤖
                        </div>
                        <div class="flex-1">
                            <div class="bg-white/10 rounded-2xl p-4 text-white">
                                <h4 class="font-bold mb-2">📖 Sermon Outline: Walking in Faith</h4>
                                <div class="space-y-2 text-white/90">
                                    <p><strong>I. Introduction</strong></p>
                                    <p class="ml-4">• Define faith (Hebrews 11:1)</p>
                                    <p class="ml-4">• Why faith matters in daily life</p>
                                    
                                    <p class="mt-3"><strong>II. Examples of Faith</strong></p>
                                    <p class="ml-4">• Abraham's journey (Genesis 12)</p>
                                    <p class="ml-4">• David vs Goliath (1 Samuel 17)</p>
                                    
                                    <p class="mt-3"><strong>III. Practical Application</strong></p>
                                    <p class="ml-4">• Trust God in uncertainty</p>
                                    <p class="ml-4">• Step out in obedience</p>
                                </div>
                            </div>
                            <div class="flex items-center mt-2 space-x-2">
                                <p class="text-white/40 text-xs">AI Assistant • Pastoral Mode</p>
                                <button class="text-white/60 hover:text-white">👍</button>
                                <button class="text-white/60 hover:text-white">👎</button>
                                <button class="text-white/60 hover:text-white">📋 Copy</button>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Input Area -->
                <div class="border-t border-white/10 p-4">
                    <div class="flex items-end space-x-3">
                        <div class="flex-1 relative">
                            <textarea 
                                id="messageInput" 
                                placeholder="Ask anything... Type / for commands"
                                rows="2"
                                class="w-full bg-white/10 text-white rounded-xl px-4 py-3 resize-none focus:outline-none focus:ring-2 focus:ring-purple-500 placeholder-white/40"
                            ></textarea>
                            <div class="absolute bottom-3 right-3 flex space-x-2">
                                <button onclick="attachFile()" class="text-white/60 hover:text-white">📎</button>
                                <button onclick="startVoiceInput()" class="text-white/60 hover:text-white">🎤</button>
                            </div>
                        </div>
                        <button onclick="sendMessage()" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-4 rounded-xl hover:opacity-90 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </div>
                    <p class="text-white/40 text-xs mt-2">AI can make mistakes. Verify important information.</p>
                </div>

            </div>

            <!-- RIGHT SIDEBAR: Context & Tools -->
            <div class="col-span-3 space-y-4 overflow-y-auto">
                
                <!-- Live Church Stats -->
                <div class="bg-white/10 backdrop-blur rounded-2xl p-4">
                    <h3 class="text-white font-bold mb-3 flex items-center">
                        <span class="mr-2">📊</span> Live Stats
                    </h3>
                    <div class="space-y-3">
                        <div onclick="viewStat('attendance')" class="bg-gradient-to-br from-blue-500/20 to-cyan-500/20 rounded-xl p-3 cursor-pointer hover:scale-105 transition">
                            <p class="text-white/60 text-xs">This Week</p>
                            <p class="text-white font-bold text-2xl">342</p>
                            <p class="text-white text-sm">Attendance</p>
                        </div>
                        <div onclick="viewStat('finance')" class="bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-xl p-3 cursor-pointer hover:scale-105 transition">
                            <p class="text-white/60 text-xs">This Month</p>
                            <p class="text-white font-bold text-2xl">GH₵ 12,450</p>
                            <p class="text-white text-sm">Tithes & Offerings</p>
                        </div>
                        <div onclick="viewStat('members')" class="bg-gradient-to-br from-purple-500/20 to-pink-500/20 rounded-xl p-3 cursor-pointer hover:scale-105 transition">
                            <p class="text-white/60 text-xs">New This Month</p>
                            <p class="text-white font-bold text-2xl">18</p>
                            <p class="text-white text-sm">New Members</p>
                        </div>
                    </div>
                    <button onclick="viewAnalytics()" class="w-full mt-3 bg-white/10 text-white py-2 rounded-lg hover:bg-white/20 transition text-sm">
                        📈 View Full Analytics
                    </button>
                </div>

                <!-- Prompt Library -->
                <div class="bg-white/10 backdrop-blur rounded-2xl p-4">
                    <h3 class="text-white font-bold mb-3 flex items-center">
                        <span class="mr-2">📚</span> Prompt Library
                    </h3>
                    <div class="space-y-2">
                        <button onclick="usePrompt('sermon')" class="w-full text-left p-3 rounded-xl bg-white/5 text-white/90 hover:bg-white/10 transition text-sm">
                            <div class="flex items-center justify-between">
                                <span>✍️ Write a sermon</span>
                                <span class="text-xs text-white/60">→</span>
                            </div>
                        </button>
                        <button onclick="usePrompt('event')" class="w-full text-left p-3 rounded-xl bg-white/5 text-white/90 hover:bg-white/10 transition text-sm">
                            <div class="flex items-center justify-between">
                                <span>🎯 Plan an event</span>
                                <span class="text-xs text-white/60">→</span>
                            </div>
                        </button>
                        <button onclick="usePrompt('birthday')" class="w-full text-left p-3 rounded-xl bg-white/5 text-white/90 hover:bg-white/10 transition text-sm">
                            <div class="flex items-center justify-between">
                                <span>💌 Birthday message</span>
                                <span class="text-xs text-white/60">→</span>
                            </div>
                        </button>
                        <button onclick="usePrompt('newsletter')" class="w-full text-left p-3 rounded-xl bg-white/5 text-white/90 hover:bg-white/10 transition text-sm">
                            <div class="flex items-center justify-between">
                                <span>📰 Newsletter draft</span>
                                <span class="text-xs text-white/60">→</span>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- AI Features -->
                <div class="bg-gradient-to-br from-yellow-500/20 to-orange-500/20 rounded-2xl p-4">
                    <h3 class="text-white font-bold mb-3">✨ AI Capabilities</h3>
                    <div class="space-y-2 text-white/80 text-sm">
                        <div class="flex items-center">
                            <span class="mr-2">✅</span>
                            <span>Generate sermons & devotionals</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-2">✅</span>
                            <span>Analyze church data</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-2">✅</span>
                            <span>Create visual designs</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-2">✅</span>
                            <span>Draft communications</span>
                        </div>
                        <div class="flex items-center">
                            <span class="mr-2">✅</span>
                            <span>Voice conversations</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<script>
let currentMode = 'pastoral';

// Select AI Mode
function selectMode(mode) {
    currentMode = mode;
    
    // Remove active state from all mode buttons
    document.querySelectorAll('[onclick^="selectMode"]').forEach(btn => {
        btn.className = 'w-full text-left p-3 rounded-xl bg-white/5 text-white/80 hover:bg-white/10 transition';
    });
    
    // Add active state to clicked button
    event.currentTarget.className = 'w-full text-left p-3 rounded-xl bg-purple-500/20 border-2 border-purple-500 text-white hover:bg-purple-500/30 transition';
    
    // Update mode indicator
    const modeNames = {
        pastoral: '✝️ Pastoral Mode',
        admin: '📊 Admin Mode',
        media: '🎬 Media Mode',
        communication: '💬 Communication Mode',
        children: '🧒 Children Mode',
        worship: '🎵 Worship Mode'
    };
    
    showNotification('Switched to ' + modeNames[mode]);
}

// New Chat
function newChat() {
    if(confirm('Start a new conversation? Current chat will be saved.')) {
        const chatMessages = document.getElementById('chatMessages');
        chatMessages.innerHTML = `
            <div class="text-center py-12">
                <div class="inline-block p-6 bg-gradient-to-br from-blue-500/20 to-purple-500/20 rounded-full mb-4">
                    <span class="text-6xl">🕊️</span>
                </div>
                <h2 class="text-3xl font-bold text-white mb-3">New Conversation Started</h2>
                <p class="text-white/60 text-lg">Ask me anything about your church ministry</p>
            </div>
        `;
        document.getElementById('messageInput').value = '';
        showNotification('New chat started!');
    }
}

// Toggle Theme
function toggleTheme() {
    const body = document.body;
    if (body.classList.contains('dark-theme')) {
        body.classList.remove('dark-theme');
        showNotification('Switched to Light Theme');
    } else {
        body.classList.add('dark-theme');
        showNotification('Switched to Dark Theme');
    }
}

// Show Settings
function showSettings() {
    window.location.href = '{{ route("settings.dashboard") }}';
}

// Send Message
function sendMessage() {
    const input = document.getElementById('messageInput');
    const message = input.value.trim();
    
    if (!message) {
        showNotification('Please enter a message', 'error');
        return;
    }
    
    // Add user message to chat
    addMessageToChat('user', message);
    input.value = '';
    
    // Show typing indicator
    showTypingIndicator();
    
    // Simulate AI response (replace with actual API call)
    setTimeout(() => {
        hideTypingIndicator();
        const response = generateAIResponse(message, currentMode);
        addMessageToChat('ai', response);
    }, 1500);
}

// Add Message to Chat
function addMessageToChat(sender, message) {
    const chatMessages = document.getElementById('chatMessages');
    
    if (sender === 'user') {
        const userMessage = `
            <div class="flex justify-end">
                <div class="max-w-md">
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-4 text-white">
                        <p>${escapeHtml(message)}</p>
                    </div>
                    <p class="text-white/40 text-xs mt-1 text-right">You • Just now</p>
                </div>
            </div>
        `;
        chatMessages.insertAdjacentHTML('beforeend', userMessage);
    } else {
        const aiMessage = `
            <div class="flex">
                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-600 rounded-full flex items-center justify-center mr-3">
                    🤖
                </div>
                <div class="flex-1">
                    <div class="bg-white/10 rounded-2xl p-4 text-white">
                        <p>${message}</p>
                    </div>
                    <div class="flex items-center mt-2 space-x-2">
                        <p class="text-white/40 text-xs">AI Assistant • ${getCurrentModeLabel()}</p>
                        <button onclick="copyMessage(this)" class="text-white/60 hover:text-white text-xs">📋 Copy</button>
                    </div>
                </div>
            </div>
        `;
        chatMessages.insertAdjacentHTML('beforeend', aiMessage);
    }
    
    // Scroll to bottom
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Generate AI Response (Mock - replace with actual API)
function generateAIResponse(message, mode) {
    const responses = {
        pastoral: `Based on your question about "${message}", here's a pastoral perspective: God's word provides guidance in all situations. Consider these scriptures and practical applications for your ministry...`,
        admin: `Analyzing your request about "${message}"... Here are the key insights from your church data and recommended actions for effective administration...`,
        media: `For "${message}", I suggest creating visual content with these elements: modern design, engaging colors, and clear messaging that resonates with your congregation...`,
        communication: `I've drafted a message for "${message}": Dear beloved members, we want to share this important update with you... [Message content here]`,
        children: `For children's ministry regarding "${message}", here's a fun and engaging lesson plan with activities, games, and memorable teaching points...`,
        worship: `For worship planning about "${message}", consider these song selections, flow ideas, and spiritual atmosphere elements for a powerful service...`
    };
    
    return responses[mode] || 'Thank you for your message. How can I assist you with your church ministry today?';
}

// Typing Indicator
function showTypingIndicator() {
    const chatMessages = document.getElementById('chatMessages');
    const indicator = `
        <div id="typingIndicator" class="flex items-center space-x-2">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-600 rounded-full flex items-center justify-center">
                🤖
            </div>
            <div class="bg-white/10 rounded-2xl px-6 py-3">
                <div class="flex space-x-2">
                    <div class="w-2 h-2 bg-white rounded-full animate-bounce"></div>
                    <div class="w-2 h-2 bg-white rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                    <div class="w-2 h-2 bg-white rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                </div>
            </div>
        </div>
    `;
    chatMessages.insertAdjacentHTML('beforeend', indicator);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function hideTypingIndicator() {
    const indicator = document.getElementById('typingIndicator');
    if (indicator) indicator.remove();
}

// Get Current Mode Label
function getCurrentModeLabel() {
    const labels = {
        pastoral: 'Pastoral Mode',
        admin: 'Admin Mode',
        media: 'Media Mode',
        communication: 'Communication Mode',
        children: 'Children Mode',
        worship: 'Worship Mode'
    };
    return labels[currentMode] || 'AI Mode';
}

// Attach File
function attachFile() {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*,application/pdf,.doc,.docx';
    input.onchange = (e) => {
        const file = e.target.files[0];
        if (file) {
            showNotification(`File "${file.name}" ready to upload`);
            // Handle file upload here
        }
    };
    input.click();
}

// Voice Input
function startVoiceInput() {
    if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        const recognition = new SpeechRecognition();
        
        recognition.onstart = () => {
            showNotification('Listening... Speak now');
        };
        
        recognition.onresult = (event) => {
            const transcript = event.results[0][0].transcript;
            document.getElementById('messageInput').value = transcript;
            showNotification('Voice input captured');
        };
        
        recognition.onerror = () => {
            showNotification('Voice input not available', 'error');
        };
        
        recognition.start();
    } else {
        showNotification('Voice input not supported in this browser', 'error');
    }
}

// Copy Message
function copyMessage(btn) {
    const messageText = btn.closest('.flex-1').querySelector('.bg-white\\/10 p').textContent;
    navigator.clipboard.writeText(messageText).then(() => {
        showNotification('Message copied to clipboard!');
    });
}

// Notification System
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-20 right-6 z-50 px-6 py-3 rounded-xl text-white font-semibold shadow-lg transform transition-all duration-300 ${
        type === 'error' ? 'bg-red-500' : 'bg-green-500'
    }`;
    notification.textContent = message;
    notification.style.opacity = '0';
    notification.style.transform = 'translateY(-20px)';
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateY(0)';
    }, 10);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(-20px)';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Load Chat History
function loadChat(chatId) {
    const chatTitles = {
        'sermon-faith': 'Sermon Outline - Faith',
        'finance-report': 'Finance Report Summary',
        'event-flyer': 'Event Flyer Design'
    };
    
    showNotification(`Loading "${chatTitles[chatId]}"...`);
    
    // Simulate loading chat history
    setTimeout(() => {
        const chatMessages = document.getElementById('chatMessages');
        chatMessages.innerHTML = `
            <div class="text-center py-8">
                <h3 class="text-2xl font-bold text-white mb-2">${chatTitles[chatId]}</h3>
                <p class="text-white/60 mb-4">Previous conversation loaded</p>
            </div>
        `;
        showNotification('Chat loaded successfully!');
    }, 500);
}

// Quick Actions
function quickAction(action) {
    const prompts = {
        sermon: 'Help me write a powerful sermon outline',
        attendance: 'Analyze our church attendance data for this month',
        flyer: 'Design a modern flyer for our upcoming youth event',
        announcement: 'Draft an announcement for Sunday service'
    };
    
    const prompt = prompts[action];
    if (prompt) {
        document.getElementById('messageInput').value = prompt;
        showNotification(`Prompt inserted: ${action}`);
        // Optionally auto-send
        // sendMessage();
    }
}

// View Analytics
function viewAnalytics() {
    window.location.href = '{{ route("dashboard") }}';
}

// View Specific Stat
function viewStat(statType) {
    const routes = {
        attendance: '{{ route("dashboard") }}',
        finance: '{{ route("finance.dashboard") }}',
        members: '{{ route("members.index") }}'
    };
    
    const statNames = {
        attendance: 'Attendance Details',
        finance: 'Finance Dashboard',
        members: 'Members List'
    };
    
    showNotification(`Opening ${statNames[statType]}...`);
    setTimeout(() => {
        window.location.href = routes[statType];
    }, 500);
}

// Use Prompt from Library
function usePrompt(promptType) {
    const prompts = {
        sermon: 'Write a comprehensive sermon outline on [TOPIC]. Include: introduction, 3 main points with scripture references, practical applications, and a powerful conclusion.',
        event: 'Help me plan a church event. I need: event concept, timeline, budget breakdown, volunteer roles, and promotional strategy.',
        birthday: 'Generate a heartfelt birthday message for a church member. Make it encouraging, faith-filled, and personalized.',
        newsletter: 'Draft a church newsletter for this month. Include: pastor\'s message, upcoming events, testimonies section, and prayer requests.'
    };
    
    const input = document.getElementById('messageInput');
    input.value = prompts[promptType] || '';
    input.focus();
    
    // Auto-resize
    input.style.height = 'auto';
    input.style.height = (input.scrollHeight) + 'px';
    
    showNotification('Prompt template loaded! Edit and send.');
}

// Auto-resize textarea
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('messageInput');
    
    input.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
    
    // Send on Enter (Shift+Enter for new line)
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });
});
</script>
@endsection
