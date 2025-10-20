@extends('layouts.pastor')

@section('content')
<div>
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">🧠 AI Ministry Assistant</h1>
                <p class="text-gray-600">Your intelligent co-pastor for ministry support</p>
            </div>
        </div>
    </div>

    <!-- AI Chat Interface -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Quick Actions Sidebar -->
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="font-bold text-gray-800 mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <button onclick="quickAction('sermon')" class="w-full text-left px-4 py-3 bg-blue-50 hover:bg-blue-100 rounded-lg text-sm font-semibold text-blue-700 transition-all">
                        <i class="fas fa-book-open mr-2"></i>Sermon Prep Help
                    </button>
                    <button onclick="quickAction('prayer')" class="w-full text-left px-4 py-3 bg-purple-50 hover:bg-purple-100 rounded-lg text-sm font-semibold text-purple-700 transition-all">
                        <i class="fas fa-praying-hands mr-2"></i>Prayer Suggestions
                    </button>
                    <button onclick="quickAction('scripture')" class="w-full text-left px-4 py-3 bg-green-50 hover:bg-green-100 rounded-lg text-sm font-semibold text-green-700 transition-all">
                        <i class="fas fa-bible mr-2"></i>Scripture Search
                    </button>
                    <button onclick="quickAction('counseling')" class="w-full text-left px-4 py-3 bg-yellow-50 hover:bg-yellow-100 rounded-lg text-sm font-semibold text-yellow-700 transition-all">
                        <i class="fas fa-comments mr-2"></i>Counseling Tips
                    </button>
                    <button onclick="quickAction('devotional')" class="w-full text-left px-4 py-3 bg-red-50 hover:bg-red-100 rounded-lg text-sm font-semibold text-red-700 transition-all">
                        <i class="fas fa-pen mr-2"></i>Write Devotional
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
                    <div class="flex items-start space-x-3" data-message="ai">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-robot text-white"></i>
                        </div>
                        <div class="bg-gray-100 rounded-2xl rounded-tl-none px-4 py-3 max-w-lg">
                            <p class="text-gray-800">Hello Pastor! 👋 I'm your AI Ministry Assistant. How can I help you today? I can assist with:</p>
                            <ul class="mt-2 space-y-1 text-sm text-gray-700">
                                <li>• Sermon preparation and outlines</li>
                                <li>• Scripture references and interpretations</li>
                                <li>• Prayer writing and guidance</li>
                                <li>• Counseling advice and resources</li>
                                <li>• Devotional content creation</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Input Area -->
                <div class="border-t p-4">
                    <div class="flex space-x-3">
                        <input type="text" id="userInput" placeholder="Ask your ministry question..." class="flex-1 border border-gray-300 rounded-lg px-4 py-3" onkeypress="handleKeyPress(event)">
                        <button onclick="sendMessage()" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// AI Assistant Responses Database
