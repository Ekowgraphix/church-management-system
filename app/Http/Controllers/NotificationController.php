<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    // Notification Center
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->paginate(20);

        $unreadCount = $this->notificationService->getUnreadCount(auth()->id());

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    // Mark as Read
    public function markAsRead($id)
    {
        $this->notificationService->markAsRead($id);
        return back()->with('success', 'Notification marked as read');
    }

    // Mark All as Read
    public function markAllAsRead()
    {
        $this->notificationService->markAllAsRead(auth()->id());
        return back()->with('success', 'All notifications marked as read');
    }

    // Delete Notification
    public function destroy($id)
    {
        $notification = Notification::where('user_id', auth()->id())->findOrFail($id);
        $notification->delete();
        return back()->with('success', 'Notification deleted');
    }

    // Get Unread Notifications (API for header)
    public function getUnread()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->unread()
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $notifications->count(),
        ]);
    }
}
