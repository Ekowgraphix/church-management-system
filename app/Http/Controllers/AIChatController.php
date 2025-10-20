<?php

namespace App\Http\Controllers;

use App\Services\AIChatService;
use Illuminate\Http\Request;

class AIChatController extends Controller
{
    protected $chatService;

    public function __construct(AIChatService $chatService)
    {
        $this->middleware('auth');
        $this->chatService = $chatService;
    }

    public function index()
    {
        try {
            $conversations = $this->chatService->getUserConversations(auth()->id());
            $roles = $this->chatService->getAvailableRoles();
            
            // Show member-specific view for Church Members
            if (auth()->user()->hasRole('Church Member')) {
                return view('ai-chat.member-index', compact('conversations', 'roles'));
            }
            
            return view('ai-chat.index', compact('conversations', 'roles'));
        } catch (\Exception $e) {
            // If service fails, return simple view
            if (auth()->user()->hasRole('Church Member')) {
                return view('ai-chat.member-index', [
                    'conversations' => collect(),
                    'roles' => []
                ]);
            }
            
            return view('ai-chat.index', [
                'conversations' => collect(),
                'roles' => []
            ]);
        }
    }

    public function divineHub()
    {
        return view('ai-chat.divine-hub');
    }

    public function newConversation(Request $request)
    {
        $role = $request->get('role');
        $conversation = $this->chatService->createConversation(auth()->id(), null, $role);
        return redirect()->route('chat.show', $conversation->id);
    }

    public function show($id)
    {
        try {
            $conversation = $this->chatService->getConversation($id);
            $conversations = $this->chatService->getUserConversations(auth()->id());
            
            return view('ai-chat.show', compact('conversation', 'conversations'));
        } catch (\Exception $e) {
            return redirect()->route('ai.chat')->with('error', 'Conversation not found');
        }
    }

    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        try {
            $result = $this->chatService->sendMessage($id, $request->message, auth()->id());
            
            return response()->json([
                'success' => true,
                'user_message' => $result['user_message'],
                'assistant_message' => $result['assistant_message'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message',
            ], 500);
        }
    }

    public function deleteConversation($id)
    {
        try {
            $this->chatService->deleteConversation($id, auth()->id());
            return redirect()->route('chat.index')->with('success', 'Conversation deleted');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete conversation');
        }
    }

    public function clearConversation($id)
    {
        try {
            $this->chatService->clearConversation($id, auth()->id());
            return redirect()->route('chat.show', $id)->with('success', 'Conversation cleared');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to clear conversation');
        }
    }

    public function exportConversation($id)
    {
        try {
            $content = $this->chatService->exportConversation($id, auth()->id());
            $conversation = $this->chatService->getConversation($id);
            
            $filename = 'chat-export-' . date('Y-m-d-His') . '.txt';
            
            return response($content)
                ->header('Content-Type', 'text/plain')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to export conversation');
        }
    }

    // Methods for simple AI chat routes
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        try {
            // Simple AI response (you can integrate with OpenAI or other AI services here)
            $userMessage = $request->message;
            $aiResponse = $this->generateSimpleResponse($userMessage);

            return response()->json([
                'success' => true,
                'message' => $aiResponse
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, I encountered an error. Please try again.'
            ], 500);
        }
    }

    public function history()
    {
        try {
            return view('ai-chat.history', [
                'conversations' => $this->chatService->getUserConversations(auth()->id())
            ]);
        } catch (\Exception $e) {
            return view('ai-chat.history', ['conversations' => collect()]);
        }
    }

    public function clear()
    {
        try {
            // Clear all conversations for the user
            $this->chatService->clearAllConversations(auth()->id());
            return redirect()->route('ai.chat')->with('success', 'All conversations cleared');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to clear conversations');
        }
    }

    // Simple AI response generator (placeholder - integrate with real AI API)
    private function generateSimpleResponse($message)
    {
        $message = strtolower($message);
        
        // Simple keyword-based responses
        if (str_contains($message, 'hello') || str_contains($message, 'hi')) {
            return "Hello! I'm the Church Management AI Assistant. How can I help you today?";
        }
        
        if (str_contains($message, 'prayer')) {
            return "I can help you with prayer requests, prayer schedules, and prayer ministry coordination. What would you like to know?";
        }
        
        if (str_contains($message, 'event')) {
            return "I can assist with event planning, scheduling, and management. What event information do you need?";
        }
        
        if (str_contains($message, 'member')) {
            return "I can help with member information, attendance tracking, and member engagement. What would you like to know?";
        }
        
        if (str_contains($message, 'donation') || str_contains($message, 'giving') || str_contains($message, 'tithe')) {
            return "I can assist with donation tracking, giving reports, and financial stewardship. How can I help?";
        }
        
        // Default response
        return "I'm here to help with church management tasks. You can ask me about members, events, donations, prayer requests, and more. What would you like to know?";
    }
}