const aiResponses = {
    sermon: {
        title: "Sermon Preparation Assistant",
        response: `I'd be happy to help with your sermon! Here's a structured outline:

📖 **Sermon Planning Framework:**

1. **Choose Your Scripture**: Select a passage that speaks to your congregation's needs
2. **Identify the Theme**: What is God saying through this text?
3. **Create Structure**:
   - Introduction (Hook)
   - Context & Background
   - Main Points (3-5 key insights)
   - Application
   - Conclusion & Call to Action

4. **Key Elements to Include**:
   - Personal stories or testimonies
   - Relevant illustrations
   - Practical applications
   - Scripture cross-references

Would you like help with a specific topic or scripture passage?`
    },
    prayer: {
        title: "Prayer Writing Guide",
        response: `Here are some powerful prayer frameworks:

🙏 **Prayer Structure:**

1. **Adoration** - Praise God for who He is
2. **Confession** - Acknowledge sins and shortcomings
3. **Thanksgiving** - Express gratitude
4. **Supplication** - Present requests

**Sample Opening Prayer:**
"Heavenly Father, we come before You with grateful hearts. You are our refuge and strength, an ever-present help in trouble. We thank You for gathering us today..."

**Sample Closing Prayer:**
"Lord, may Your Word dwell richly in our hearts. Go with us as we leave this place, and help us to be Your light in the world. In Jesus' name, Amen."

What type of prayer do you need? (Opening, Closing, Pastoral, Healing, etc.)`
    },
    scripture: {
        title: "Scripture Reference Guide",
        response: `I can help you find relevant scriptures! Here are some popular topics:

📚 **Common Scripture Topics:**

**Faith:** Hebrews 11:1, Romans 10:17, 2 Corinthians 5:7
**Love:** 1 Corinthians 13:4-8, John 3:16, 1 John 4:8
**Peace:** Philippians 4:6-7, John 14:27, Isaiah 26:3
**Strength:** Philippians 4:13, Isaiah 40:31, Psalm 46:1
**Hope:** Romans 15:13, Jeremiah 29:11, Psalm 42:11
**Salvation:** Acts 4:12, Ephesians 2:8-9, Romans 10:9

What topic or theme are you looking for? I can provide relevant verses with context!`
    },
    counseling: {
        title: "Pastoral Counseling Tips",
        response: `Here are key principles for effective pastoral counseling:

💬 **Counseling Framework:**

1. **Listen Actively**
   - Give full attention
   - Don't interrupt
   - Reflect back what you hear

2. **Ask Good Questions**
   - Open-ended questions
   - "How did that make you feel?"
   - "What do you think God is saying?"

3. **Offer Biblical Wisdom**
   - Share relevant scriptures
   - Pray with them
   - Offer hope in Christ

4. **Maintain Boundaries**
   - Keep confidentiality
   - Know when to refer to professionals
   - Set appropriate meeting times

5. **Follow Up**
   - Check in regularly
   - Pray for them daily
   - Connect them with resources

What specific counseling situation would you like guidance on?`
    },
    devotional: {
        title: "Devotional Writing Assistant",
        response: `Let me help you write a powerful devotional!

✍️ **Devotional Structure:**

1. **Title** - Catchy and relevant (e.g., "Finding Peace in the Storm")

2. **Scripture** - Choose 1-3 key verses

3. **Opening** - Start with a relatable story or question

4. **Body** (150-300 words)
   - Explain the scripture
   - Share insights
   - Make it practical

5. **Application** - "Today, I will..."

6. **Closing Prayer** - Brief and focused

**Sample Opening:**
"Have you ever felt overwhelmed by life's challenges? David did too. In Psalm 23, he reminds us that even in the darkest valley..."

Would you like me to help you develop a devotional on a specific theme?`
    }
};

// Send message function
function sendMessage() {
    const input = document.getElementById('userInput');
    const message = input.value.trim();
    
    if (message === '') return;
    
    // Add user message to chat
    addUserMessage(message);
    
    // Clear input
    input.value = '';
    
    // Show typing indicator
    showTypingIndicator();
    
    // Simulate AI response after delay
    setTimeout(() => {
        hideTypingIndicator();
        addAIResponse(message);
    }, 1500);
}

// Handle Enter key press
function handleKeyPress(event) {
    if (event.key === 'Enter') {
        sendMessage();
    }
}

// Quick action buttons
function quickAction(type) {
    const response = aiResponses[type];
    
    if (response) {
        // Add user message
        const userMessages = {
            sermon: "Help me prepare a sermon",
            prayer: "I need help writing a prayer",
            scripture: "Can you help me find relevant scriptures?",
            counseling: "Give me counseling tips",
            devotional: "Help me write a devotional"
        };
        
        addUserMessage(userMessages[type]);
        
        // Show typing indicator
        showTypingIndicator();
        
        // Add AI response after delay
        setTimeout(() => {
            hideTypingIndicator();
            addQuickActionResponse(response.title, response.response);
        }, 1500);
    }
}

// Add user message to chat
function addUserMessage(message) {
    const chatMessages = document.getElementById('chatMessages');
    const messageDiv = document.createElement('div');
    messageDiv.className = 'flex items-start space-x-3 justify-end';
    messageDiv.setAttribute('data-message', 'user');
    messageDiv.innerHTML = `
        <div class="bg-blue-600 text-white rounded-2xl rounded-tr-none px-4 py-3 max-w-lg">
            <p>${escapeHtml(message)}</p>
        </div>
        <div class="w-10 h-10 bg-blue-200 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fas fa-user text-blue-700"></i>
        </div>
    `;
    chatMessages.appendChild(messageDiv);
    scrollToBottom();
}

