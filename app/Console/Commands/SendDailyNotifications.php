<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Member;
use App\Models\Event;
use App\Models\Followup;
use App\Models\ChildrenMinistry;
use App\Services\NotificationService;
use Carbon\Carbon;

class SendDailyNotifications extends Command
{
    protected $signature = 'notifications:send-daily';
    protected $description = 'Send daily automated notifications for birthdays, anniversaries, and reminders';

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    public function handle()
    {
        $this->info('Starting daily notification checks...');
        
        $this->checkBirthdays();
        $this->checkAnniversaries();
        $this->checkMembershipMilestones();
        $this->checkEventReminders();
        $this->checkFollowupReminders();
        $this->cleanupOldNotifications();
        
        $this->info('Daily notifications sent successfully!');
        return 0;
    }

    protected function checkBirthdays()
    {
        $today = Carbon::today();
        
        // Check member birthdays
        $members = Member::whereMonth('date_of_birth', $today->month)
            ->whereDay('date_of_birth', $today->day)
            ->get();

        foreach ($members as $member) {
            $this->notificationService->sendBirthdayReminder($member);
            $this->info("Birthday notification sent for: {$member->full_name}");
        }

        // Check children birthdays
        $children = ChildrenMinistry::whereMonth('dob', $today->month)
            ->whereDay('dob', $today->day)
            ->get();

        foreach ($children as $child) {
            // You can create a similar notification for children
            $this->info("Birthday found for child: {$child->child_name}");
        }

        $this->info("Checked birthdays: " . ($members->count() + $children->count()) . " found");
    }

    protected function checkAnniversaries()
    {
        $today = Carbon::today();
        
        $members = Member::whereNotNull('wedding_anniversary')
            ->whereMonth('wedding_anniversary', $today->month)
            ->whereDay('wedding_anniversary', $today->day)
            ->get();

        foreach ($members as $member) {
            $this->notificationService->sendAnniversaryReminder($member);
            $this->info("Anniversary notification sent for: {$member->full_name}");
        }

        $this->info("Checked anniversaries: " . $members->count() . " found");
    }

    protected function checkMembershipMilestones()
    {
        $today = Carbon::today();
        
        // Check for 1 year, 5 years, 10 years, etc.
        $milestones = [1, 5, 10, 15, 20, 25, 30, 40, 50];
        
        foreach ($milestones as $years) {
            $targetDate = $today->copy()->subYears($years);
            
            $members = Member::whereMonth('membership_date', $targetDate->month)
                ->whereDay('membership_date', $targetDate->day)
                ->whereYear('membership_date', $targetDate->year)
                ->get();

            foreach ($members as $member) {
                $this->notificationService->sendMembershipMilestone($member, "{$years} years");
                $this->info("Milestone notification sent for: {$member->full_name} - {$years} years");
            }
        }

        $this->info("Checked membership milestones");
    }

    protected function checkEventReminders()
    {
        // Events happening tomorrow
        $tomorrow = Carbon::tomorrow();
        
        $events = Event::whereDate('start_date', $tomorrow)->get();

        foreach ($events as $event) {
            $this->notificationService->sendEventReminder($event);
            $this->info("Event reminder sent for: {$event->event_name}");
        }

        // Events happening in 7 days
        $nextWeek = Carbon::today()->addDays(7);
        
        $upcomingEvents = Event::whereDate('start_date', $nextWeek)->get();

        foreach ($upcomingEvents as $event) {
            $this->notificationService->sendEventReminder($event);
            $this->info("Weekly event reminder sent for: {$event->event_name}");
        }

        $this->info("Checked event reminders: " . ($events->count() + $upcomingEvents->count()) . " found");
    }

    protected function checkFollowupReminders()
    {
        $today = Carbon::today();
        
        // Follow-ups due today
        $followups = Followup::whereDate('followup_date', $today)
            ->where('status', '!=', 'completed')
            ->get();

        foreach ($followups as $followup) {
            $this->notificationService->sendFollowupReminder($followup);
            $this->info("Follow-up reminder sent for: {$followup->visitor->full_name}");
        }

        $this->info("Checked follow-up reminders: " . $followups->count() . " found");
    }

    protected function cleanupOldNotifications()
    {
        $this->notificationService->deleteOldNotifications(30);
        $this->info("Cleaned up old notifications (older than 30 days)");
    }
}

