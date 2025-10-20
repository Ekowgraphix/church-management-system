<?php

namespace App\Services;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Http;

class AIChatService
{
    // This service can integrate with OpenAI API, Claude, or other AI services
    // For demonstration, I'll create a mock response system
    
    public function createConversation($userId, $title = null, $role = null)
    {
        $conversation = ChatConversation::create([
            'user_id' => $userId,
            'title' => $title ?? 'New Conversation',
            'is_active' => true,
            'last_message_at' => now(),
            'context' => $role ? ['role' => $role] : null,
        ]);

        // Add system message based on role preset
        if ($role) {
            $systemMessage = $this->getRoleSystemMessage($role);
            ChatMessage::create([
                'chat_conversation_id' => $conversation->id,
                'role' => 'system',
                'content' => $systemMessage,
            ]);
        }

        return $conversation;
    }

    public function getUserConversations($userId)
    {
        return ChatConversation::where('user_id', $userId)
            ->where('is_active', true)
            ->orderBy('last_message_at', 'desc')
            ->get();
    }

    public function getConversation($conversationId)
    {
        return ChatConversation::with('messages')->findOrFail($conversationId);
    }

    public function sendMessage($conversationId, $content, $userId)
    {
        $conversation = ChatConversation::findOrFail($conversationId);
        
        // Verify user owns this conversation
        if ($conversation->user_id !== $userId) {
            throw new \Exception('Unauthorized');
        }

        // Save user message
        $userMessage = ChatMessage::create([
            'chat_conversation_id' => $conversationId,
            'role' => 'user',
            'content' => $content,
        ]);

        // Get AI response
        $aiResponse = $this->getAIResponse($conversationId, $content);

        // Save AI response
        $assistantMessage = ChatMessage::create([
            'chat_conversation_id' => $conversationId,
            'role' => 'assistant',
            'content' => $aiResponse,
        ]);

        // Update conversation
        $conversation->update([
            'last_message_at' => now(),
            'title' => $conversation->title === 'New Conversation' ? 
                $this->generateTitle($content) : $conversation->title,
        ]);

        return [
            'user_message' => $userMessage,
            'assistant_message' => $assistantMessage,
        ];
    }

    private function getAIResponse($conversationId, $userMessage)
    {
        // METHOD 1: Use OpenAI API (requires API key in .env)
        if (config('services.openai.api_key')) {
            return $this->getOpenAIResponse($conversationId, $userMessage);
        }

        // METHOD 2: Mock intelligent responses for demonstration
        return $this->getMockAIResponse($userMessage);
    }

    private function getOpenAIResponse($conversationId, $userMessage)
    {
        try {
            // Get conversation history for context (last 10 messages for better context)
            $messages = ChatMessage::where('chat_conversation_id', $conversationId)
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get()
                ->reverse()
                ->map(function($msg) {
                    return [
                        'role' => $msg->role === 'system' ? 'system' : $msg->role,
                        'content' => $msg->content,
                    ];
                })
                ->toArray();

            // Add comprehensive system message for church context
            array_unshift($messages, [
                'role' => 'system',
                'content' => 'You are a helpful and knowledgeable AI assistant for a Church Management System in Ghana. Your role is to assist pastors, church administrators, and ministry leaders with:

1. **Church Operations**: Member management, attendance tracking, financial management
2. **Spiritual Content**: Sermon outlines, prayer points, devotionals, Bible study materials
3. **Administrative Tasks**: Announcements, bulletins, reports, event planning
4. **Ministry Support**: Small groups, children\'s ministry, outreach programs
5. **Financial Guidance**: Donation tracking, pledges, budgets, expense management
6. **Communication**: SMS campaigns, email notifications, prayer requests

Always be:
- Respectful and considerate of Christian faith and values
- Practical and actionable in your suggestions
- Clear and concise in your responses
- Culturally aware of Ghanaian church context
- Helpful with both spiritual and administrative matters

You can help draft content, answer questions, provide guidance, and assist with system features.'
            ]);

            // Call OpenAI API with optimized settings
            $response = Http::timeout(30)->withHeaders([
                'Authorization' => 'Bearer ' . config('services.openai.api_key'),
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => config('services.openai.model', 'gpt-4o-mini'), // Use gpt-4o-mini by default
                'messages' => $messages,
                'temperature' => 0.8, // Slightly higher for more creative responses
                'max_tokens' => 600, // Increased for better responses
                'presence_penalty' => 0.1,
                'frequency_penalty' => 0.1,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['choices'][0]['message']['content'])) {
                    return $data['choices'][0]['message']['content'];
                }
            }

            // Log error for debugging
            \Log::warning('OpenAI API failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return $this->getMockAIResponse($userMessage);
        } catch (\Exception $e) {
            \Log::error('OpenAI API error: ' . $e->getMessage());
            return $this->getMockAIResponse($userMessage);
        }
    }

