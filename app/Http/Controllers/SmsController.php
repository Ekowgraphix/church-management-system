<?php

namespace App\Http\Controllers;

use App\Models\SmsCampaign;
use App\Models\SmsTemplate;
use App\Models\SmsMessage;
use App\Models\Member;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function index()
    {
        $campaigns = SmsCampaign::with('createdBy')->latest()->paginate(20);
        return view('sms.index', compact('campaigns'));
    }

    public function create()
    {
        $templates = SmsTemplate::where('is_active', true)->get();
        return view('sms.create', compact('templates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'message' => 'required|string|max:500',
            'template_id' => 'nullable|exists:sms_templates,id',
            'recipient_type' => 'required|in:all_members,specific_members,group,custom',
            'recipient_filters' => 'nullable|array',
            'scheduled_at' => 'nullable|date',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['status'] = $request->filled('scheduled_at') ? 'scheduled' : 'draft';

        $campaign = SmsCampaign::create($validated);

        return redirect()->route('sms.show', $campaign)
            ->with('success', 'SMS campaign created successfully.');
    }

    public function show(SmsCampaign $campaign)
    {
        $campaign->load(['messages', 'template', 'createdBy']);
        return view('sms.show', compact('campaign'));
    }

    public function send(SmsCampaign $campaign)
    {
        // Get recipients based on campaign settings
        $recipients = $this->getRecipients($campaign);

        $campaign->update([
            'total_recipients' => $recipients->count(),
            'status' => 'sending',
        ]);

        foreach ($recipients as $recipient) {
            SmsMessage::create([
                'campaign_id' => $campaign->id,
                'member_id' => $recipient->id,
                'phone_number' => $recipient->phone,
                'message' => $campaign->message,
                'status' => 'pending',
            ]);
        }

        // Here you would integrate with your SMS provider
        // For now, we'll just mark as sent
        $campaign->update([
            'status' => 'completed',
            'sent_at' => now(),
            'sent_count' => $recipients->count(),
        ]);

        return redirect()->route('sms.show', $campaign)
            ->with('success', 'SMS campaign sent successfully.');
    }

    private function getRecipients(SmsCampaign $campaign)
    {
        $query = Member::active();

        if ($campaign->recipient_type === 'specific_members' && $campaign->recipient_filters) {
            $query->whereIn('id', $campaign->recipient_filters);
        }

        return $query->get();
    }

    public function templates()
    {
        $templates = SmsTemplate::latest()->paginate(20);
        return view('sms.templates', compact('templates'));
    }
}
