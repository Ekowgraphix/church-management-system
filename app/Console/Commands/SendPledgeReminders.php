<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pledge;
use App\Models\PledgeCampaign;
use App\Services\NotificationService;
use Carbon\Carbon;

class SendPledgeReminders extends Command
{
    protected $signature = 'pledges:send-reminders';
    protected $description = 'Send reminders for outstanding pledges';

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    public function handle()
    {
        $this->info('Checking pledges for reminders...');
        
        // Get active campaigns with reminders enabled
        $campaigns = PledgeCampaign::where('status', 'active')
            ->where('send_reminders', true)
            ->get();

        $remindersSent = 0;

        foreach ($campaigns as $campaign) {
            // Get incomplete pledges for this campaign
            $pledges = Pledge::where('campaign_id', $campaign->id)
                ->where('status', 'active')
                ->whereColumn('amount_paid', '<', 'pledge_amount')
                ->with('member')
                ->get();

            foreach ($pledges as $pledge) {
                // Check if reminder is due (based on campaign frequency)
                $lastReminder = $pledge->last_reminder_sent ?? $pledge->created_at;
                $daysSinceLastReminder = Carbon::parse($lastReminder)->diffInDays(Carbon::now());

                if ($daysSinceLastReminder >= $campaign->reminder_frequency_days) {
                    $this->sendPledgeReminder($pledge, $campaign);
                    
                    // Update last reminder sent
                    $pledge->update(['last_reminder_sent' => now()]);
                    
                    $remindersSent++;
                    $this->info("Reminder sent to: {$pledge->member->full_name} for campaign: {$campaign->campaign_name}");
                }
            }
        }

        $this->info("Total reminders sent: {$remindersSent}");
        return 0;
    }

    protected function sendPledgeReminder($pledge, $campaign)
    {
        $remaining = $pledge->remaining_amount;
        $completion = round($pledge->completion_percentage, 1);

        $this->notificationService->sendCustomNotification(
            auth()->id() ?? 1,
            '💰 Pledge Reminder',
            "Reminder: {$pledge->member->full_name} has a pledge of \${$pledge->pledge_amount} for '{$campaign->campaign_name}'. {$completion}% complete. Remaining: \${$remaining}",
            'pledge_reminder',
            route('pledges.show', $pledge),
            [
                'pledge_id' => $pledge->id,
                'member_id' => $pledge->member_id,
                'campaign_id' => $campaign->id,
                'remaining_amount' => $remaining,
            ]
        );
    }
}