    private function getMockAIResponse($userMessage)
    {
        $message = strtolower($userMessage);

        // Church-specific responses
        if (str_contains($message, 'member') || str_contains($message, 'attendance')) {
            return "I can help you with member management and attendance tracking! Our system allows you to:\n\n• Add and manage member profiles\n• Track attendance with QR codes\n• View attendance reports and trends\n• Monitor member engagement\n\nWould you like me to guide you through any specific feature?";
        }

        if (str_contains($message, 'donation') || str_contains($message, 'giving') || str_contains($message, 'financial')) {
            return "Our financial management system includes:\n\n• Online donation processing\n• Pledge tracking and management\n• Expense tracking and approval\n• Financial reports and analytics\n• Budget monitoring\n\nWhat specific financial feature would you like to learn about?";
        }

        if (str_contains($message, 'event')) {
            return "Event management features include:\n\n• Create and schedule events\n• Track registrations and attendance\n• Send event notifications\n• Manage event resources\n\nHow can I help you with events?";
        }

        if (str_contains($message, 'prayer')) {
            return "Our prayer request system allows:\n\n• Submit prayer requests\n• Prayer chain notifications\n• Track answered prayers\n• Privacy controls\n• Response and testimony sharing\n\nWould you like to know more about prayer management?";
        }

        if (str_contains($message, 'report') || str_contains($message, 'analytics')) {
            return "The analytics and reporting features provide:\n\n• Interactive dashboards\n• Attendance pattern analysis\n• Financial trend reports\n• Growth metrics\n• Member engagement analytics\n• Custom reports and exports\n\nWhat kind of report are you interested in?";
        }

        if (str_contains($message, 'hello') || str_contains($message, 'hi')) {
            return "Hello! 👋 I'm your Church Management AI Assistant. I can help you with:\n\n• Member management\n• Attendance tracking\n• Financial management\n• Event planning\n• Prayer requests\n• Reports and analytics\n• System features and guidance\n\nHow can I assist you today?";
        }

        if (str_contains($message, 'help')) {
            return "I'm here to help! I can assist you with:\n\n📊 **Dashboard & Reports** - View statistics and generate reports\n👥 **Members** - Manage member profiles and information\n📅 **Attendance** - Track and analyze attendance\n💰 **Finance** - Handle donations, expenses, and budgets\n🙏 **Prayer** - Manage prayer requests and chains\n📧 **Communication** - Send SMS and notifications\n🔒 **Security** - User access and data protection\n\nWhat would you like to know more about?";
        }

        if (str_contains($message, 'thank')) {
            return "You're welcome! If you have any other questions about the church management system, feel free to ask. I'm here to help! 😊";
        }

        // Default intelligent response
        return "I understand you're asking about: \"" . substr($userMessage, 0, 50) . (strlen($userMessage) > 50 ? '..."' : '"') . "\n\nI'm your Church Management AI Assistant. I can help you with:\n\n• Member and attendance management\n• Financial tracking and reports\n• Event planning and coordination\n• Prayer request systems\n• Communication tools\n• Analytics and insights\n\nCould you please provide more details about what you'd like to know?";
    }

    private function generateTitle($firstMessage)
    {
        // Generate a short title from the first message
        $words = explode(' ', $firstMessage);
        $title = implode(' ', array_slice($words, 0, 5));
        return strlen($title) > 50 ? substr($title, 0, 47) . '...' : $title;
    }

    public function deleteConversation($conversationId, $userId)
    {
        $conversation = ChatConversation::findOrFail($conversationId);
        
        if ($conversation->user_id !== $userId) {
            throw new \Exception('Unauthorized');
        }

        $conversation->update(['is_active' => false]);
        return true;
    }

