@extends('layouts.volunteer')

@section('content')
<div>
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <h1 class="text-3xl font-bold text-gray-800">🧠 AI Helper</h1>
        <p class="text-gray-600">Your personal assistant for volunteering</p>
    </div>

    <!-- AI Chat Interface -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Quick Actions Sidebar -->
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="font-bold text-gray-800 mb-4">Quick Help</h3>
                <div class="space-y-2">
                    <button onclick="quickAction('next_assignment')" class="w-full text-left px-4 py-3 bg-orange-50 hover:bg-orange-100 rounded-lg text-sm font-semibold text-orange-700 transition-all">
                        <i class="fas fa-calendar-check mr-2"></i>Next Assignment
                    </button>
                    <button onclick="quickAction('task_tips')" class="w-full text-left px-4 py-3 bg-blue-50 hover:bg-blue-100 rounded-lg text-sm font-semibold text-blue-700 transition-all">
                        <i class="fas fa-lightbulb mr-2"></i>Task Tips
                    </button>
                    <button onclick="quickAction('daily_verse')" class="w-full text-left px-4 py-3 bg-purple-50 hover:bg-purple-100 rounded-lg text-sm font-semibold text-purple-700 transition-all">
                        <i class="fas fa-bible mr-2"></i>Daily Verse
                    </button>
                    <button onclick="quickAction('event_prep')" class="w-full text-left px-4 py-3 bg-green-50 hover:bg-green-100 rounded-lg text-sm font-semibold text-green-700 transition-all">
                        <i class="fas fa-question-circle mr-2"></i>Event Prep
                    </button>
                </div>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-2xl shadow-lg flex flex-col" style="height: 600px;">
                <!-- Chat Messages -->
                <div id="chatMessages" class="flex-1 overflow-y-auto p-6 space-y-4">
                    <!-- AI Welcome Message -->
                    <div class="flex items-start space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-robot text-white"></i>
                        </div>
                        <div class="bg-gray-100 rounded-2xl rounded-tl-none px-4 py-3 max-w-lg">
                            <p class="text-gray-800">Hello! I'm your AI volunteer assistant. How can I help you today?</p>
                            <ul class="mt-2 space-y-1 text-sm text-gray-700">
                                <li>• Check your next assignment</li>
                                <li>• Get task completion tips</li>
                                <li>• Find motivational verses</li>
                                <li>• Prepare for upcoming events</li>
                            </ul>
                        </div>
                    </div>

                    <!-- User Message Example -->
                    <div class="flex items-start space-x-3 justify-end">
                        <div class="bg-orange-600 text-white rounded-2xl rounded-tr-none px-4 py-3 max-w-lg">
                            <p>What's my next assignment?</p>
                        </div>
                        <div class="w-10 h-10 bg-orange-200 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user text-orange-700"></i>
                        </div>
                    </div>

                    <!-- AI Response Example -->
                    <div class="flex items-start space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-robot text-white"></i>
                        </div>
                        <div class="bg-gray-100 rounded-2xl rounded-tl-none px-4 py-3 max-w-lg">
                            <p class="text-gray-800 font-semibold mb-2">Your Next Assignment:</p>
                            <div class="text-sm text-gray-700 space-y-2">
                                <p><strong>Event:</strong> Sunday Worship Service</p>
                                <p><strong>Date:</strong> This Sunday, 9:00 AM</p>
                                <p><strong>Role:</strong> Ushering Team</p>
                                <p><strong>Tasks:</strong></p>
                                <ul class="list-disc ml-4">
                                    <li>Arrive by 8:30 AM</li>
                                    <li>Welcome guests at main entrance</li>
                                    <li>Assist with seating</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Input Area -->
                <div class="border-t p-4">
                    <div class="flex space-x-3">
                        <input type="text" id="chatInput" placeholder="Ask me anything..." class="flex-1 border border-gray-300 rounded-lg px-4 py-3 focus:border-orange-500 focus:ring-2 focus:ring-orange-200" onkeypress="if(event.key==='Enter') sendMessage()">
                        <button onclick="sendMessage()" class="bg-orange-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-orange-700 transition-all">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                        <button onclick="startVoiceInput()" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-all">
                            <i class="fas fa-microphone"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Send Chat Message
function sendMessage() {
    const input = document.getElementById('chatInput');
    const message = input.value.trim();
    
    if (!message) return;
    
    // Add user message to chat
    addMessageToChat(message, 'user');
    
    // Clear input
    input.value = '';
    
    // Show typing indicator
    showTypingIndicator();
    
    // Simulate AI response
    setTimeout(() => {
        const response = generateAIResponse(message);
        removeTypingIndicator();
        addMessageToChat(response, 'ai');
    }, 1500);
}