// Add AI response to chat
function addAIResponse(userMessage) {
    const chatMessages = document.getElementById('chatMessages');
    const messageDiv = document.createElement('div');
    messageDiv.className = 'flex items-start space-x-3';
    messageDiv.setAttribute('data-message', 'ai');
    
    // Generate contextual response
    let response = generateAIResponse(userMessage);
    
    messageDiv.innerHTML = `
        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fas fa-robot text-white"></i>
        </div>
        <div class="bg-gray-100 rounded-2xl rounded-tl-none px-4 py-3 max-w-lg">
            <p class="text-gray-800">${response}</p>
        </div>
    `;
    chatMessages.appendChild(messageDiv);
    scrollToBottom();
}

// Add quick action response
function addQuickActionResponse(title, response) {
    const chatMessages = document.getElementById('chatMessages');
    const messageDiv = document.createElement('div');
    messageDiv.className = 'flex items-start space-x-3';
    messageDiv.setAttribute('data-message', 'ai');
    messageDiv.innerHTML = `
        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fas fa-robot text-white"></i>
        </div>
        <div class="bg-gray-100 rounded-2xl rounded-tl-none px-4 py-3 max-w-lg">
            <p class="text-gray-800 font-semibold mb-2">${title}</p>
            <div class="text-sm text-gray-700 whitespace-pre-line">${response}</div>
        </div>
    `;
    chatMessages.appendChild(messageDiv);
    scrollToBottom();
}

// Generate contextual AI response
function generateAIResponse(message) {
    const lowerMessage = message.toLowerCase();
    
    // Check for keywords and respond accordingly
    if (lowerMessage.includes('sermon') || lowerMessage.includes('preach')) {
        return "I'd be happy to help with sermon preparation! What topic or scripture passage would you like to focus on? I can help create an outline, suggest illustrations, or provide relevant scriptures.";
    } else if (lowerMessage.includes('prayer') || lowerMessage.includes('pray')) {
        return "I can help you craft a meaningful prayer. What type of prayer do you need? (Opening prayer, closing prayer, pastoral prayer, healing prayer, etc.) Also, what's the occasion or focus?";
    } else if (lowerMessage.includes('scripture') || lowerMessage.includes('verse') || lowerMessage.includes('bible')) {
        return "I can help you find relevant scriptures! What topic, theme, or life situation are you looking for verses about? (e.g., faith, hope, love, forgiveness, strength, etc.)";
    } else if (lowerMessage.includes('counsel') || lowerMessage.includes('advice')) {
        return "Pastoral counseling is an important ministry. What kind of counseling situation are you dealing with? I can provide biblical principles and practical approaches. Remember, for serious mental health issues, professional referral may be necessary.";
    } else if (lowerMessage.includes('devotional') || lowerMessage.includes('quiet time')) {
        return "I'd love to help you write a devotional! What theme or scripture would you like to base it on? A good devotional includes: a scripture passage, a relatable story, practical application, and a closing prayer.";
    } else if (lowerMessage.includes('thank') || lowerMessage.includes('thanks')) {
        return "You're very welcome, Pastor! I'm here to serve and support your ministry. Is there anything else I can help you with today? 🙏";
    } else if (lowerMessage.includes('hello') || lowerMessage.includes('hi')) {
        return "Hello Pastor! 👋 How can I assist you with your ministry today? Feel free to ask about sermon prep, prayers, scriptures, counseling, or devotional writing!";
    } else {
        return "That's a great question! While I'm still learning and my full AI capabilities are coming soon, I can help you with:<br><br>• Sermon preparation frameworks<br>• Prayer writing guides<br>• Scripture references<br>• Counseling principles<br>• Devotional structure<br><br>Try using the Quick Actions buttons on the left, or ask me about any of these topics!";
    }
}

// Show typing indicator
function showTypingIndicator() {
    const chatMessages = document.getElementById('chatMessages');
    const typingDiv = document.createElement('div');
    typingDiv.id = 'typingIndicator';
    typingDiv.className = 'flex items-start space-x-3';
    typingDiv.innerHTML = `
        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fas fa-robot text-white"></i>
        </div>
        <div class="bg-gray-100 rounded-2xl rounded-tl-none px-4 py-3">
            <div class="flex space-x-2">
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
            </div>
        </div>
    `;
    chatMessages.appendChild(typingDiv);
    scrollToBottom();
}

// Hide typing indicator
function hideTypingIndicator() {
    const typingDiv = document.getElementById('typingIndicator');
    if (typingDiv) {
        typingDiv.remove();
    }
}

// Scroll to bottom of chat
function scrollToBottom() {
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Escape HTML to prevent XSS
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Focus input on page load
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('userInput').focus();
});
</script>
@endsection