    public function clearConversation($conversationId, $userId)
    {
        $conversation = ChatConversation::findOrFail($conversationId);
        
        if ($conversation->user_id !== $userId) {
            throw new \Exception('Unauthorized');
        }

        ChatMessage::where('chat_conversation_id', $conversationId)->delete();
        return true;
    }

    public function exportConversation($conversationId, $userId)
    {
        $conversation = ChatConversation::with('messages')->findOrFail($conversationId);
        
        if ($conversation->user_id !== $userId) {
            throw new \Exception('Unauthorized');
        }

        $export = "=" . str_repeat("=", 60) . "\n";
        $export .= "CHURCH MANAGEMENT SYSTEM - AI CHAT EXPORT\n";
        $export .= "=" . str_repeat("=", 60) . "\n\n";
        $export .= "Conversation: {$conversation->title}\n";
        $export .= "Date: " . $conversation->created_at->format('F d, Y g:i A') . "\n";
        $export .= "\n" . str_repeat("-", 60) . "\n\n";

        foreach ($conversation->messages as $message) {
            if ($message->role === 'system') continue;
            
            $role = $message->role === 'user' ? 'YOU' : 'AI ASSISTANT';
            $time = $message->created_at->format('g:i A');
            
            $export .= "[{$time}] {$role}:\n";
            $export .= $message->content . "\n\n";
        }

        $export .= str_repeat("=", 60) . "\n";
        $export .= "End of conversation\n";
        $export .= "Exported: " . now()->format('F d, Y g:i A') . "\n";

        return $export;
    }

    private function getRoleSystemMessage($role)
    {
        return match($role) {
            'sermon_assistant' => "You are a Sermon Assistant. Help pastors draft sermon outlines, find relevant scriptures, create illustrations, and develop sermon series. Be theologically sound and practical.",
            
            'bible_study' => "You are a Bible Study Helper. Assist in creating Bible study materials, discussion questions, and study guides. Provide context, cross-references, and practical applications.",
            
            'prayer_helper' => "You are a Prayer Ministry Assistant. Help draft prayer points, create prayer guides, organize prayer chains, and provide scripture-based prayers for various occasions.",
            
            'admin_assistant' => "You are a Church Administrative Assistant. Help with announcements, bulletins, reports, meeting minutes, and administrative communications. Be clear and professional.",
            
            'worship_planner' => "You are a Worship Planning Assistant. Help plan worship services, suggest songs, create service orders, and organize special events like Easter, Christmas, etc.",
            
            'youth_ministry' => "You are a Youth Ministry Helper. Provide ideas for youth programs, games, devotionals, and engagement activities. Be creative and age-appropriate.",
            
            'outreach' => "You are an Outreach & Evangelism Assistant. Help plan outreach programs, create evangelistic materials, and suggest community engagement strategies.",
            
            default => "You are a helpful AI assistant for church management and ministry."
        };
    }

    public function getAvailableRoles()
    {
        return [
            [
                'id' => 'sermon_assistant',
                'name' => '📖 Sermon Assistant',
                'description' => 'Help with sermon outlines, scriptures, and illustrations',
                'icon' => 'fa-book-bible'
            ],
            [
                'id' => 'bible_study',
                'name' => '📚 Bible Study Helper',
                'description' => 'Create study materials and discussion questions',
                'icon' => 'fa-book-open'
            ],
            [
                'id' => 'prayer_helper',
                'name' => '🙏 Prayer Ministry',
                'description' => 'Draft prayer points and organize prayer chains',
                'icon' => 'fa-hands-praying'
            ],
            [
                'id' => 'admin_assistant',
                'name' => '📋 Admin Assistant',
                'description' => 'Help with announcements, bulletins, and reports',
                'icon' => 'fa-clipboard'
            ],
            [
                'id' => 'worship_planner',
                'name' => '🎵 Worship Planner',
                'description' => 'Plan worship services and special events',
                'icon' => 'fa-music'
            ],
            [
                'id' => 'youth_ministry',
                'name' => '👥 Youth Ministry',
                'description' => 'Youth programs, games, and activities',
                'icon' => 'fa-users'
            ],
            [
                'id' => 'outreach',
                'name' => '🌍 Outreach & Evangelism',
                'description' => 'Outreach programs and evangelistic materials',
                'icon' => 'fa-globe'
            ],
        ];
    }
}