// Add Message to Chat
function addMessageToChat(message, sender) {
    const chatMessages = document.getElementById('chatMessages');
    const messageDiv = document.createElement('div');
    
    if (sender === 'user') {
        messageDiv.innerHTML = `
            <div class="flex items-start space-x-3 justify-end">
                <div class="bg-orange-600 text-white rounded-2xl rounded-tr-none px-4 py-3 max-w-lg">
                    <p>${message}</p>
                </div>
                <div class="w-10 h-10 bg-orange-200 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user text-orange-700"></i>
                </div>
            </div>
        `;
    } else {
        messageDiv.innerHTML = `
            <div class="flex items-start space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-robot text-white"></i>
                </div>
                <div class="bg-gray-100 rounded-2xl rounded-tl-none px-4 py-3 max-w-lg">
                    <p class="text-gray-800">${message}</p>
                </div>
            </div>
        `;
    }
    
    chatMessages.appendChild(messageDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Show Typing Indicator
function showTypingIndicator() {
    const chatMessages = document.getElementById('chatMessages');
    const typingDiv = document.createElement('div');
    typingDiv.id = 'typingIndicator';
    typingDiv.innerHTML = `
        <div class="flex items-start space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-robot text-white"></i>
            </div>
            <div class="bg-gray-100 rounded-2xl rounded-tl-none px-4 py-3">
                <div class="flex space-x-2">
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                </div>
            </div>
        </div>
    `;
    chatMessages.appendChild(typingDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Remove Typing Indicator
function removeTypingIndicator() {
    const indicator = document.getElementById('typingIndicator');
    if (indicator) indicator.remove();
}

// Generate AI Response
function generateAIResponse(userMessage) {
    const msg = userMessage.toLowerCase();
    
    // Next assignment responses
    if (msg.includes('next') || msg.includes('assignment') || msg.includes('task')) {
        return `<p class="font-semibold mb-2">Your Next Assignment:</p>
                <div class="text-sm space-y-2">
                    <p><strong>Event:</strong> Sunday Worship Service</p>
                    <p><strong>Date:</strong> This Sunday, 9:00 AM</p>
                    <p><strong>Role:</strong> Setup Chairs for Sunday Service</p>
                    <p><strong>Deadline:</strong> 2 days from now</p>
                    <p class="mt-2">💡 <em>Tip: Arrive 30 minutes early to prepare!</em></p>
                </div>`;
    }
    
    // Task tips
    if (msg.includes('tip') || msg.includes('help') || msg.includes('how')) {
        return `<p class="font-semibold mb-2">Task Completion Tips:</p>
                <ul class="text-sm space-y-1 list-disc ml-4">
                    <li>Break large tasks into smaller steps</li>
                    <li>Set a timer to stay focused</li>
                    <li>Ask your team leader for clarification</li>
                    <li>Document your progress</li>
                    <li>Celebrate small wins!</li>
                </ul>
                <p class="mt-2 text-sm">🎯 <em>You've got this!</em></p>`;
    }
    
    // Bible verse
    if (msg.includes('verse') || msg.includes('bible') || msg.includes('scripture')) {
        const verses = [
            { ref: 'Colossians 3:23', text: 'Whatever you do, work at it with all your heart, as working for the Lord, not for human masters.' },
            { ref: '1 Peter 4:10', text: 'Each of you should use whatever gift you have received to serve others, as faithful stewards of God\'s grace.' },
            { ref: 'Galatians 6:9', text: 'Let us not become weary in doing good, for at the proper time we will reap a harvest if we do not give up.' }
        ];
        const verse = verses[Math.floor(Math.random() * verses.length)];
        return `<p class="font-semibold mb-2">📖 Daily Verse:</p>
                <p class="text-sm italic mb-2">"${verse.text}"</p>
                <p class="text-xs text-gray-600">— ${verse.ref}</p>`;
    }
    
    // Event prep
    if (msg.includes('event') || msg.includes('prepare') || msg.includes('ready')) {
        return `<p class="font-semibold mb-2">Event Preparation Checklist:</p>
                <ul class="text-sm space-y-1">
                    <li>✓ Review event details</li>
                    <li>✓ Check your assignment</li>
                    <li>✓ Gather necessary materials</li>
                    <li>✓ Arrive on time</li>
                    <li>✓ Communicate with team</li>
                </ul>
                <p class="mt-2 text-sm">🌟 <em>You're well prepared!</em></p>`;
    }
    
    // Default response
    return `I'm here to help! I can assist you with:<br>
            • Checking your next assignment<br>
            • Providing task completion tips<br>
            • Sharing motivational Bible verses<br>
            • Helping you prepare for events<br><br>
            What would you like to know?`;
}

// Quick Action Buttons
function quickAction(action) {
    const actions = {
        'next_assignment': 'What\'s my next assignment?',
        'task_tips': 'Can you give me some task tips?',
        'daily_verse': 'Give me a daily Bible verse',
        'event_prep': 'Help me prepare for the next event'
    };
    
    document.getElementById('chatInput').value = actions[action];
    sendMessage();
}

// Voice Input
function startVoiceInput() {
    if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        const recognition = new SpeechRecognition();
        
        recognition.lang = 'en-US';
        recognition.continuous = false;
        
        recognition.onstart = function() {
            alert('🎤 Listening... Speak now!');
        };
        
        recognition.onresult = function(event) {
            const transcript = event.results[0][0].transcript;
            document.getElementById('chatInput').value = transcript;
            alert(`✓ Heard: "${transcript}"`);
        };
        
        recognition.onerror = function(event) {
            alert('⚠️ Voice input error. Please type instead.');
        };
        
        recognition.start();
    } else {
        alert('⚠️ Voice input not supported in this browser.\n\nPlease use Chrome or Edge.');
    }
}
</script>
@endsection
