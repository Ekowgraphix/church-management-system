<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Member;
use App\Models\User;
use Carbon\Carbon;

class NotificationService
{
    // Send Birthday Reminder
    public function sendBirthdayReminder(Member $member)
    {
        $users = User::all(); // Send to all admins
        
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'birthday_reminder',
                'title' => '🎂 Birthday Today!',
                'message' => "{$member->full_name} is celebrating their birthday today!",
                'data' => [
                    'member_id' => $member->id,
                    'member_name' => $member->full_name,
                    'age' => $member->age,
                ],
                'action_url' => route('members.show', $member),
            ]);
        }
    }

    // Send Anniversary Reminder
    public function sendAnniversaryReminder(Member $member)
    {
        if (!$member->wedding_anniversary) return;

        $years = Carbon::parse($member->wedding_anniversary)->diffInYears(Carbon::now());
        $users = User::all();
        
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'anniversary_reminder',
                'title' => '💑 Anniversary Today!',
                'message' => "{$member->full_name} is celebrating {$years} years of marriage!",
                'data' => [
                    'member_id' => $member->id,
                    'member_name' => $member->full_name,
                    'years' => $years,
                ],
                'action_url' => route('members.show', $member),
            ]);
        }
    }

    // Send Membership Milestone
    public function sendMembershipMilestone(Member $member, $milestone)
    {
        $users = User::all();
        
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'membership_milestone',
                'title' => '🏆 Membership Milestone!',
                'message' => "{$member->full_name} has reached {$milestone} milestone!",
                'data' => [
                    'member_id' => $member->id,
                    'member_name' => $member->full_name,
                    'milestone' => $milestone,
                ],
                'action_url' => route('members.show', $member),
            ]);
        }
    }

    // Send Event Reminder
    public function sendEventReminder($event, $recipientType = 'all')
    {
        $users = User::all();
        
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'event_reminder',
                'title' => '📅 Event Reminder',
                'message' => "Reminder: {$event->event_name} is coming up on {$event->start_date->format('M d, Y')}",
                'data' => [
                    'event_id' => $event->id,
                    'event_name' => $event->event_name,
                    'start_date' => $event->start_date,
                ],
                'action_url' => route('events.show', $event),
            ]);
        }
    }

    // Send Follow-up Reminder
    public function sendFollowupReminder($followup)
    {
        $users = User::all();
        
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'followup_reminder',
                'title' => '📋 Follow-up Due',
                'message' => "Follow-up due for {$followup->visitor->full_name} on {$followup->followup_date->format('M d, Y')}",
                'data' => [
                    'followup_id' => $followup->id,
                    'visitor_name' => $followup->visitor->full_name,
                    'due_date' => $followup->followup_date,
                ],
                'action_url' => route('followups.show', $followup),
            ]);
        }
    }

    // Send Absence Notification
    public function sendAbsenceNotification(Member $member)
    {
        $users = User::all();
        
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'absence_alert',
                'title' => '⚠️ Member Absent',
                'message' => "{$member->full_name} was absent from the last service",
                'data' => [
                    'member_id' => $member->id,
                    'member_name' => $member->full_name,
                ],
                'action_url' => route('members.show', $member),
            ]);
        }
    }

    // Send Custom Notification
    public function sendCustomNotification($userId, $title, $message, $type = 'custom', $actionUrl = null, $data = [])
    {
        Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'action_url' => $actionUrl,
        ]);
    }

    // Mark as Read
    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification) {
            $notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    // Mark All as Read
    public function markAllAsRead($userId)
    {
        Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    // Get Unread Count
    public function getUnreadCount($userId)
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }

    // Delete Old Notifications
    public function deleteOldNotifications($days = 30)
    {
        Notification::where('created_at', '<', Carbon::now()->subDays($days))
            ->where('is_read', true)
            ->delete();
    }
}
