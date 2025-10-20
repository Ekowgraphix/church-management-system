<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display the chat interface
     */
    public function index()
    {
        // Get all users except current user (Church Members only)
        $users = User::whereHas('roles', function($q) {
            $q->where('name', 'Church Member');
        })
        ->where('id', '!=', Auth::id())
        ->where('is_active', true)
        ->whereNotNull('email_verified_at')
        ->withCount(['sentMessages as unread_count' => function($q) {
            $q->where('receiver_id', Auth::id())
              ->whereNull('read_at');
        }])
        ->orderBy('name')
        ->get();

        return view('chat.members', compact('users'));
    }

    /**
     * Fetch messages between current user and another user
     */
    public function fetchMessages($userId)
    {
        $messages = Message::between(Auth::id(), $userId)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages from this user as read
        Message::where('sender_id', $userId)
            ->where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json($messages);
    }

    /**
     * Send a message
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:5000',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        $message->load('sender', 'receiver');

        // Broadcast the message
        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Get unread message count
     */
    public function getUnreadCount()
    {
        $count = Message::where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Mark all messages from a user as read
     */
    public function markAsRead($userId)
    {
        Message::where('sender_id', $userId)
            ->where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Search users for chat
     */
    public function searchUsers(Request $request)
    {
        $query = $request->input('query');

        $users = User::whereHas('roles', function($q) {
            $q->where('name', 'Church Member');
        })
        ->where('id', '!=', Auth::id())
        ->where('is_active', true)
        ->whereNotNull('email_verified_at')
        ->where(function($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('email', 'like', "%{$query}%");
        })
        ->limit(20)
        ->get(['id', 'name', 'email']);

        return response()->json($users);
    }
}
